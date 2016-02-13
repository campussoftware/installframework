<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of TransactionLogs
 *
 * @author ramesh
 */
class Modules_Financemanagement_Controllers_TransactionLogs extends Core_Controllers_NodeController 
{
    //put your code here
    public function getDetailsAction() 
    {
        $requestedData=$this->_requestedData;
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("transaction_logs","tl");    
        $db->addFieldArray(array("tl.transactionno"=>"transactionno"));
        $db->addFieldArray(array("tl.feereceiptno"=>"feereceiptno"));
        $db->addFieldArray(array("tl.transactiondate"=>"transactiondate"));
        $db->addFieldArray(array("tl.amount"=>"amount"));
        $db->addWhere("tl.student_admission_id='".$requestedData['student_admission_id']."'");
        $db->addWhere("tl.cur_list_academicyear_id='".$requestedData['cur_list_academicyear_id']."'");
        $db->addWhere("tl.fee_list_transactiontype_id='PM'");
        $db->addOrderBy("tl.id DESC");
        $db->buildSelect();        
        $result=$db->getRows("transactionno");
        
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("transaction_logs_details","tld");    
        $db->addFieldArray(array("tl.transactionno"=>"transactionno"));
        $db->addFieldArray(array("ft.name"=>"feetype"));
        $db->addFieldArray(array("fq.name"=>"frequency"));
        $db->addFieldArray(array("tld.amount"=>"amount"));       
        $db->addWhere("tl.transactionno in('".Core::covertArrayToString(Core::getKeysFromArray($result),"','")."')");
        $db->addJoin("transaction_logs_id", "transaction_logs", "tl", "tl.id=tld.transaction_logs_id");
        $db->addJoin("fee_list_feetype_id", "fee_list_feetype", "ft", "tld.fee_list_feetype_id=ft.short_code");
        $db->addJoin("fee_list_frequency_id", "fee_list_frequency", "fq", "tld.fee_list_frequency_id=fq.short_code");
        $db->addOrderBy("tld.id ASC");
        $db->buildSelect();     
        
        $paymentDataItemWise=array();
        $paymentDataItemWiseDetails=$db->getRows();
        if(count($paymentDataItemWiseDetails)>0)
        {
           foreach($paymentDataItemWiseDetails as $pd)
           {
               $paymentDataItemWise[$pd['transactionno']][]=$pd;
           }
        }
        
        echo Core::convertArrayToJson(array("paymentData"=>$result,"paymentDataItemWise"=>$paymentDataItemWise,"totalData"=>Core::sumOfArarrayValues($result, array("amount"))));
    }
}
