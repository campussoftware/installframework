<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdProgressReport
 *
 * @author ramesh
 */
class Modules_Academics_Controllers_Acdprogressreport extends Core_Controllers_NodeController
{
    //put your code here
    public function getProgressReportAction()
    {
        $wb=new Core_WebsiteSettings();
        $requestdata=$this->_requestedData;
        
        $list_academicyear_id=$requestdata['cur_list_academicyear_id'];
        $branch_class_id=$requestdata['cur_branch_class_id'];
        $branch_class_section_id=$requestdata['cur_branch_class_section_id'];
        $branch_orientation_id=$requestdata['cur_branch_orientation_id'];
        $admission_no=$requestdata['admision_no'];
        $acd_exam_group_id=$requestdata['acd_exam_group_id'];
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("core_organization","org");
        $db->addFieldArray(array("org.name"=>"orgname"));
        $db->addFieldArray(array("org.logo"=>"logo"));
        $db->addFieldArray(array("lb.name"=>"branch"));
        $db->addJoin("list_branch_id","list_branch", "lb", "lb.core_organization_id=org.id");      
        $db->addWhere("lb.id='1'");
        $orgnization_details=$db->getRow();
        $resulttemplte=null;	
	$orgimage= $wb->websiteAdminUrl.$wb->documentRootUpload."/logos/small/".$orgnization_details['logo'];
	$organitionlogo='<img src="'.$orgimage.'">';	
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_exam_group");
        $db->addWhere("id='1'");
	$examgroupdeails=$db->getRow();
	$examgroupname=$examgroupdeails['name'];
        $templatePath=$wb->documentRoot."template.tpl";
	$template=Core::getFileContent($templatePath);
	$template=str_replace("{ORGANIZATIONLOGO}",$organitionlogo,$template);
	$template=str_replace("{ORGANIZATIONNAME}",strtoupper($orgnization_details['orgname']),$template);
	$template=str_replace("{BRANCH}",strtoupper($orgnization_details['branch']),$template);
	
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_orientation","bo");        
        $db->addFieldArray(array("lo.name"=>"name"));
        $db->addJoin("cur_list_orientation","cur_list_orientation", "lo", "bo.cur_list_orientation_id=lo.id");    
        $db->addWhere("bo.id='".$branch_orientation_id."'");
	$course=$db->getValue();
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_class_subject","csd");
        $db->addFieldArray(array("csd.id"=>"id"));
        $db->addFieldArray(array("acd.name"=>"name"));
        $db->addJoin("cur_academic_subject_id","cur_academic_subject", "acd", "csd.cur_academic_subject_id=acd.id");      
	$subjectname_array=$db->getRows("id","name");
        
	$template=str_replace("{COURSE}",strtoupper($course),$template);
        
         $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_class","bc");        
        $db->addFieldArray(array("lc.name"=>"name"));
        $db->addJoin("cur_list_class","cur_list_class", "lc", "bc.cur_list_class_id=lc.id");    
        $db->addWhere("bc.id='".$branch_class_id."'");
	$classname=$db->getValue();
        
        $template=str_replace("{CLASS}",strtoupper($classname),$template);
	$fmwhere=null;
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_final_marks","fm");
        $db->addField("fm.*");
        $db->addWhere("og.cur_branch_class_id='".$branch_class_id."'");
        $db->addWhere("og.acd_exam_group_id='".$acd_exam_group_id."'");
        if($branch_class_section_id!="")
	{
	    $db->addWhere("fm.cur_branch_class_section_id='".$branch_class_section_id."'");
	}
	if($admission_no!="")
	{
	    $db->addWhere(" fm.admission_no='".$admission_no."'");
	}
        $db->addJoin("acd_overall_grade_id","acd_overall_grade","og","fm.acd_overall_grade_id=og.id");
	$finalmarks=$db->getRows("admission_no");
	
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_subgroupexams");
        $db->addFieldArray(array("acd_examination_schedule_id"=>"examination_shedule_id"));
        $db->addFieldArray(array("percentage"=>"percentage"));
        $db->addWhere("acd_exam_group_id='".$acd_exam_group_id."'");
	$subexamgroupdetails=$db->getRows("examination_shedule_id","percentage");
	if(!is_array($subexamgroupdetails))
	{
	    $subexamgroupdetails=array();
	}
	$totalexamgroupdeails=$subexamgroupdetails;
	
	
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_schedule");
        $db->addFieldArray(array("id"=>"id"));
        $db->addFieldArray(array("name"=>"name"));
        $examnamearray=$db->getRows("id","name");
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_class_section","ls");
        $db->addFieldArray(array("ls.id"=>"id"));
        $db->addFieldArray(array("ls.name"=>"name"));
        $db->addJoin("branch_class_id","cur_branch_class","bc","bc.id=ls.cur_branch_class_id");
        $db->addWhere("bc.id='".$branch_class_id."'");
        $db->buildSelect();        
        $section_array=$db->getRows("id","name");
	
	if(count($finalmarks)>0)
	{
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("acd_studentmarks_details","smd");
            $db->addFieldArray(array("es.id"=>"esid"));
            $db->addFieldArray(array("emd.list_branch_id"=>"list_branch_id"));
            $db->addFieldArray(array("emd.cur_branch_orientation_id"=>"branch_orientation_id"));
            $db->addFieldArray(array("emd.cur_branch_class_section_id"=>"branch_class_section_id"));
            $db->addFieldArray(array("smd.cur_class_subject_id"=>"class_subject_id"));
            $db->addFieldArray(array("smd.admission_no"=>"admission_no"));
            $db->addFieldArray(array("smd.max_marks"=>"max_marks"));
            $db->addFieldArray(array("smd.obtained_marks"=>"obtained_marks"));
            $db->addFieldArray(array("smd.percentage"=>"percentage"));
            $db->addFieldArray(array("smd.core_attendance_status_id"=>"attendance_status_id"));
            $db->addFieldArray(array("smd.gradepoints"=>"gradepoints"));
            $db->addFieldArray(array("smd.grade"=>"grade"));
            $db->addFieldArray(array("smd.remarks"=>"remarks"));
            $db->addJoin("acd_examination_marks_id","acd_examination_marks","emd","smd.acd_examination_marks_id=emd.id");
            $db->addJoin("acd_examination_schedule_id","acd_examination_schedule","es","smd.acd_examination_schedule_id=emd.id");
            $db->addJoin("acd_examination_schedule_details","acd_examination_schedule_details","esd","esd.acd_examination_schedule_id=es.id and smd.cur_class_subject_id=esd.cur_class_subject_id");
	    $db->addOrderBy("smd.admission_no ASC,esd.id ASC");
            $db->addWhere("emd.cur_list_academicyear_id='".$list_academicyear_id."'");
            $db->addWhere("emd.cur_branch_class_id='".$branch_class_id."'");
            $db->addWhere("emd.acd_examination_schedule_id in('".implode("','",array_keys($totalexamgroupdeails))."')");	   
            if($branch_class_section_id!="")
            {
                $db->addWhere("emd.cur_branch_class_section_id='".$branch_class_section_id."'");                
            }
            if($admission_no!="")
            {
                $db->addWhere("smd.admission_no='".$admission_no."'");          
                
            }  
            $db->buildSelect();
           
	    $student_marks_details=$db->getRows();
	    $studentmarksdetails=array();
	    if(count($student_marks_details)>0)
	    {
		foreach($student_marks_details as $smd)
		{
		    $studentmarksdetails[$smd['admission_no']][$smd['class_subject_id']][$smd['esid']]['max_marks']=$smd['max_marks'];
		    $studentmarksdetails[$smd['admission_no']][$smd['class_subject_id']][$smd['esid']]['obtained_marks']=$smd['obtained_marks'];
		    $studentmarksdetails[$smd['admission_no']][$smd['class_subject_id']][$smd['esid']]['attendance_status_id']=$smd['attendance_status_id'];
		    $studentmarksdetails[$smd['admission_no']][$smd['class_subject_id']][$smd['esid']]['grade']=$smd['grade'];
		    $studentmarksdetails[$smd['admission_no']][$smd['class_subject_id']][$smd['esid']]['gradepoints']=$smd['gradepoints'];
		    $studentmarksdetails[$smd['admission_no']][$smd['class_subject_id']][$smd['esid']]['remarks']=$smd['remarks'];
		}
	    }
	    
	    $k=1;
	    foreach($finalmarks as $admission_no=>$studentdata)
	    {
		$temp=str_replace("{ADMISSIONNO}",$admission_no,$template);
		$temp=str_replace("{NAME}",$studentdata['name'],$temp);
		$temp=str_replace("{EXAMNAME}",$examgroupname,$temp);
		$temp=str_replace("{SECTION}",$section_array[$studentdata['cur_branch_class_section_id']],$temp);
		
		$list=explode('<!--<rhead>-->',$temp);
		$temphead=$list['0'];
		$list1=explode('<!--</rhead>-->',$list['1']);
		$examheader=$list1['0'];
		$examheaderlist=explode('<!--<exnamelist>-->',$examheader);
		$examheader=$examheaderlist['0'];
		$examfooter=explode('<!--</exnamelist>-->',$examheaderlist['1']);
		//exnamelist
		$exnametemp=$examfooter['0'];
		if(count($subexamgroupdetails)>0)
		{
		    foreach($subexamgroupdetails as $esid=>$per)
		    {
			$examheader.=str_replace("{ENAME}",$examnamearray[$esid],$exnametemp);
		    }
		}
		if(count($mainexamgroupdeails)>0)
		{
		    foreach($mainexamgroupdeails as $esid=>$per)
		    {
			$examheader.=str_replace("{ENAME}",$examnamearray[$esid],$exnametemp);
		    }
		}
		$examheader.=$examfooter['1'];
		$temp=$list1['1'];
		$list=explode('<!--<data>-->',$temp);
		$temp=$list['0'];
		$list1=explode('<!--</data>-->',$list['1']);
		$temp1=$list1['0'];
		$examinationmarksdetails_temp=$studentmarksdetails[$admission_no];
		foreach($examinationmarksdetails_temp as $subject=>$studentmarksdetails_temp)
		{
		    $subjectname=$subjectname_array[$subject];
		    $maxmarks="";
		    $opmarks="";
		    $examdatalist=explode('<!--<exrs>-->',$temp1);
		    $examdatahead=$examdatalist['0'];
		    $examdatalist=explode('<!--</exrs>-->',$examdatalist['1']);
		    $exdatatemp=$examdatalist['0'];
		    $subjecttemp="";
		    foreach($studentmarksdetails_temp as $esid=>$subjectdata)
		    {
			$subjecttemp.=str_replace(array("{OBTAINEDMARKS}","{MM}","{GRADE}"),array($subjectdata['obtained_marks'],$subjectdata['max_marks'],$subjectdata['grade']),$exdatatemp);
			$maxmarks+=$subjectdata['max_marks'];
			if($subjectdata['obtained_marks']!="" || $subjectdata['obtained_marks']=="0")
			{
			    $opmarks+=$subjectdata['obtained_marks'];
			}
			
		    }
		    $temp.=str_replace(array('{SUBJECTNAME}','{MM}')
					   ,array($subjectname,$maxmarks),$examdatahead);
		    $temp.=$subjecttemp;
		    $temp.=str_replace(array('{MAXMARKS}','{OBTAINEDMARKS}','{STOTAL}','{GRADE}','{GRADEPOINTS}','{REMARKS}')
					   ,array($maxmarks,$opmarks,$opmarks,$subjectdata['grade'],$subjectdata['gradepoints'],$subjectdata['remarks']),$examdatalist['1']);
		}		
		$i=1;
		$temp.=$list1['1'];
		$studenttemplate=$temphead.$examheader.$temp;
		$studenttemplate=str_replace(array('{GRANDTOTAL}','{FINALGRADE}','{GPA}')
				  ,array($studentdata['total_optianed'],$studentdata['grade'],$studentdata['gradepoints']),$studenttemplate);
		$studenttemplate=str_replace(array('{OVERALLREMARKS}'),array($studentdata['remarks']),$studenttemplate);
		$resulttemplte.=$studenttemplate;
		if(count($finalmarks)!=$k)
		{
		    $resulttemplte.='<div style="page-break-before: always;">&nbsp</div>';
		}
		$k++;
		
	    }
	}
        echo $resulttemplte;
    }
}
