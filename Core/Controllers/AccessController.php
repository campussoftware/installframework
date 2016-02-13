<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AccessController
 *
 * @author ramesh
 */
class Core_Controllers_AccessController 
{
    //put your code here
    public function NodeCheckForProfile($nodeRelations) 
    {
        $session=new Core_Session();
        $sessionData=$session->getSessionMaganager();        
        $np = new Core_Model_NodeProperties();
        $np->setNode($nodeName);
        $nodeStructureData=$np->getCurrentProfilePermission($sessionData['profile_id']);
        $output=array();
        if($nodeRelations!="")
        {
            if(!Core::isArray($nodeRelations))
            {
                $nodeRelations=array($nodeRelations);
            }
            foreach ($nodeRelations as $key => $data) 
            {
                if(Core::keyInArray($key, $nodeStructureData))
                {
                    $output[$key]=$data;
                }
            }
            
        }
        return $output;        
    }
}
