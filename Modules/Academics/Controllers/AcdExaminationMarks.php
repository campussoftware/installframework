<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdExaminationMarks
 *
 * @author ramesh
 */
class Modules_Academics_Controllers_AcdExaminationMarks extends Core_Controllers_NodeController
{
    //put your code here
    public function acdExaminationMarksBeforeDataUpdate($data)
    {       
        if($this->_currentAction=='add')
        {
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("acd_examination_schedule");
            $db->addField("acd_exam_name_id");
            $db->addWhere("id='".$data['acd_examination_schedule_id']."'");
            $acd_exam_name_id=$db->getValue();
            $data['acd_exam_name_id']=$acd_exam_name_id;       
            $this->_requestedData['acd_exam_name_id']=$acd_exam_name_id;
        }
        return $data;
    }
    public function acdExaminationMarksNodeDataValidateAfter($errorsArray)
    {
        if($this->_currentAction=='add')
        {
            $requestedData=$this->_requestedData;
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("student_history");
            $db->addField("count(student_history.id)");
            $db->addWhere("fee_list_feegroup_id='TAD'");
            $db->addWhere("cur_list_academicyear_id='".$requestedData['cur_list_academicyear_id']."'");
            $db->addWhere("list_branch_id='".$requestedData['list_branch_id']."'");
            $db->addWhere("cur_branch_orientation_id='".$requestedData['cur_branch_orientation_id']."'");
            $db->addWhere("cur_branch_class_id='".$requestedData['cur_branch_class_id']."'");
            $db->addWhere("cur_branch_class_section_id='".$requestedData['cur_branch_class_section_id']."'");
            $db->addWhere("student_status_id in('NW','EX')");
            $db->buildSelect();
            $existingCount=$db->getValue();
            if($existingCount==0)
            {
                $errorsArray['cur_branch_class_section_id']="No Student Are There in Section";
            }
        
        }
        return $errorsArray;
    }
    public function acdExaminationMarksAfterDataUpdate($data)
    {       
        
        if($this->_currentAction=='add')
        {
            $requestedData=$this->_requestedData;
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("acd_examination_schedule_details","esd"); 
            $db->addField("*");
            $db->addWhere("esd.acd_examination_schedule_id='".$requestedData['acd_examination_schedule_id']."'");
            $db->buildSelect();           
            $examDetails=$db->getRows();
            
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("student_history","sh");
            $db->addFieldArray(array("sd.name"=>"name"));
            $db->addField("sh.*");
            $db->addJoin("student_admission_id","student_admission","sd","sd.id=sh.student_admission_id");
            $db->addWhere("fee_list_feegroup_id='TAD'");
            $db->addWhere("cur_list_academicyear_id='".$requestedData['cur_list_academicyear_id']."'");
            $db->addWhere("list_branch_id='".$requestedData['list_branch_id']."'");
            $db->addWhere("cur_branch_orientation_id='".$requestedData['cur_branch_orientation_id']."'");
            $db->addWhere("cur_branch_class_id='".$requestedData['cur_branch_class_id']."'");
            $db->addWhere("cur_branch_class_section_id='".$requestedData['cur_branch_class_section_id']."'");
            $db->addWhere("student_status_id in('NW','EX')");
            $db->buildSelect();             
            $studentDetails=$db->getRows();
            if(Core::countArray($studentDetails)>0)
            {
                $db=new Core_DataBase_ProcessQuery();
                $db->setTable("cur_branch_orientation");
                $db->addField("cur_list_orientation_id");
                $db->addWhere("id='".$requestedData['cur_branch_orientation_id']."'");
                $cur_list_orientation_id=$db->getValue();
                
                $db=new Core_DataBase_ProcessQuery();
                $db->setTable("cur_branch_class");
                $db->addField("cur_list_class_id");
                $db->addWhere("id='".$requestedData['cur_branch_class_id']."'");
                $cur_list_class_id=$db->getValue();
                foreach ($studentDetails as $studentData)
                {
                    if(Core::countArray($examDetails)>0)
                    {
                        foreach ($examDetails as $examData) 
                        {                         
                            $node=new Core_Model_NodeSave();
                            $node->setNode("acd_studentmarks_details");
                            $node->setData("acd_examination_marks_id",$requestedData['id']);
                            $node->setData("cur_list_academicyear_id",$requestedData['cur_list_academicyear_id']);
                            $node->setData("list_branch_id",$requestedData['list_branch_id']);
                            $node->setData("cur_branch_orientation_id",$requestedData['cur_branch_orientation_id']);
                            $node->setData("cur_list_orientation_id",$cur_list_orientation_id);
                            $node->setData("cur_branch_class_id",$requestedData['cur_branch_class_id']);
                            $node->setData("cur_list_class_id",$cur_list_class_id);
                            $node->setData("cur_branch_class_section_id",$requestedData['cur_branch_class_section_id']);
                            $node->setData("admission_no",$studentData['admission_no']);
                            $node->setData("name",$studentData['name']);
                            $node->setData("cur_class_subject_id",$examData['cur_class_subject_id']);
                            $node->setData("cur_academic_subject_id",$examData['cur_academic_subject_id']);
                            $node->setData("max_marks",$examData['max_marks']);
                            $node->setData("cutoff_marks",$examData['cutoff_marks']);
                            $node->setData("min_marks",$examData['min_marks']);                    
                            $node->setData("core_attendance_status_id","PR");                        
                            $node->setData("acd_examination_schedule_id",$examData['acd_examination_schedule_id']);
                            $node->setData("acd_exam_name_id",$requestedData['acd_exam_name_id']);
                            $node->save();
                        }
                    }
                }
            }
            
        }
        return $data;
    }
    public function acdExaminationMarksBeforeFinal() 
    {
        $examinationMarksId=$this->_currentSelector;
        
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_marks");        
        $db->addWhere("id='".$examinationMarksId."'");
        $examinationMarksDetails=$db->getRow();
        $examination_id=$examinationMarksDetails['acd_examination_schedule_id'];
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("acd_studentmarks_details");        
        $db->addWhere("acd_examination_marks_id='".$examinationMarksId."'");
        $student_marks_details=$db->getRows("id"); 
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_schedule_details","esd");
        $db->addFieldArray(array("esd.cur_class_subject_id"=>"cur_class_subject_id"));
        $db->addFieldArray(array("esd.subject_grade_id"=>"subject_grade_id"));
        $db->addWhere("esd.acd_examination_schedule_id='".$examination_id."'");
        $db->buildSelect();       
        $examination_details=$db->getRows("cur_class_subject_id","subject_grade_id");
       
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_grade_master");
        $db->addWhere("acd_grade_method_details_id in ('".Core::covertArrayToString(($examination_details),"','")."')");
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
                    $grademaster_temp[$type][$percentage]['grade']=$grade;
                    $grademaster_temp[$type][$percentage]['remarks']=$remarks;
                    $grademaster_temp[$type][$percentage]['gradepoints']=$gradepoints;
                }
        }    
        $quries=array();
        $q=1;
        if(count($student_marks_details)>0)
        {
                foreach($student_marks_details as $smd)
                {
                  
                        $status=$smd['core_attendance_status_id'];
                        if($status=="PR")
                        {
                                if($smd['obtained_marks']!="" || $smd['obtained_marks']=="0")
                                {
                                    $percentage=($smd['obtained_marks']/$smd['max_marks']*100);
                                    $grademaster=$grademaster_temp[$examination_details[$smd['cur_class_subject_id']]];                                    
                                    $gradedata=$this->getGradeData($grademaster,$percentage);
                                    $node=new Core_Model_NodeSave();
                                    $node->setNode("acd_studentmarks_details");
                                    $node->setData("id",$smd['id']);
                                    $node->setData("percentage", round($percentage,2));
                                    $node->setData("grade", $gradedata['grade']);
                                    $node->setData("gradepoints", $gradedata['gradepoints']);
                                    $node->setData("remarks", $gradedata['remarks']);
                                    $node->save();
                                } 
                                else
                                {
                                    $node=new Core_Model_NodeSave();
                                    $node->setNode("acd_studentmarks_details");
                                    $node->setData("id",$smd['id']);
                                    $node->setData("percentage", "");
                                    $node->setData("grade","");
                                    $node->setData("gradepoints","");
                                    $node->setData("remarks","");
                                    $node->save();
                                }
                        }
                        else
                        {
                                    $node=new Core_Model_NodeSave();
                                    $node->setNode("acd_studentmarks_details");
                                    $node->setData("id",$smd['id']);
                                    $node->setData("percentage", "");
                                    $node->setData("grade","");
                                    $node->setData("gradepoints","");
                                    $node->setData("remarks","");
                                    $node->save();                              
                        }
                        
                }
        }
        return array();
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
