<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Setup
 *
 * @author ramesh
 */
class Core_Install_Setup
{
    //put your code here
    function __construct() 
    {
        $this->schemaSetup();        
    }
    public function schemaSetup()
    {
		try
		{
			$cp=new Core_CodeProcess();
			$files=$cp->dirToArray("Config"); 
			if(Core::countArray($files)>0)
			{
				foreach ($files as $folder=>$file)
				{
					foreach ($file as $tempfile)
					{
						$configFile="Config/".$folder."/".$tempfile;
						$configFileContent=Core::getFileContent($configFile);
						$configFileContentSettings=Core::convertXmlToArray($configFileContent);
						if($configFileContentSettings)
						{
							if(trim($configFileContentSettings['setuppath'])!="")
							{
								$setuppath=$configFileContentSettings['setuppath']."/SchemaInstall";
								$className=str_replace("/", "_", $setuppath);
								$setup=new $className();
							}
							if($configFileContentSettings['datapath'])
							{
								$setuppath=$configFileContentSettings['datapath']."/DataInstall";
								$className=str_replace("/", "_", $setuppath);
								$setup=new $className();
							}
							
						}                   
					}                
				}        
			}
			Core::createFile("mode.flag",1,"prod");
		}
		catch(Exception $ex)
		{
			Core::Log(__METHOD__."::".$ex->getMessage(),"installexception.log");
		}
		
    }
}
