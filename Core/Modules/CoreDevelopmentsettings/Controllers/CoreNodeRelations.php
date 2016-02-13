<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreNodeRelations
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Controllers_CoreNodeRelations extends Core_Controllers_NodeController
{
    private $core_node_settings_id=NULL;
    private $core_node_colname=NULL;
    private $core_relation_type_id=NULL;
    private $core_node_parent=NULL;
    private $dependee_fields=NULL;
    private $sort_value=NULL;
      
    
    public function coreNodeRelationsAfterDataUpdate()
    {        
        $cache=new Core_Cache_Refresh();
        $cache->setNodeName($this->_requestedData['core_node_settings_id']);
        $cache->setRelations();  
        $cache=new Core_Cache_Refresh();
        $cache->setNodeName($this->_requestedData['core_node_parent']);
        $cache->setChildRelations();
        return TRUE;  
    }
    public function getStructureAction()
    {
        $requestedData=$this->_requestedData;
        $defaultValue=$requestedData['dependee_fields'];
        $np=new Core_Model_NodeProperties();
        $np->setNode($requestedData['core_node_settings_id']);
        $nodestructure=$np->currentNodeStructure();
        
        $tb=new Core_Model_TableStructure();
        $tb->setTable($nodestructure['tablename']);
        $tableStructure=$tb->getStructure();
        $tableStructure=Core::getKeysFromArray($tableStructure);
        $tableStructure=Core::diffArray($tableStructure, $this->_defaulthideAttributes);
        
        $result=array();
        $i=0;
        foreach ($tableStructure as $key) 
        {
            $result[$i]['pid']=$key;
            $result[$i]['pds']=$this->getLabel($key);
            $i++;
        }
        $attributeType="checkbox";        
        $attributeDetails=new Core_Attributes_LoadAttribute($attributeType);				
        $attributeClass=Core_Attributes_.$attributeDetails->_attributeName;
        $attribute=new $attributeClass;
        $attribute->setIdName($requestedData['idname']);
        $attribute->setOptions($result);
        $attribute->setValue($defaultValue);        
        $attribute->loadAttributeTemplate($attributeType,$requestedData['idname']);
    }    
    public function setCoreNodeSettingsId($core_node_settings_id)
    {
        $this->_core_node_settings_id=$core_node_settings_id;
    }
    public function getCoreNodeSettingsId()
    {
        return $this->_core_node_settings_id;
    }
    public function setCoreNodeColname($core_node_colname)
    {
        $this->_core_node_colname=$core_node_colname;
    }
    public function getCoreNodeColname()
    {
        return $this->_core_node_colname;
    }
    public function setCoreRelationTypeId($core_relation_type_id)
    {
        $this->_core_relation_type_id=$core_relation_type_id;
    }
    public function getCoreRelationTypeId()
    {
        return $this->_core_relation_type_id;
    }
    public function setDependeeFields($dependee_fields)
    {
        $this->_dependee_fields=$dependee_fields;
    }
    public function getDependeeFields()
    {
        return $this->_dependee_fields;
    }
    public function setCoreNodeParent($core_node_parent)
    {
        $this->_core_node_parent=$core_node_parent;
    }
    public function getCoreNodeParent()
    {
        return $this->_core_node_parent;
    }
    
    public function setSortValue($sort_value)
    {
        $this->_sort_value=$sort_value;
    }
    public function getSortValue()
    {
        return $this->_sort_value;
    }
    
   
       
    public function dataSave()
    {        
        try
        {
            $db1=new Core_DataBase_ProcessQuery();
            $db1->setTable("core_node_relations");
            $db1->addField("id");
            $db1->addWhere("core_node_settings_id='".$this->getCoreNodeSettingsId()."'");
            $db1->addWhere("core_relation_type_id='".$this->getCoreRelationTypeId()."'");
            if($this->getCoreRelationTypeId()=='MTO')
            {
                $db1->addWhere("core_node_colname='".$this->getCoreNodeColname()."'");            
            }
            else
            {
                $db1->addWhere("core_node_parent='".$this->getCoreNodeParent()."'");
            }
            $registernodeid=$db1->getValue();
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("core_node_relations");
            $db->addFieldArray(array("core_node_settings_id"=>$this->getCoreNodeSettingsId()));
            $db->addFieldArray(array("core_node_colname"=>$this->getCoreNodeColname()));            
            $db->addFieldArray(array("core_relation_type_id"=>$this->getCoreRelationTypeId()));            
            $db->addFieldArray(array("core_node_parent"=>$this->getCoreNodeParent()));            
            $db->addFieldArray(array("dependee_fields"=>$this->getDependeeFields()));            
            $db->addFieldArray(array("sort_value"=>$this->getSortValue()));            
            $db->addFieldArray(array("updatedat"=>Core::getDateTime()));
            if($registernodeid)
            {
                $db->addWhere("id='".$registernodeid."'");
                $db->buildUpdate();
            }
            else
            {
                $db->addFieldArray(array("createdat"=>Core::getDateTime()));
                $db->buildInsert();
            }     
            $db->executeQuery();        
        }
        catch(Exception $ex)
        {
            Core::Log(__METHOD__.$ex->getMessage(),"installdata.log");
        }
    }
}