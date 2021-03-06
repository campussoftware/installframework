<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreNodeActions
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Controllers_CoreNodeActions extends Core_Controllers_NodeController
{
    //put your code here
    private $_name=NULL;
    private $_short_code=NULL;
    private $_core_action_type_id=NULL;
    private $_sort_no=NULL;
    
    public function setNodeActionName($name) 
    {
        $this->_name=$name;
    }
    public function getNodeActionName()
    {
        return $this->_name;
    }

    public function setNodeActionShortCode($code)
    {
        $this->_short_code=$code;
    }
    public function getNodeActionShortCode()
    {
        return $this->_short_code;
    }
    public function setNodeActionType($actionType)
    {
        $this->_core_action_type_id=$actionType;
    }
    public function getNodeActionType()
    {
        return $this->_core_action_type_id;
    }
    public function setNodeActionSortNo($sortNo)
    {
        $this->_sort_no=$sortNo;
    }
    public function getNodeActionSortNo()
    {
        return $this->_sort_no;
    }

    public function dataSave()
    {        
        try
        {
            $db1=new Core_DataBase_ProcessQuery();
            $db1->setTable("core_node_actions");
            $db1->addField("id");
            $db1->addWhere("short_code='".$this->getNodeActionShortCode()."'");
            $registernodeid=$db1->getValue();
            $db=new Core_DataBase_ProcessQuery();
            $db->setTable("core_node_actions");
            $db->addFieldArray(array("name"=>$this->getNodeActionName()));
            $db->addFieldArray(array("short_code"=>$this->getNodeActionShortCode()));
            $db->addFieldArray(array("core_action_type_id"=>$this->getNodeActionType()));
            $db->addFieldArray(array("sort_no"=>$this->getNodeActionSortNo()));
            $db->addFieldArray(array("updatedat"=>Core::getDateTime()));
            if($registernodeid)
            {
                $db->addWhere("short_code='".$this->getNodeActionShortCode()."'");
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
