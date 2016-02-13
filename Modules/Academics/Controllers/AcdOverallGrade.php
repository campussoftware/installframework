<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdOverallGrade
 *
 * @author ramesh
 */
class Modules_Academics_Controllers_AcdOverallGrade extends Core_Controllers_NodeController
{
    //put your code here
    public function acdOverallGradeAfterDataUpdate()
    {        
        $acd_overall_grade_id=$this->_requestedData['id'];
        $requestdata=$this->_requestedData;
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_subgroupexams");
        $db->addFieldArray(array("acd_examination_schedule_id"=>"esid"));
        $db->addFieldArray(array("percentage"=>"percentage"));
        $db->addWhere("acd_exam_group_id='".$requestdata['acd_exam_group_id']."'");
        $db->buildSelect();
        
        $totalexamgroupdeails=$db->getRows("esid","percentage");
	if(!is_array($totalexamgroupdeails))
	{
	    $totalexamgroupdeails=array();
	}
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_studentmarks_details","smd");
        $db->addFieldArray(array("smd.acd_examination_schedule_id"=>"esid"));
        $db->addField("smd.list_branch_id");
        $db->addField("smd.cur_branch_orientation_id");
        $db->addField("smd.cur_branch_class_id");
        $db->addField("smd.cur_branch_class_section_id");        
        $db->addField("smd.admission_no");
        $db->addField("smd.name");
        $db->addField("smd.cur_class_subject_id");
        $db->addField("smd.max_marks");
        $db->addField("smd.obtained_marks");
        $db->addField("smd.core_attendance_status_id");
        $db->addField("smd.gradepoints");
        $db->addField("smd.grade");
        $db->addField("smd.remarks");
        $db->addWhere("smd.acd_examination_schedule_id in ('".Core::covertArrayToString(Core::getKeysFromArray($totalexamgroupdeails),"','")."')");
        $db->buildSelect();
       
        $studentmarksdetails=$db->getRows();
        
        $finalmarks=array();
	
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_exam_group");        
        $db->addWhere("id='".$requestdata['acd_exam_group_id']."'");
        $db->buildSelect();       
        $exam_groupdetails=$db->getRow();
        
        $grade_method_id=$exam_groupdetails['overall_grade_id'];
	$exam_name_id=$exam_groupdetails['acd_exam_name_id'];
	$exam_schedule_id=$exam_groupdetails['acd_examination_schedule_id'];
	
	$list_academicyear_id=$requestdata['cur_list_academicyear_id'];
        if(count($studentmarksdetails)>0)
	{
	    foreach($studentmarksdetails as $smd)
	    {
                $admission_no=$smd['admission_no'];
                $list_branch_id=$smd['list_branch_id'];
                $branch_orientation_id=$smd['cur_branch_orientation_id'];
                $branch_class_id=$smd['cur_branch_class_id'];
                $branch_class_section_id=$smd['cur_branch_class_section_id'];
                               
                $finalmarks[$list_branch_id][$branch_orientation_id][$branch_class_id][$branch_class_section_id][$admission_no]['max_marks']+=round($smd['max_marks']*($totalexamgroupdeails[$smd['esid']]/100),2);
                $finalmarks[$list_branch_id][$branch_orientation_id][$branch_class_id][$branch_class_section_id][$admission_no]['section']=$branch_class_section_id;
                $finalmarks[$list_branch_id][$branch_orientation_id][$branch_class_id][$branch_class_section_id][$admission_no]['name']=$smd['name'];
                $finalmarks[$list_branch_id][$branch_orientation_id][$branch_class_id][$branch_class_section_id][$admission_no]['obtained_marks']+=round($smd['obtained_marks']*($totalexamgroupdeails[$smd['esid']]/100),2);
                if($smd['core_attendance_status_id']=="AB" && key_exists($smd['esid'],$totalexamgroupdeails))
                {
                        $finalmarks[$list_branch_id][$branch_orientation_id][$branch_class_id][$branch_class_section_id][$admission_no]['status']="AB";
                }		
            }
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("acd_grade_master");        
            $db->addWhere("acd_grade_method_details_id in ('".$grade_method_id."')");
            $db->buildSelect();       
            $grade_master_details=$db->getRows();            
            $grademaster_temp=array();
            if(count($grade_master_details)>0)
            {
                foreach($grade_master_details as $gmd)
                {
                        $type=$gmd['acd_grade_method_details_id'];
                        $percentage=$gmd['max']."_".$gmd['min'];
                        $gradepoints=$gmd['gradepoints'];
                        $grade=$gmd['grade'];
                        $remarks=$gmd['remarks'];
                        $grademaster_temp[$percentage]['grade']=$grade;
                        $grademaster_temp[$percentage]['remarks']=$remarks;
                        $grademaster_temp[$percentage]['gradepoints']=$gradepoints;
                }
            }
            foreach($finalmarks as $list_branch_id=>$branchdata)
            {
                foreach($branchdata as $branch_orientation_id=>$coursedata)
                {
                    foreach($coursedata as $branch_class_id=>$classdata)
                    {
                        foreach($classdata as $branch_class_section_id=>$sectiondata)
                        {
                            foreach($sectiondata as $admission_no=>$data)
                            {
                                    $percentage=round(($data['obtained_marks']/$data['max_marks'])*100,2);
                                    $gradedata=$this->getGradeData($grademaster_temp,$percentage);
                                    if($gradedata!="")
                                    {
                                        $grade=$gradedata['grade'];
                                        $gradepoints=$gradedata['gradepoints'];
                                        $remarks=$gradedata['remarks'];
                                    }
                                    else
                                    {
                                        $grade=NULL;
                                        $gradepoints=NULL;
                                        $remarks=NULL;
                                    }
                                    if(key_exists("status",$data))
                                    {
                                        $status="AB";
                                    }
                                    else
                                    {
                                        $status="PR";
                                    }
                                    $node =new Core_Model_NodeSave();
                                    $node->setNode("acd_final_marks");
                                    $node->setData("acd_overall_grade_id",$acd_overall_grade_id);
                                    $node->setData("acd_exam_group_id",$requestdata['acd_exam_group_id']);
                                    $node->setData("cur_list_academicyear_id",$list_academicyear_id);
                                    $node->setData("list_branch_id",$list_branch_id);
                                    $node->setData("cur_branch_orientation_id",$branch_orientation_id);
                                    $node->setData("cur_branch_class_id",$branch_class_id); 
                                    $node->setData("cur_branch_class_section_id",$data['section']);
                                    $node->setData("admission_no",$admission_no);
                                    $node->setData("name",$data['name']);
                                    $node->setData("total_marks",$data['max_marks']);
                                    $node->setData("total_optianed",$data['obtained_marks']);
                                    $node->setData("percentage",$percentage);
                                    $node->setData("cur_attendance_status_id",$status);
                                    $node->setData("gradepoints",$gradepoints);
                                    $node->setData("grade",$grade);
                                    $node->setData("remarks",$remarks);
                                    $node->save();
                            }
                        }
                    }
                }           
            }
        }
        return TRUE;
    }
    function getGradeData($gradeMaster,$percentage=null)
    {
	$gradeData=array();
	if($percentage!="" || $percentage=="0")
	{
	    if(count($gradeMaster)>0)
	    {
		foreach($gradeMaster as $max_min=>$data)
		{
		    $list=explode("_",$max_min);
		    $max=$list[0];
		    $min=$list[1];
		    if(round($percentage,2)>=round($min,2) && round($percentage,2)<=round($max,2))
		    {
			return $gradeData=$data;
		    }
		}
	    }
	}
	return $gradeData;
    }
    
}
