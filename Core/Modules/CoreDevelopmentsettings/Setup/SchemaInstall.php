<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SchemaInstall
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Setup_SchemaInstall
{
    //put your code here
    function __construct() 
    {
        $this->setUp();
    }
    protected function setUp()
    {
        $nodesArray=array(
            // Node Actions            
            "CoreActionType"=>"CoreActionType",
            "CoreNodeActions"=>"CoreNodeActions",
            //Node Management
            "CoreRegisternode"=>"CoreRegisternode",
            "CoreNodeSettings"=>"CoreNodeSettings",            
            "CoreElementDisplaytype"=>"CoreElementDisplaytype",
            "CoreUniquefieldset"=>"CoreUniquefieldset",
            "CoreDefaultvalues"=>"CoreDefaultvalues",
            // Attributes 
            "CoreRootAttributes"=>"CoreRootAttributes",
            "CoreNodeAttributes"=>"CoreNodeAttributes",
            "CoreNodeFieldattributes"=>"CoreNodeFieldattributes",
            // Custom Attributes 
            "CoreAttributeOption"=>"CoreAttributeOption",
            "CoreNodeAttributeOption"=>"CoreNodeAttributeOption",
            "CoreNodeAttributeOptionValue"=>"CoreNodeAttributeOptionValue",
            //File Management
            "CoreFileTypes"=>"CoreFileTypes",
            "CoreCmsUploadfolders"=>"CoreCmsUploadfolders",
            "CoreCmsImageSettings"=>"CoreCmsImageSettings",
            "CoreCmsMediatype"=>"CoreCmsMediatype",
            "CoreNodeFiletypes"=>"CoreNodeFiletypes",            
            // Relation Settings
            "CoreRelationType"=>"CoreRelationType",
            "CoreNodeRelations"=>"CoreNodeRelations",
            // Form Settings
            "CoreFormSettings"=>"CoreFormSettings",
            "CoreFormLayout"=>"CoreFormLayout",            
            //Report Settings
            "CoreOrderclausetype"=>"CoreOrderclausetype",
            "CoreAggregateFunction"=>"CoreAggregateFunction",
            "CoreQueryclause"=>"CoreQueryclause",
            "CoreOutputType"=>"CoreOutputType",
            "CoreReportOutputtypes"=>"CoreReportOutputtypes",
            "CoreReportDetails"=>"CoreReportDetails",
            "CoreReportsdetailsSettings"=>"CoreReportsdetailsSettings",
            //User Management
            "CoreAccess"=>"CoreAccess",
            "CoreProfileLevel"=>"CoreProfileLevel",
            "CoreProfile"=>"CoreProfile",
            "CoreUsers"=>"CoreUsers",
            "CoreLoginhistory"=>"CoreLoginhistory",
            "CoreUseronline"=>"CoreUseronline",
            "CoreWebsiteloginhistory"=>"CoreWebsiteloginhistory",
            // CoreAppSettings
            "CoreAppSettings"=>"CoreAppSettings",
            // Archive Settings
            "CoreArchiveData"=>"CoreArchiveData",
            "CoreWebsiteusers"=>"CoreWebsiteusers",
            "CoreNodeHistory"=>"CoreNodeHistory",
            //backup
            "CoreBackupType"=>"CoreBackupType",
            "CoreBackupdetails"=>"CoreBackupdetails",
            // Email Settings
            "CoreEmailSettings"=>"CoreEmailSettings", 
            "CoreEmailNotifications"=>"CoreEmailNotifications",
                       
            //Label Settings
            "CoreLabelDetails"=>"CoreLabelDetails",
            
            // Report Templates
            "CoreReportTemplate"=>"CoreReportTemplate",            
            // Sequence Settings
            "CoreSettingsSequence"=>"CoreSettingsSequence",
            // Socail Media Settings
            "CoreSocialnetworkingsites"=>"CoreSocialnetworkingsites", 
            // Admin Theme Settings
            "CoreAdminthemeSettings"=>"CoreAdminthemeSettings",
            // SMS Settings 
            "CoreSmsSettings"=>"CoreSmsSettings",
            "CoreSmsNotifications"=>"CoreSmsNotifications",
            "CoreSmsLog"=>"CoreSmsLog",
            // Organization             
            "CoreOrganization"=>"CoreOrganization",
            "CoreOrganizationHistory"=>"CoreOrganizationHistory",
            "CoreOrganizationSocialnetworkingsites"=>"CoreOrganizationSocialnetworkingsites",
            
            // Portal Theme Settings
            "CoreCmsThemesettings"=>"CoreCmsThemesettings",
            
            // General Settings
            "CoreCountry"=>"CoreCountry",
            "CoreAttendanceStatus"=>"CoreAttendanceStatus",
            "CoreListCity"=>"CoreListCity",
            "CoreListLocation"=>"CoreListLocation",
            "CoreListState"=>"CoreListState",
            "CorePaymentType"=>"CorePaymentType",
           
        );
        foreach ($nodesArray as $node) 
        {
            $nodeClass="Core_Modules_CoreDevelopmentsettings_Setup_".$node;
            $rnode=new $nodeClass();
            $rnode->execute();
        }               
    }
}
