<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Studentshift
 *
 * @author ramesh
 */
class Modules_Studentmanagement_Controllers_Studentshift extends Core_Controllers_NodeController
{
    //put your code here
    public function getAcademicYearDetails()
    {
        $node =  CoreClass::getModel("cur_list_academicyear");
        $node->setNodeName("cur_list_academicyear");
        $node->getCollection();
        return $node->_collections;
    }
    public function getBranchDetails()
    {
        $node =  CoreClass::getModel("list_branch_id");
        $node->setNodeName("list_branch");
        $node->getCollection();
        return $node->_collections;
    }
    public function addFilter() 
    {
        $requestedData=$this->_requestedData;
        parent::addFilter();
        if($this->_whereCon)
        {
            $this->_whereCon.=" and ";
        }
        
        $this->_whereCon.=$this->_nodeName.".cur_list_academicyear_id='".$requestedData['search_list_acdemicyear_id']."'";
        $this->_whereCon.=" and ".$this->_nodeName.".list_branch_id='".$requestedData['search_list_branch_id']."'";
        $this->_whereCon.=" and ".$this->_nodeName.".cur_branch_orientation_id='".$requestedData['search_branch_orientation_id']."'";
        $this->_whereCon.=" and ".$this->_nodeName.".cur_branch_class_id='".$requestedData['search_branch_class_id']."'";
    }
    function mraSHFAction()
    {
        $requestedData=$this->_requestedData;
        $list_academicyear_id=$requestedData['cur_list_academicyear_id'];
        $list_branch_id=$requestedData['list_branch_id'];
        $branch_orientation_id=$requestedData['cur_branch_orientation_id'];
        $branch_class_id=$requestedData['cur_branch_class_id'];
        $branch_class_section_id=$requestedData['cur_branch_class_section_id'];
        $selectorlist=Core::covertStringToArray($requestedData['studentshift_selector']);
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_orientation");
        $db->addField("cur_list_orientation_id");
        $db->addWhere("id='".$branch_orientation_id."'");
        $db->buildSelect();
        $cur_list_orientation_id=$db->getValue();
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_class");
        $db->addField("cur_list_class_id");
        $db->addWhere("id='".$branch_class_id."'");
        $db->buildSelect();
        $cur_list_class_id=$db->getValue();
        
        foreach ($selectorlist as $studentId)
        {
            $qry=new Core_DataBase_ProcessQuery();
            $qry->setTable("student_admission");
            $qry->addWhere("student_admission.id='".$studentId."'");
            $qry->buildSelect();
            $studentdetails=$qry->getRow();
            $flag=1;
            if($studentdetails['cur_list_academicyear_id']==$list_academicyear_id)
            {
                if($studentdetails['list_branch_id']==$list_branch_id)
                {
                    if($studentdetails['cur_branch_orientation_id']==$branch_orientation_id)
                    {
                        if($studentdetails['cur_branch_class_id']==$branch_class_id)
                        {
                            if($studentdetails['cur_branch_class_section_id']==$branch_class_section_id)
                            {
                                $flag=0;
                            }
                        }
                    }
                }
            }
            if($flag==1)
            {
                try
                {
                $student_status_id=$studentdetails['student_status_id'];
                $student_action_id="SFD";
                if($studentdetails['cur_list_academicyear_id']!=$list_academicyear_id)
                {
                    $student_status_id="EX";
                    $student_action_id="PMT";
                }
                $db=new Core_DataBase_ProcessQuery();
                $db->setTable("student_history");
                $db->addField("max(student_history.id)");
                $db->buildSelect();
                $db->addWhere("student_history.student_admission_id='".$studentId."'");
                $student_history_id=$db->getValue();
                
                $nodeSave=new Core_Model_NodeSave();
                $nodeSave->setNode("student_history");
                $nodeSave->setData("id",$student_history_id);
                $nodeSave->setData("student_admission_id",$studentId);
                $nodeSave->setData("admission_no",$studentdetails['admission_no']);
                $nodeSave->setData("fee_list_feegroup_id",'TAD');
                $nodeSave->setData("student_quota_id",$studentdetails['student_quota_id']);
                $nodeSave->setData("cur_list_academicyear_id",$list_academicyear_id);
                $nodeSave->setData("list_branch_id",$list_branch_id);
                $nodeSave->setData("cur_branch_orientation_id",$branch_orientation_id);
                $nodeSave->setData("cur_branch_class_id",$branch_class_id);
                $nodeSave->setData("cur_branch_class_section_id",$branch_class_section_id);
                $nodeSave->setData("cur_list_class_id",$cur_list_class_id);
                $nodeSave->setData("student_status_id",$student_status_id);
                $nodeSave->setData("date",date('Y-m-d'));
                $nodeSave->setData("fee_list_feeplan_id",$fee_list_feeplan_id);
                $nodeSave->setData("student_action_id","ALT");                       
                $nodeSave->save();
                echo "<pre>";
                    print_r($nodeSave);
                echo "</pre>";
                $db=new Core_DataBase_ProcessQuery();
                $db->setTable("student_log");
                $db->addField("max(student_log.id)");
                $db->addWhere("student_log.student_admission_id='".$studentId."'");
                $db->buildSelect();
                $studentlogid=$db->getValue();
                $nodeSave=new Core_Model_NodeSave();
                $nodeSave->setNode("student_log");
                $nodeSave->setData("id",$studentlogid);
                $nodeSave->setData("student_action_id",$student_action_id);
                $nodeSave->setForceUpdate();
                $nodeSave->save();
                $nodeSave=new Core_Model_NodeSave();
                $nodeSave->setNode("student_log");
                $nodeSave->setData("student_admission_id",$studentId);
                $nodeSave->setData("admission_no",$studentdetails['admission_no']);
                $nodeSave->setData("fee_list_feegroup_id",'TAD');
                $nodeSave->setData("student_quota_id",$studentdetails['student_quota_id']);
                $nodeSave->setData("cur_list_academicyear_id",$list_academicyear_id);
                $nodeSave->setData("list_branch_id",$list_branch_id);
                $nodeSave->setData("cur_branch_orientation_id",$branch_orientation_id);
                $nodeSave->setData("cur_branch_class_id",$branch_class_id);
                $nodeSave->setData("cur_branch_class_section_id",$branch_class_section_id);
                $nodeSave->setData("cur_list_class_id",$cur_list_class_id);        
                $nodeSave->setData("student_status_id",$student_status_id);
                $nodeSave->setData("date",date('Y-m-d'));
                $nodeSave->setData("fee_list_feeplan_id",$fee_list_feeplan_id);
                $nodeSave->setData("student_action_id","ALT");                       
                $nodeSave->save();
                }
                catch (Exception $ex)
                {
                    Core::Log(__METHOD__.$ex->getMessage());
                }
            }            
        }
        
    }
    function getBranchCoursesAction()
    {
        $requestedData=$this->_requestedData;
        
        $defaultValue="";
        $list_branch_id=$requestedData['list_branch_id'];
        $idName="cur_branch_orientation_id";
        if($requestedData['type']=='filter')
        {
            $defaultValue=$requestedData['search_branch_orientation_id'];
            $idName="search_branch_orientation_id";
            $list_branch_id=$requestedData['search_list_branch_id'];
        }
        
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_orientation","bo");
        $db->addFieldArray(array("bo.id"=>"pid"));
        $db->addFieldArray(array("lo.name"=>"pds"));
        $db->addJoin("cur_list_orientation_id","cur_list_orientation","lo","bo.cur_list_orientation_id=lo.id");
        $db->addWhere("bo.list_branch_id='".$list_branch_id."'");
        $db->buildSelect();
        
        $result=$db->getRows();
        $attributeType="select";        
        $attributeDetails=new Core_Attributes_LoadAttribute($attributeType);				
        $attributeClass="Core_Attributes_".$attributeDetails->_attributeName;
        $attribute=new $attributeClass;
        $attribute->setIdName($idName);
        $attribute->setOptions($result);
        if($requestedData['type']=='filter')
        {
            $attribute->setOnchange("getbranchclasess('filter');");
        }
        else
        {
            $attribute->setOnchange("getbranchclasess();");
        }
        $attribute->setValue($defaultValue);        
        $attribute->loadAttributeTemplate($attributeType,$idName);
    }
    function getBranchClassesAction()
    {
        $requestedData=$this->_requestedData;
        $idName="cur_branch_class_id";
        $defaultValue="";
        $branch_orientaion_id=$requestedData['cur_branch_orientation_id'];
        if($requestedData['type']=='filter')
        {
            $defaultValue=$requestedData['search_branch_class_id'];
            $idName="search_branch_class_id";
            $branch_orientaion_id=$requestedData['search_branch_orientation_id'];
        }
        $cur_list_academicyear_id=$requestedData['cur_list_academicyear_id'];
        if($requestedData['type']=='filter')
        {
            $cur_list_academicyear_id=$requestedData['search_list_acdemicyear_id'];
        }
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_class","bc");
        $db->addFieldArray(array("bc.id"=>"pid"));
        $db->addFieldArray(array("lc.name"=>"pds"));
        $db->addJoin("cur_list_class_id","cur_list_class","lc","bc.cur_list_class_id=lc.id");
        $db->addWhere("bc.cur_list_academicyear_id='".$cur_list_academicyear_id."'");
        $db->addWhere("bc.cur_branch_orientation_id='".$branch_orientaion_id."'");
        $db->buildSelect();
        
        $result=$db->getRows(); 
        $attributeType="select";        
        $attributeDetails=new Core_Attributes_LoadAttribute($attributeType);				
        $attributeClass="Core_Attributes_".$attributeDetails->_attributeName;
        $attribute=new $attributeClass;
        $attribute->setIdName($idName);
        $attribute->setOptions($result);
        if($requestedData['type']=='filter')
        {        
            $attribute->setOnchange("getbranchsections('filter');");
        }
        else
        {
            $attribute->setOnchange("getbranchsections();");
        }
        $attribute->setValue($defaultValue);        
        $attribute->loadAttributeTemplate($attributeType,$idName);
    }
    function getBranchSectionsAction()
    {
        $requestedData=$this->_requestedData;
        $idName="cur_branch_class_section_id";
        $cur_branch_class_id=$requestedData['cur_branch_class_id'];
        if($requestedData['type']=='filter')
        {
            $idName="search_branch_class_section_id";
            $cur_branch_class_id=$requestedData['search_branch_class_id'];
        }
        $db=new Core_DataBase_ProcessQuery();
        $db->setTable("cur_branch_class_section","ls");
        $db->addFieldArray(array("ls.id"=>"pid"));
        $db->addFieldArray(array("ls.name"=>"pds"));
        $db->addWhere("ls.cur_branch_class_id='".$cur_branch_class_id."'");
        $db->buildSelect();
        
        $result=$db->getRows(); 
        $attributeType="select";        
        $attributeDetails=new Core_Attributes_LoadAttribute($attributeType);				
        $attributeClass="Core_Attributes_".$attributeDetails->_attributeName;
        $attribute=new $attributeClass;
        $attribute->setIdName($idName);
        $attribute->setOptions($result);
        $attribute->setValue($defaultValue);        
        $attribute->loadAttributeTemplate($attributeType,$idName);
    }
}
