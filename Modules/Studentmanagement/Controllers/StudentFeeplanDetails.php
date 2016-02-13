<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StudentFeeplanDetails
 *
 * @author ramesh
 */
class Modules_Studentmanagement_Controllers_StudentFeeplanDetails extends Core_Controllers_NodeController
{
    public $_feecollection;
    public $_studentCollection;
    //put your code here
    public function getFeeStructureAction()
    {
        
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("student_history","sh");
        $db->addFieldArray(array("concat(sd.first_name,' ',sd.last_name)"=>"studentname"));
        $db->addFieldArray(array("sd.admission_no"=>"admission_no"));
        $db->addFieldArray(array("acyd.academicyear"=>"academicyear"));
        $db->addFieldArray(array("lb.name"=>"branch"));
        $db->addFieldArray(array("lo.name"=>"course"));
        $db->addFieldArray(array("lc.name"=>"classname"));
        $db->addFieldArray(array("ls.name"=>"section"));
        $db->addFieldArray(array("fp.name"=>"plan"));
        $db->addWhere("sh.cur_list_academicyear_id='".$this->_requestedData['cur_list_academicyear_id']."'");
        $db->addWhere("(sh.student_admission_id='".$this->_requestedData['student_admission_id']."' || sh.admission_no='".$this->_requestedData['admision_no']."')");
        $db->addJoin("student_admission_id","student_admission","sd","sh.student_admission_id=sd.id and fee_list_feegroup_id='TAD'");
        $db->addJoin("cur_list_academicyear_id","cur_list_academicyear","acyd","sh.cur_list_academicyear_id=acyd.short_code");
        $db->addJoin("list_branch_id","list_branch","lb","sh.list_branch_id=lb.id");
        $db->addJoin("cur_branch_orientation_id","cur_branch_orientation","bo","sh.cur_branch_orientation_id=bo.id");
        $db->addJoin("cur_list_orientation_id","cur_list_orientation","lo","bo.cur_list_orientation_id=lo.id");        
        $db->addJoin("cur_branch_class_id","cur_branch_class","bc","sh.cur_branch_class_id=bc.id");
        $db->addJoin("cur_list_class_id","cur_list_class","lc","bc.cur_list_class_id=lc.id"); 
        $db->addJoin("cur_branch_class_section_id","cur_branch_class_section","ls","sh.cur_branch_class_section_id=ls.id");
        $db->addJoin("fee_list_feeplan_id","fee_list_feeplan","fp","sh.fee_list_feeplan_id=fp.id");
        $db->buildSelect();        
        $this->_studentCollection=$db->getRow();
        
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("student_feeplan_details","sfd");        
        $db->addFieldArray(array("ftp.name"=>"feetype"));
        $db->addFieldArray(array("fqy.name"=>"frequency"));
        $db->addFieldArray(array("sfd.amount"=>"amount"));
        $db->addFieldArray(array("sfd.amount"=>"amount"));
        $db->addFieldArray(array("sfd.concession"=>"concession"));
        $db->addFieldArray(array("(sfd.amount-sfd.concession)"=>"finalamount"));
        $db->addFieldArray(array("(sfd.paid_amount)"=>"paidamount"));
        $db->addFieldArray(array("(sfd.balance)"=>"balance"));
        $db->addFieldArray(array("(sfd.due_date)"=>"due_date"));        
        $db->addWhere("sfd.cur_list_academicyear_id='".$this->_requestedData['cur_list_academicyear_id']."'");
        $db->addWhere("(sfd.student_admission_id='".$this->_requestedData['student_admission_id']."' || sfd.admission_no='".$this->_requestedData['admision_no']."')");
        $db->addJoin("fee_list_feetype_id","fee_list_feetype","ftp","sfd.fee_list_feetype_id=ftp.short_code");
        $db->addJoin("fee_list_frequency_id","fee_list_frequency","fqy","sfd.fee_list_frequency_id=fqy.short_code");
        $db->buildSelect();            
        $this->_feecollection=$db->getRows();
        if($this->_requestedData['dataType']=='json')
        {
            echo Core::convertArrayToJson(array("studnetdata"=>$this->_studentCollection,"feeData"=>$this->_feecollection,"totalData"=>Core::sumOfArarrayValues($this->_feecollection, array("amount","concession","finalamount","paidamount","balance"))));
        }
        else
        {
            if($this->_requestedData['node']=='financeconcession')
            {
                $this->loadLayout("feestructureforconcession.phtml");
            }
            else
            {
                $this->loadLayout("feestructureforfeereceipt.phtml");
            }
        }
    }
    public function getPaymentTypes()
    {
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("core_payment_type","pt");        
        $db->addFieldArray(array("pt.name"=>"pds"));
        $db->addFieldArray(array("pt.short_code"=>"pid"));
        $db->buildSelect();
        $result=$db->getRows();
        return $result;
    }  
    public function getConcessionFypes()
    {
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("fee_concession_type","ctp");        
        $db->addFieldArray(array("ctp.name"=>"pds"));
        $db->addFieldArray(array("ctp.id"=>"pid"));
        $db->buildSelect();
        $result=$db->getRows();
        return $result;
    }  
    
}
