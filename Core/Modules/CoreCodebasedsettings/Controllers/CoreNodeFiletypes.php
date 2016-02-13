<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreNodeFiletypes
 *
 * @author ramesh
 */
class Core_Modules_CoreCodebasedsettings_Controllers_CoreNodeFiletypes extends Core_Controllers_NodeController
{
    //put your code here
    public function getStructureAction()
    {
        $requestedData=$this->_requestedData;
        $nodeName=$requestedData['core_node_settings_id'];
        $defaultValue=$requestedData[$requestedData['idname']];
        $result=array();
        $i=0;
        if($nodeName)
        {
            $node= new Core_Model_Node();
            $node->setNodeName($nodeName);
            if(Core::countArray($node->_NodeFieldAttributes)>0)
            {
                foreach ($node->_NodeFieldAttributes as $key=>$type)
                {
                    if($type=='file')
                    {
                        $result[$i]['pid']=$key;
                        $result[$i]['pds']=$this->getLabel($key);
                        $i++;
                    }
                }
            }
        }        
        $attributeType="select";        
        $attributeDetails=new Core_Attributes_LoadAttribute($attributeType);				
        $attributeClass=Core_Attributes_.$attributeDetails->_attributeName;
        $attribute=new $attributeClass;
        $attribute->setIdName($requestedData['idname']);
        $attribute->setOptions($result);
        $attribute->setValue($defaultValue);        
        $attribute->loadAttributeTemplate($attributeType,$requestedData['idname']);
    }
    public function coreNodeFiletypesAfterDataUpdate()
    {        
        $nodeName=$requestedData['core_node_settings_id'];
        $cache=new Core_Cache_Refresh();
        $cache->setNodeName($nodeName);
        $cache->setFilePath();
        return true;
    }
    
}
