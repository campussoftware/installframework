<?php
class Core_Model_Abstract extends Core_Pages_PageLayout
{
    public $_nodeName;
    public $_parentNode;
    public $_parentSelector;
    public $_parentAction;
    public $_parentColName;
    public $_nodeProperties;
    public $_currentNodeModule;
    public $_currentAction;
    public $_singleActions=array();    
    public $_individualActions=array();
    public $_mraActions=array();
    public $_showAttributes=array(); 
    public $_collections=array();
    public $_tableName=NULL;
    public $_primaryKey=NULL;
    public $_autoKey=NULL;
    public $_descriptor=NULL;
    public $_nodeMTORelations=array();   
    public $_NodeFieldAttributes=array();
    public $_NodeFieldsList=array();
    public $_nodeOTORelations=array(); 
    public $_nodeOTMRelations=array(); 
    public $_isDefaultCollection=NULL;
    public $_isAPI=NULL;    
    public $_nodeFilePath=array();
    public $_filePath=array();
    public $_fileStoragePath=array();
    public function setAPI($apiMethod)
    {
        $this->_isAPI=$apiMethod;
    }
    public function setNodeName($nodename) 
    {
        $this->_nodeName=$nodename;
        $this->getModuleName();       
        $this->getAdminSettingsData();
    }
    public function setParentNode($parentNode)
    {
        $this->_parentNode=$parentNode;  
        $np=new Core_Model_NodeRelations();
        $np->setNode($this->_parentNode);
        $np->setParentNode($this->_nodeName);
        $this->_parentColName=$np->getParentColName();         
    }
    public function setParentValue($parentValue)
    {
        $this->_parentSelector=$parentValue;        
    }
    public function setParentAction($parentAction)
    {
        $this->_parentAction=$parentAction;
        $this->refreshNodeRelations();
        
    }
    public function setActionName($action) 
    {
        $this->_currentAction=$action;           
    }    
    public function getNodeName() 
    {
        return $this->_nodeName;            
    }
    public function setSurrentSelector($currentSelector) 
    {
        $this->_currentSelector=$currentSelector;           
    }
    public function getNodeProperties()
    {
        $np= new Core_Model_NodeProperties($this->_nodeName);
        return $np->nodeSettings();
    }
    public function getNodeFileProperties()
    {
        $np= new Core_Model_NodeProperties();
        $np->setNode($this->_nodeName);           
        return $np->getNodeDetails();
    }
    public function getModuleName()
    {

        $this->getNodeFileProperties();
        $this->_currentNodeModule=$this->getNodeFileProperties()['module'];   
        
    }
    public function getNodeActions()
    {
        global $currentProfileCode;
        $np= new Core_Model_NodeProperties($this->_nodeName);
        $np->setProfile($currentProfileCode);
        return explode(",",$np->getCurrentProfilePermissionNodeAction($currentProfileCode));
    }
    public function setSingleActions()
    {
        $nodeActions=$this->getNodeActions();            
        $np= new Core_Model_NodeProperties($this->_nodeName);
        foreach ($nodeActions as $actionData) 
        {
            $actionDataList=explode("||",$actionData);
            if($np->getActionType($actionDataList[1])=="SN")
            {
                if(!in_array($actionDataList[1],array("import","export","admin")))
                {
                    $this->_singleActions[]=array("name"=>$actionDataList[0],"code"=>$actionDataList[1]);                   
                }
            }                
        }            
    }  
    public function setIndividualActions()
    {
        $nodeActions=$this->getNodeActions();            
        $np= new Core_Model_NodeProperties($this->_nodeName);
        foreach ($nodeActions as $actionData) 
        {
            $actionDataList=explode("||",$actionData);
            $np->getActionType($actionDataList[1]);
            $actionType=$np->getActionType($actionDataList[1]);
            $actionTypeList=explode("|",$actionType);                
            if(in_array("IN", $actionTypeList))                {

                $this->_individualActions[]=array("name"=>$actionDataList[0],"code"=>$actionDataList[1]);                   

            }                
        }            
    }
    public function setMraActions()
    {
        $nodeActions=$this->getNodeActions();            
        $np= new Core_Model_NodeProperties($this->_nodeName);
        foreach ($nodeActions as $actionData) 
        {
            $actionDataList=explode("||",$actionData);
            $np->getActionType($actionDataList[1]);
            $actionType=$np->getActionType($actionDataList[1]);
            $actionTypeList=explode("|",$actionType);                
            if(in_array("MRA", $actionTypeList))                {

                $this->_mraActions[]=array("name"=>$actionDataList[0],"code"=>$actionDataList[1]);                   

            }                
        }            
    }
    public function getFieldsForNode()
    {
        $np= new Core_Model_NodeProperties($this->_nodeName);
        $np->setNode($this->_nodeName);
        $nodeStructure=$np->currentNodeStructure();        
        $nodeTable=$nodeStructure['tablename'];       
        $this->_tableName=$nodeTable;
        $this->getNodeFieldsProperties();
        
    }
    function getNodeFieldsProperties()
    {
        
        $ts=new Core_Model_TableStructure();
        $ts->setTable($this->_tableName);   
        $filedsArray=array();
        foreach ($ts->getStructure() as $key => $fieldStructure) 
        {
            $filedsArray[$fieldStructure['Field']]=$fieldStructure['Type'];           
        }
        $this->_NodeFieldsList=$filedsArray;
        return $this->_NodeFieldsList;
    }
    public function getAdminSettingsData()
    {
        $wp=new Core_WebsiteSettings();
        $np = new Core_Model_NodeProperties();
        $np->setNode($this->_nodeName);
        $this->_currentNodeStructure=$np->currentNodeStructure();
        $this->_tableName =$this->_currentNodeStructure['tablename'];
        $this->_primaryKey=$this->_currentNodeStructure['primkey'];
        $this->_autoKey=$this->_currentNodeStructure['autokey'];
        $this->_descriptor=$this->_currentNodeStructure['descriptor'];
        $this->_isDefaultCollection=$this->_currentNodeStructure['default_collection'];
        
        if(trim($this->_currentNodeStructure['uniquefields']))
        {
            $this->_uniqueAttributes=explode("|",  $this->_currentNodeStructure['uniquefields']);
        }
        if(trim($this->_currentNodeStructure['file']))
        {
            $this->_fileAttributes=explode("|",  $this->_currentNodeStructure['file']);
            $this->setNodeFeildAttribute($this->_fileAttributes,"file");
        }
        if(trim($this->_currentNodeStructure['boolattributes']))
        {
            $this->_boolAttributes=explode("|",  $this->_currentNodeStructure['boolattributes']);
            $this->setNodeFeildAttribute($this->_boolAttributes,"bool");
        }
        if(trim($this->_currentNodeStructure['numberattribute']))
        {
            $this->_numberAttributes=explode("|",  $this->_currentNodeStructure['numberattribute']);
        }                    
        if(trim($this->_currentNodeStructure['multivalues']))
        {
            $this->_multivaluesAttributes=explode("|",$this->_currentNodeStructure['multivalues']);
        }
        
        if(trim($this->_currentNodeStructure['selectbox']))
        {
            $SelectAttributes=explode("|",$this->_currentNodeStructure['selectbox']);
            if(count($SelectAttributes)>0)
            {
                $this->setNodeFeildAttribute($SelectAttributes,"select");
            }
        }
        if(trim($this->_currentNodeStructure['fck']))
        {
            $SelectAttributes=explode("|",$this->_currentNodeStructure['fck']);
            if(count($SelectAttributes)>0)
            {
                $this->setNodeFeildAttribute($SelectAttributes,"fck");
            }
        }
        if(trim($this->_currentNodeStructure['file']))
        {
            $SelectAttributes=explode("|",$this->_currentNodeStructure['file']);
            if(count($SelectAttributes)>0)
            {
                $this->setNodeFeildAttribute($SelectAttributes,"file");
            }
        }
        
        if(trim($this->_currentNodeStructure['checkbox']))
        {
            $SelectAttributes=explode("|",$this->_currentNodeStructure['checkbox']);
            if(count($SelectAttributes)>0)
            {
                $this->setNodeFeildAttribute($SelectAttributes,"checkbox");
            }
        }
        if(trim($this->_currentNodeStructure['search']))
        {
            $this->_searchAttributes=explode("|",  $this->_currentNodeStructure['search']);
        }        
        $nr=new Core_Model_NodeRelations();
        $nr->setNode($this->_nodeName);        
        $this->_nodeMTORelations=$nr->getCurrentNodeRelation();          
        $this->_nodeOTORelations=$nr->getCurrentNodeOneToOneRelation();
        $this->_nodeOTMRelations=$nr->getCurrentNodeOneToManyRelation();    
        $this->getFilePath();
    }
    function setNodeFeildAttribute($FieldList,$type)
    {
        if(!is_array($FieldList))
        {
            $FieldList=array($FieldList);
        }
        foreach ($FieldList as $FieldName)
        {
            $this->_NodeFieldAttributes[$FieldName]=$type;
        }
        $np = new Core_Model_NodeProperties();
        $np->setNode($this->_nodeName);
        $FieldAttributesValues=$np->getFieldAttributesValues();        
        if(Core::countArray($FieldAttributesValues)>0)
        {
            foreach($FieldAttributesValues as $FieldName=>$type)
            {
                $this->_NodeFieldAttributes[$FieldName]=$type;
            }
        }        
    }
    public function refreshNodeRelations()
    {       
        if($this->_parentNode)
        {
            $this->_nodeMTORelations=  array_merge(array($this->_parentColName=>$this->_parentNode),$this->_nodeMTORelations);
        }        
    }
    public function getUnqueFieldSetAttributes()
    {
        $np= new Core_Model_NodeProperties($this->_nodeName);
        $np->setNode($this->_nodeName);
        return $np->getUniqueSetValues();
    }
    public function getFilePath()
    {
        $np= new Core_Model_NodeProperties($this->_nodeName);
        $np->setNode($this->_nodeName);
        $this->_filePath=$np->getFilePath();
        $filePath=array();
        if(Core::countArray($this->_filePath))
        {
            foreach($this->_filePath as $fieldName=>$fieldData)
            {
                $ws=new Core_WebsiteSettings();
                $storageUrl=$ws->websiteAdminUrl.$ws->documentRootUpload."/";
                if($fieldData['storagefolder'])
                {
                    $storageUrl.=$fieldData['storagefolder']."/";
                }
                $filePath[$fieldName]=$storageUrl;
            }
        }
        $this->_fileStoragePath=$filePath;
    }
}