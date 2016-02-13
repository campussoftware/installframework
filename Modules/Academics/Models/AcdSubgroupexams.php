<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdSubgroupexams
 *
 * @author ramesh
 */
class Modules_Academics_Models_AcdSubgroupexams extends Core_Model_Node
{
    //put your code here
    public function acdExaminationScheduleIdAddSingleFilter() 
    {
        if($this->_currentAction=='add')
        {
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("acd_exam_group");
            $db->addField("cur_list_academicyear_id");
            $db->addField("cur_list_class_id");
            $db->addWhere("id='".$this->_requestedData['acd_exam_group_id']."'");
            $examgroup=$db->getRow();
            $cur_list_class_id=$examgroup[cur_list_class_id];
            $cur_list_academicyear_id=$examgroup[cur_list_academicyear_id];
            return "acd_examination_schedule.cur_list_class_id='".$cur_list_class_id."' and  acd_examination_schedule.cur_list_academicyear_id='".$cur_list_academicyear_id."' and acd_examination_schedule.is_final='1'";
        }
        else
        {
            
        }
    }
}
