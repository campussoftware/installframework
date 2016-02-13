<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of CoreWebsiteloginhistory
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Setup_CoreWebsiteloginhistory 
{
    //put your code here
    function execute()
    {
        $setup=new Core_DataBase_Setup();   
        $setup->setTable("core_websiteloginhistory");
        if(!$setup->tableExists($setup->getTable()))
        {
            $setup->setDisplayValue("Website Login History Details");
            $setup->addColumnName(array(
                "name"=>"id",
                "displayValue"=>"Id",
                "prmiary"=>1,
                "key"=>"primary",
                "default"=>NULL,
                "type"=>"int",
                "size"=>"11",
                "auto_increment"=>1            
            ));
            $setup->addColumnName(array(
                "name"=>"loginname",
                "displayValue"=>"Login Name",            
                "default"=>false,                
                "type"=>"varchar",
                "size"=>"255"          
            ));
            $setup->addColumnName(array(
                "name"=>"loginid",
                "displayValue"=>"Login Id",            
                "default"=>false,                
                "type"=>"varchar",
                "size"=>"255"          
            ));
            $setup->addColumnName(array(
                "name"=>"core_profile_id",
                "displayValue"=>"Profile Id",                
                "type"=>"varchar",
                "size"=>"255"                
            )); 
            $setup->addColumnName(array(
                "name"=>"datetime",
                "displayValue"=>"Datetime",                
                "type"=>"datetime"          
            )); 
            $setup->addColumnName(array(
                "name"=>"host",
                "displayValue"=>"Host",                
                "type"=>"varchar",
                "size"=>"255"                
            ));  
            $setup->addColumnName(array(
                "name"=>"sesssionid",
                "displayValue"=>"Sesssion Id",               
                "type"=>"varchar",
                "size"=>"255"                
            ));
            $setup->addColumnName(array(
                "name"=>"is_online",
                "displayValue"=>"Is Online",               
                "type"=>"varchar",
                "size"=>"255"                
            ));
            $setup->addColumnName(array(
                "name"=>"createdby",
                "displayValue"=>"Created User Id",            
                "default"=>NULL,
                "type"=>"int",
                "size"=>"11"
            ));
            $setup->addColumnName(array(
                "name"=>"createdat",
                "displayValue"=>"Created Datetime",            
                "default"=>NULL,
                "type"=>"datetime"
            ));
            $setup->addColumnName(array(
                "name"=>"updatedby",
                "displayValue"=>"Updated User Id",            
                "default"=>NULL,
                "type"=>"int",
                "size"=>"11"
            ));
            $setup->addColumnName(array(
                "name"=>"updatedat",
                "displayValue"=>"Updated Datetime",            
                "default"=>NULL,
                "type"=>"datetime"
            ));
            $setup->create();
        }
    }
}
