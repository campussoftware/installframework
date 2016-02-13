<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreNodeSettings
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Models_CoreNodeSettings extends Core_Model_Node
{
    //put your code here
    
    function coreRegisternodeIdAddSingleFilter()
    {
        if($this->_currentAction=="add")
        {
            return "core_registernode.nodename not in (select core_registernode_id from core_node_settings)";
        }
    }
}
