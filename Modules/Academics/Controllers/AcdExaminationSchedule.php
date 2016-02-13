<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AcdExaminationSchedule
 *
 * @author ramesh
 */
class Modules_Academics_Controllers_AcdExaminationSchedule extends Core_Controllers_NodeController
{
    //put your code here
    public function getlistExaminationsAction() 
    {
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_schedule","es");
        $db->addFieldArray(array("es.id"=>"esid"));
        $db->addFieldArray(array("es.name"=>"name"));
        $db->addFieldArray(array("es.start_date"=>"start_date"));
        $db->addFieldArray(array("es.end_date"=>"end_date"));
        $db->addWhere("es.is_final='1'");
        $result=$db->getRows();
        echo Core::convertArrayToJson(array("status"=>"success","data"=>$result));
    }
    public function getExaminationDetailsAction() 
    {
        $requestedData=$this->_requestedData;
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("acd_examination_schedule_details","esd");
        $db->addFieldArray(array("esd.id"=>"esid"));
        $db->addFieldArray(array("asd.name"=>"subject"));
        $db->addFieldArray(array("esd.exam_date"=>"exam_date"));
        $db->addFieldArray(array("esd.start_time"=>"start_time"));
        $db->addFieldArray(array("esd.end_time"=>"end_time"));
        $db->addFieldArray(array("esd.max_marks"=>"max_marks"));
        $db->addFieldArray(array("esd.cutoff_marks"=>"cutoff_marks"));
        $db->addFieldArray(array("esd.min_marks"=>"min_marks"));
        $db->addJoin("cur_academic_subject_id","cur_academic_subject","asd","esd.cur_academic_subject_id=asd.id");
        $db->addWhere("esd.acd_examination_schedule_id='".$requestedData['esid']."'");
        $result=$db->getRows();
        echo Core::convertArrayToJson(array("status"=>"success","data"=>$result));
    }
}
