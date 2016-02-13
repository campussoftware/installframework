<?php
    define("ADMINNAME", "admin");
    define("ADMINPASS", 'Ramesh');
    class Core_WebsiteSettings
    {
        public $websiteUrl=NULL;
        public $websiteAdminUrl=NULL;
        public $documentRoot=NULL;
        public $identity=NULL;
        public $themeName=NULL;        
        public $documentRootUpload=NULL;       
                
        function __construct() 
        {
            $Config=Core::getSiteConfig(); 
            
            $this->websiteUrl=$Config['websitehost'];
            $this->websiteAdminUrl=$Config['websitehostadmin'];
            $this->documentRoot=$Config['documentroot'];
            $this->identity=$Config['identity'];
            $this->themeName=$Config['theme'];
            $this->rpp=$Config['rpp'];
            $this->documentRootUpload="uploads/".$this->identity;
        }
    }
?>
