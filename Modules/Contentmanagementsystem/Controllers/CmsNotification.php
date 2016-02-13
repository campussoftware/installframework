<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CmsNotification
 *
 * @author ramesh
 */
class Modules_Contentmanagementsystem_Controllers_CmsNotification extends Core_Controllers_NodeController
{
    //put your code here
    public function cmsNotificationBeforeDataUpdate($data)
    {        
        if($this->_currentAction=="add")
        {
            $helper= CoreClass::getHelper();
            $notification_code=$helper->getSequenceCode('NOTIFICATION');
            $data['notification_code']=$notification_code;
            $this->_requestedData['notification_code']=$notification_code;
        }
        return $data;
    }
    public function cmsNotificationAfterDataUpdate($data)
    {        
        if($this->_currentAction=="add")
        {
            $helper= CoreClass::getHelper();
            $helper->updateSequenceCode('NOTIFICATION');           
        }
        return $data;
    }
    public function getDetailsAction()
    {
        
        $requestedData=$this->_requestedData;
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cms_notification","nf");    
        $db->addFieldArray(array("nf.notification_code"=>"notification_code"));
        $db->addFieldArray(array("nf.title"=>"title"));
        $db->addFieldArray(array("nf.start_date"=>"start_date"));
        $db->addFieldArray(array("nf.end_date"=>"end_date"));
        $db->addFieldArray(array("nf.description"=>"description"));
       // $db->addWhere("nf.student_admission_id='".$requestedData['student_admission_id']."'");
        $db->addWhere("nf.cur_list_academicyear_id='".$requestedData['cur_list_academicyear_id']."'");
        $db->addWhere("nf.cms_notification_type_id='".$requestedData['type']."'");
        $db->addOrderBy("nf.start_date DESC");
        $db->buildSelect();
        $db->sql;
        $result=$db->getRows();
        echo Core::convertArrayToJson(array("status"=>"success","notificationData"=>$result));
    }
    public function saveNotificationCommentAction()
    {
        $requestedData=$this->_requestedData;
        $notificationCode=$requestedData['notificationcode'];
        $studentId=$requestedData['student_admission_id'];
        $userMessage=$requestedData['message'];
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cms_notification","nf");    
        $db->addWhere("nf.notification_code='".$notificationCode."'");
        $notificationData=$db->getRow();
        if(Core::countArray($notificationData)>0)
        {
            $status="success";
            $node=new Core_Model_NodeSave();
            $node->setNode("cms_notification_comments");
            $node->setData("cms_notification_id",$notificationData['id']);
            $node->setData("comment_source","STUDENT");
            $node->setData("commentby",$studentId);
            $node->setData("description",$userMessage);
            $node->save();
            $message=" Succesfully Created ";
        }
        else
        {
            $status="error";
            $message=" Notification Not Exists ";
        }
        echo Core::convertArrayToJson(array("status"=>$status,"message"=>$message));
        
    }
    public function getNotificationCommentsAction()
    {
        $requestedData=$this->_requestedData;
        $notificationCode=trim($requestedData['notificationcode']);
        $studentId=$requestedData['student_admission_id'];
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cms_notification","nf");    
        $db->addField("id");
        $db->addWhere("nf.notification_code='".$notificationCode."'");
        $db->buildSelect();
        $notificationData=$db->getRow();        
        if(Core::countArray($notificationData)>0)
        {
            $status="success";            
            $db =new Core_DataBase_ProcessQuery();
            $db->setTable("cms_notification_comments","nfc");
            $db->addFieldArray(array("nfc.comment_source"=>"sourcetype"));
            $db->addFieldArray(array("nfc.description"=>"description"));
            $db->addWhere("nfc.cms_notification_id='".$notificationData['id']."'");
            $db->addWhere("((nfc.comment_source='STUDENT' && nfc.commentby='".$studentId."') ||(nfc.comment_source!='STUDENT'))");
            $db->addOrderBy("nfc.id DESC");
            $db->buildSelect();            
            $result=$db->getRows();
            $status="success";
        }
        else
        {
            $status="error";
            $message=" Notification Not Exists ";
        }
        echo Core::convertArrayToJson(array("status"=>$status,"message"=>$message,"comments"=>$result));
        
    }
}
