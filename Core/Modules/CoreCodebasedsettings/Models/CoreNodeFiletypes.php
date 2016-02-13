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
class Core_Modules_CoreCodebasedsettings_Models_CoreNodeFiletypes extends Core_Model_Node
{
    //put your code here
    public function coreNodeSettingsIdAddSingleFilter()
    {
        return "file!=''";
    }
    public function coreNodeFiletypesOnchange()
    {
        $events=array();
        $events['core_node_settings_id']="getFieldsForNodeFiletypes();";           
        return $events;
    }
    
}
