<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdStudentmarksDetails
 *
 * @author ramesh
 */
class Modules_Academics_Controllers_AcdStudentmarksDetails extends Core_Controllers_NodeController 
{
    //put your code here
    public function acdStudentmarksDetailsBeforeMRADataValidate() 
    {
        $errors=array();
        $requestedData=$this->_requestedData;
        $examinationMarksId=$requestedData['acd_studentmarks_details_parentidvalue'];
        $Data=$requestedData['acd_studentmarks_details_save'];
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_marks");
        $db->addField("acd_examination_schedule_id");
        $db->addWhere("id='".$examinationMarksId."'");
        $acd_examination_schedule_id=$db->getValue();
        
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_schedule_details");        
        $db->addWhere("acd_examination_schedule_id='".$acd_examination_schedule_id."'");
        $examinationDetails=$db->getRows("cur_class_subject_id");
        
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("acd_studentmarks_details");        
        $db->addWhere("acd_examination_marks_id='".$examinationMarksId."'");
        $studentMarks=$db->getRows("id");        
        
        if(count($Data)>0)
        {
            foreach ($Data as $key => $data) 
            {
                if($data['core_attendance_status_id']=='PR')
                {
                    $class_subject_id=$studentMarks[$key]['cur_class_subject_id'];
                    $subjectDetails=$examinationDetails[$class_subject_id];
                    if($subjectDetails['cutoff_marks']<$data['obtained_marks'] || $data['obtained_marks']<$subjectDetails['min_marks'])
                    {
                        $errors['error_'.$this->_nodeName.'_'.$key.'_obtained_marks']=" Marks Must in Between ".$subjectDetails['min_marks'].",".$subjectDetails['cutoff_marks']." only ";
                    }
                }
                else
                {
                    if($data['obtained_marks']>0 || $data['obtained_marks']<0)
                    {
                        $errors['error_'.$this->_nodeName.'_'.$key.'_obtained_marks']=" Marks Must be zero ";
                    }
                }
            }
            
        }
        return $errors;
    }
}
