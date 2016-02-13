<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StudentAdmission
 *
 * @author ramesh
 */
class Modules_Studentmanagement_Controllers_StudentAdmission extends Core_Controllers_NodeController
{
    //put your code here
    public function studentAdmissionBeforeDataUpdate($data)
    {        
        $data['name']=$data['first_name']." ".$data['last_name'];            
        if($this->_currentAction=='add')
        {
            $helper= CoreClass::getHelper();
            $admissionNo=$helper->getSequenceCode('STUDENT');
            $data['admission_no']=$admissionNo;
            $this->_requestedData['admission_no']=$admissionNo;
        }
        return $data;
    }
    public function studentAdmissionAfterDataUpdate()
    {        
        
        if($this->_currentAction=='add')
        {
            $helper= CoreClass::getHelper();
            $admissionNo=$helper->updateSequenceCode('STUDENT');  
             $db=new Core_DataBase_ProcessQuery();
            $db->setTable("cur_branch_class");
            $db->addField("cur_list_class_id");
            $db->addWhere("id='".$this->_requestedData['cur_branch_class_id']."'");
            $db->buildSelect();
            $cur_list_class_id=$db->getValue();       

            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("fee_list_feeplan","fp");
            $db->addJoin("fee_list_feeplan_id","fee_list_feeplan_details","fpd","fp.id=fpd.fee_list_feeplan_id");
            $db->addWhere("fp.cur_list_academicyear_id='".$this->_requestedData['cur_list_academicyear_id']."'");
            $db->addWhere("fp.student_quota_id='".$this->_requestedData['student_quota_id']."'");
            $db->addWhere("fp.list_branch_id='".$this->_requestedData['list_branch_id']."'");
            $db->addWhere("fp.cur_branch_orientation_id='".$this->_requestedData['cur_branch_orientation_id']."'");
            $db->addWhere("fp.cur_branch_class_id='".$this->_requestedData['cur_branch_class_id']."'");
            $db->addWhere("fp.fee_list_feegroup_id='TAD'");
            $db->buildSelect();

            $FeePlanDetails=$db->getRows();

            if(count($FeePlanDetails)>0)
            {
                foreach ($FeePlanDetails as $feeItem)
                {
                    try
                    {
                        $nodeSave=new Core_Model_NodeSave();
                        $nodeSave->setNode("student_feeplan_details");
                        $nodeSave->setData("student_admission_id",$this->_requestedData['id']);
                        $nodeSave->setData("admission_no",$this->_requestedData['admission_no']);
                        $nodeSave->setData("fee_list_feegroup_id",'TAD');
                        $nodeSave->setData("student_quota_id",$this->_requestedData['student_quota_id']);
                        $nodeSave->setData("cur_list_academicyear_id",$this->_requestedData['cur_list_academicyear_id']);
                        $nodeSave->setData("list_branch_id",$this->_requestedData['list_branch_id']);
                        $nodeSave->setData("cur_branch_orientation_id",$this->_requestedData['cur_branch_orientation_id']);
                        $nodeSave->setData("cur_branch_class_id",$this->_requestedData['cur_branch_class_id']);
                        $nodeSave->setData("fee_list_feeplan_id",$feeItem['fee_list_feeplan_id']);
                        $nodeSave->setData("fee_list_feetype_id",$feeItem['fee_list_feetype_id']);
                        $nodeSave->setData("fee_list_frequency_id",$feeItem['fee_list_frequency_id']);
                        $nodeSave->setData("amount",$feeItem['amount']);
                        $nodeSave->setData("concession",0);
                        $nodeSave->setData("paid_amount",0);
                        $nodeSave->setData("balance",$feeItem['amount']);
                        $nodeSave->setData("tax_paid",0);
                        $nodeSave->setData("tax_amount",0);
                        $nodeSave->setData("active_status","1");                    
                        $nodeSave->save();
                        $fee_list_feeplan_id=$feeItem['fee_list_feeplan_id'];
                    }
                    catch (Exception $ex)
                    {
                         Core::Log(__METHOD__.$ex->getMessage(),"studentexception.log");
                    }

                }
            }   
            $nodeSave=new Core_Model_NodeSave();
            $nodeSave->setNode("student_history");
            $nodeSave->setData("student_admission_id",$this->_requestedData['id']);
            $nodeSave->setData("admission_no",$this->_requestedData['admission_no']);
            $nodeSave->setData("fee_list_feegroup_id",'TAD');
            $nodeSave->setData("student_quota_id",$this->_requestedData['student_quota_id']);
            $nodeSave->setData("cur_list_academicyear_id",$this->_requestedData['cur_list_academicyear_id']);
            $nodeSave->setData("list_branch_id",$this->_requestedData['list_branch_id']);
            $nodeSave->setData("cur_branch_orientation_id",$this->_requestedData['cur_branch_orientation_id']);
            $nodeSave->setData("cur_branch_class_id",$this->_requestedData['cur_branch_class_id']);
            $nodeSave->setData("cur_branch_class_section_id",$this->_requestedData['cur_branch_class_section_id']);
            $nodeSave->setData("cur_list_class_id",$cur_list_class_id);
            $nodeSave->setData("student_status_id",$this->_requestedData['student_status_id']);
            $nodeSave->setData("date",$this->_requestedData['admission_date']);
            $nodeSave->setData("fee_list_feeplan_id",$fee_list_feeplan_id);
            $nodeSave->setData("student_action_id","ALT");                       
            $nodeSave->save();

            $nodeSave=new Core_Model_NodeSave();
            $nodeSave->setNode("student_log");
            $nodeSave->setData("student_admission_id",$this->_requestedData['id']);
            $nodeSave->setData("admission_no",$this->_requestedData['admission_no']);
            $nodeSave->setData("fee_list_feegroup_id",'TAD');
            $nodeSave->setData("student_quota_id",$this->_requestedData['student_quota_id']);
            $nodeSave->setData("cur_list_academicyear_id",$this->_requestedData['cur_list_academicyear_id']);
            $nodeSave->setData("list_branch_id",$this->_requestedData['list_branch_id']);
            $nodeSave->setData("cur_branch_orientation_id",$this->_requestedData['cur_branch_orientation_id']);
            $nodeSave->setData("cur_branch_class_id",$this->_requestedData['cur_branch_class_id']);
            $nodeSave->setData("cur_branch_class_section_id",$this->_requestedData['cur_branch_class_section_id']);
            $nodeSave->setData("cur_list_class_id",$cur_list_class_id);        
            $nodeSave->setData("student_status_id",$this->_requestedData['student_status_id']);
            $nodeSave->setData("date",$this->_requestedData['admission_date']);
            $nodeSave->setData("fee_list_feeplan_id",$fee_list_feeplan_id);
            $nodeSave->setData("student_action_id","ALT");                       
            $nodeSave->save();
        }       
        return true;
    }
    public function getDetailsAction()
    {
        if($this->_currentSelector!="")
        {
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("student_admission","sd");
            $db->addFieldArray(array("sd.name"=>"name"));
            $db->addFieldArray(array("sd.admission_no"=>"admission_no"));            
            $db->addFieldArray(array("sd.image"=>"image"));
            $db->addFieldArray(array("sd.present_address"=>"present_address"));
            $db->addFieldArray(array("sd.email_id"=>"email"));
            $db->addFieldArray(array("sd.primary_phno"=>"primary_phno"));
            $db->addFieldArray(array("sd.mother_name"=>"mother_name"));
            $db->addFieldArray(array("sd.father_name"=>"father_name"));
            $db->addFieldArray(array("sq.name"=>"status"));
            $db->addFieldArray(array("lac.academicyear"=>"academicyear"));
            $db->addFieldArray(array("lb.name"=>"branch"));
            $db->addFieldArray(array("lo.name"=>"course"));
            $db->addFieldArray(array("lc.name"=>"classname"));
            $db->addFieldArray(array("ls.name"=>"section"));
            $db->addFieldArray(array("lac.short_code"=>"acyearcode"));
            $db->addJoin("cur_list_academicyear_id","cur_list_academicyear","lac","sd.cur_list_academicyear_id=lac.short_code");
            $db->addJoin("list_branch_id","list_branch","lb","sd.list_branch_id=lb.id");
            $db->addJoin("student_quota_id","student_quota","sq","sd.student_quota_id=sq.short_code");
            $db->addJoin("cur_branch_orientation_id","cur_branch_orientation","bo","sd.cur_branch_orientation_id=bo.id");
            $db->addJoin("cur_list_orientation_id","cur_list_orientation","lo","bo.cur_list_orientation_id=lo.id");
            $db->addJoin("cur_branch_class_id","cur_branch_class","bc","sd.cur_branch_class_id=bc.id");
            $db->addJoin("cur_list_class_id","cur_list_class","lc","bc.cur_list_class_id=lc.id");
            $db->addJoin("cur_branch_class_section_id","cur_branch_class_section","ls","sd.cur_branch_class_section_id=ls.id");
            $db->addWhere("sd.id='".$this->_currentSelector."'");
            $db->buildSelect();
            
            $record=$db->getRow();
            if(Core::countArray($record)>1)
            {
                $status="success";
            }
            else 
            {
                $status="error";
                $message="Please Send Valid Student Id ";
            }
            echo Core::convertArrayToJson(array("status"=>"success","message"=>$message,"data"=>$record));
        }        
    }
    public function getStudentIdAction()
    {
        if($this->_currentSelector!="")
        {
            $db=new Core_DataBase_ProcessQuery();            
            $db->setTable($this->_tableName);              
            $db->addField("id");
            $db->addWhere("admission_no='".$this->_currentSelector."'");
            $id=$db->getValue();
            if($id)
            {
                $status="success";
            }
            else 
            {
                $status="error";
                $message="Please Send Valid Admission No ";
            }
            echo Core::convertArrayToJson(array("status"=>$status,"message"=>$message,"id"=>$id)); 
        }        
    }
    public function validateStudentLoginAction()
    {
        $requestedData=$this->_requestedData;
        
        $db=new Core_DataBase_ProcessQuery();            
        $db->setTable($this->_tableName);              
        $db->addField("id");
        $db->addWhere("admission_no='".$requestedData['admissionno']."' and password='".$requestedData['password']."'");
        $db->buildSelect();
        
        $id=$db->getValue();
        if($id)
        {
            $status="success";
        }
        else 
        {
            $status="error";
            $message="Please Send Valid Credentails ";
        }        
        echo $output=Core::convertArrayToJson(array("status"=>$status,"message"=>$message,"id"=>$id)); 
        Core::Log($output,"studentlogin.log");
    }
    
    public function financefeereceiptStudentAdmissionIdDescriptorAction()
    {
        $db= new Core_DataBase_ProcessQuery();
        $db->setTable("student_history");
        $db->addFieldArray(array("student_admission_id"=>"pid"));
        $db->addFieldArray(array("admission_no"=>"pds"));
        $db->addWhere("fee_list_feegroup_id='TAD'");
        $db->addWhere("cur_list_academicyear_id='".$this->_requestedData['cur_list_academicyear_id']."'");
        $db->addWhere("list_branch_id='".$this->_requestedData['list_branch_id']."'");
        $db->addWhere("cur_branch_orientation_id='".$this->_requestedData['cur_branch_orientation_id']."'");
        $db->addWhere("cur_branch_class_id='".$this->_requestedData['cur_branch_class_id']."'");
        $db->addWhere("cur_branch_class_section_id='".$this->_requestedData['cur_branch_class_section_id']."'");
        $result=$db->getRows();
        $attributeType="select";        
        $attributeDetails=new Core_Attributes_LoadAttribute($attributeType);				
        $attributeClass=Core_Attributes_.$attributeDetails->_attributeName;
        $attribute=new $attributeClass;
        $attribute->setIdName("student_admission_id");
        $attribute->setOptions($result);
        $attribute->setValue($defaultValue);        
        $attribute->loadAttributeTemplate($attributeType,"student_admission_id");
    }
}
