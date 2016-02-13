<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DataInstall
 *
 * @author ramesh
 */
class Core_Modules_CoreDevelopmentsettings_Data_DataInstall 
{
    //put your code here
    function __construct() 
    {
        $this->setDataUp();
    }
    protected function setDataUp()
    {
        $nodesArray=array(
            "CoreModuleCreate"=>"CoreModuleCreate",
            "CoreRegisternode"=>"CoreRegisternode",
            "CoreNodeSettings"=>"CoreNodeSettings",
            "CoreActionType"=>"CoreActionType",
            "CoreAdminthemesettings"=>"CoreAdminthemesettings",
            "CoreCmsUploadfolders"=>"CoreCmsUploadfolders",
            "CoreNodeActions"=>"CoreNodeActions",
            "CoreRootAttributes"=>"CoreRootAttributes",
            "CoreUniquefieldset"=>"CoreUniquefieldset",
            "CoreQueryClause"=>"CoreQueryClause",
            "CoreOrderClausetype"=>"CoreOrderClausetype",
            "CoreAggregateFunction"=>"CoreAggregateFunction",
            "CoreOutputType"=>"CoreOutputType",
            "CoreNodeHistory"=>"CoreNodeHistory",
            "CoreElementDisplaytype"=>"CoreElementDisplaytype",
            "CoreFormSettings"=>"CoreFormSettings",
            "CoreBackupType"=>"CoreBackupType",
            "CoreRelationType"=>"CoreRelationType",
            "CoreNodeRelations"=>"CoreNodeRelations",
            "CoreFormLayout"=>"CoreFormLayout",
            "CoreDefaultvalues"=>"CoreDefaultvalues",
            "CoreNodeFieldattributes"=>"CoreNodeFieldattributes",
            "CoreAccess"=>"CoreAccess",
            "CoreLoginhistory"=>"CoreLoginhistory",
            "CoreProfile"=>"CoreProfile",
            "CoreProfileLevel"=>"CoreProfileLevel",
            "CoreUsers"=>"CoreUsers",
            "CoreCmsImageSettings"=>"CoreCmsImageSettings",
            "CoreCmsMediatype"=>"CoreCmsMediatype",
            "CoreEmailNotifications"=>"CoreEmailNotifications",
            "CoreFileTypes"=>"CoreFileTypes",
            "CoreLabelDetails"=>"CoreLabelDetails",
            "CoreNodeAttributes"=>"CoreNodeAttributes",
            "CoreNodeFiletypes"=>"CoreNodeFiletypes",
            "CoreReportTemplate"=>"CoreReportTemplate",
            "CoreSettingsSequence"=>"CoreSettingsSequence",
            "CoreSocialnetworkingsites"=>"CoreSocialnetworkingsites",
            "CoreEmailSettings"=>"CoreEmailSettings",
            "CoreSmsSettings"=>"CoreSmsSettings",
            "CoreSmsNotifications"=>"CoreSmsNotifications",
            "CoreAppSettings"=>"CoreAppSettings",
            "CorePaymenType"=>"CorePaymenType",
            "CoreOrganization"=>"CoreOrganization",
            "CoreListLocation"=>"CoreListLocation",
            "CoreOrganizationSocialnetworkingsites"=>"CoreOrganizationSocialnetworkingsites",
            "CoreArchiveData"=>"CoreArchiveData",
            "CoreReportsdetailsSettings"=>"CoreReportsdetailsSettings",
            "CoreSmsLog"=>"CoreSmsLog",
            "CoreReportsengine"=>"CoreReportsengine",
            "CoreReportsdetails"=>"CoreReportsdetails",
            "CoreBackupdetails"=>"CoreBackupdetails",
            "CoreCodeBackup"=>"CoreCodeBackup",
            "CoreDbbackfile"=>"CoreDbbackfile",
            "CoreReportOutputtypes"=>"CoreReportOutputtypes",
            "CoreUsedashboard"=>"CoreUsedashboard",
            "CoreListCity"=>"CoreListCity",
            "CoreListState"=>"CoreListState",
            "CoreAttendanceStatus"=>"CoreAttendanceStatus",
            "CoreCountry"=>"CoreCountry"
            
            
        );
        foreach ($nodesArray as $node) 
        {
            $nodeClass="Core_Modules_CoreDevelopmentsettings_Data_".$node;
            $rnode=new $nodeClass();
            $rnode->execute();
        } 
        
    }
}
