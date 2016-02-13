<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdExaminationScheduleDetails
 *
 * @author ramesh
 */
class Modules_Academics_Controllers_AcdExaminationScheduleDetails extends Core_Controllers_NodeController 
{
    //put your code here
    function acdExaminationScheduleDetailsBeforeDataUpdate($data)
    {
        $requestedData=$this->_requestedData;
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_class_subject");
        $db->addField("cur_academic_subject_id");
        $db->addWhere("id='".$this->_requestedData['cur_class_subject_id']."'");
        $cur_academic_subject_id=$db->getValue();  
        $data["cur_academic_subject_id"]=$cur_academic_subject_id;
        $this->_requestedData['cur_academic_subject_id']=$cur_academic_subject_id;
        return $data;        
    }
}
