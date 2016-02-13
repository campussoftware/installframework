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
class Modules_Academics_Models_AcdOverallGrade extends Core_Model_Node 
{
    //put your code here
    public function acdExamGroupIdAddSingleFilter() 
    {
        if($this->_currentAction=="add")
        {
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("cur_branch_class");
            $db->addField("cur_list_class_id");
            $db->addWhere("id='".$this->_requestedData['cur_branch_class_id']."'");
            $classid=$db->getValue();
            $filter="acd_exam_group.cur_list_class_id='".$classid."' and acd_exam_group.is_final='1'";
            return $filter;
        }        
    }
}
