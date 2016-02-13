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
class Modules_Academics_Models_AcdExaminationScheduleDetails extends Core_Model_Node
{
    //put your code here
    public function curClassSubjectIdAddDescriptorFieldsFilter()
    {
        return array();
    }
    public function curClassSubjectIdAddSingleFilter()
    {
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_schedule");
        $db->addField("cur_list_class_id");
        $db->addWhere("id='".$this->_requestedData['acd_examination_schedule_id']."'");
        $classid=$db->getValue();        
        return "cur_list_class_id='".$classid."'";
    }
}
