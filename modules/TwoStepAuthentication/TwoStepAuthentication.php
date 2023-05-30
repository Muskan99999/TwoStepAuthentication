<?php
class TwoStepAuthentication {
	
	function vtlib_handler($modulename, $event_type) {
		if($event_type == 'module.postinstall') {

			$this->addLinksFor2FA();
			$this->appendJSPath();
			
		} else if($event_type == 'module.disabled') {

			$this->removeLinksFor2FA();
			$this->removeJSPath();
			
		} else if($event_type == 'module.enabled') {
			
			$this->addLinksFor2FA();
			$this->appendJSPath();
			
		} else if($event_type == 'module.preuninstall') {

			$this->removeLinksFor2FA();
		
		} else if($event_type == 'module.preupdate') {
			// TODO Handle actions before this module is updated.
		} else if($event_type == 'module.postupdate') {
			// TODO Handle actions after this module is updated.
			$this->addLinksFor2FA();
			$this->appendJSPath();
		}
	}
 	
 	/**
     * To add a link in vtiger_links which is to load our UserMailConfigs.js 
     */
     function addLinksFor2FA() {
     		
     	global $adb;
    	global $vtiger_current_version;

		if (version_compare($vtiger_current_version, '7.0.0', '<')) {
			$template_folder = 'layouts/vlayout';
		}
		else {
			$template_folder = 'layouts/v7';
		}
        
     	$linkurl=$template_folder.'/modules/TwoStepAuthentication/resources/TwoStepAuthentication.js';
     	
        $result = $adb->pquery("select * from vtiger_links where linkurl = ?",array($linkurl));

        if(!$adb->num_rows($result)){
        
        	Vtiger_Link::addLink(0, 'HEADERSCRIPT', 'TwoStepAuthentication', 
        	$template_folder.'/modules/TwoStepAuthentication/resources/TwoStepAuthentication.js', '', '0', '', '', '');
			
        }
       	
        $adb->pquery("CREATE TABLE  IF NOT EXISTS vtiger_google_secret_keys ( id INT(19) NOT NULL AUTO_INCREMENT , 
        user_id INT(19) NULL , secret_key VARCHAR(250) NULL , PRIMARY KEY (id))");
     
     }
 
  
    function removeLinksFor2FA() {
        
    	global $adb;
    	global $log;
		global $vtiger_current_version;

		if (version_compare($vtiger_current_version, '7.0.0', '<')) {
			$template_folder = 'layouts/vlayout';
		}
		else {
			$template_folder = 'layouts/v7';
		}
        
    	Vtiger_Link::deleteLink(0, 'HEADERSCRIPT', 'TwoStepAuthentication',
    	$template_folder.'/modules/TwoStepAuthentication/resources/TwoStepAuthentication.js');
    	
	}
    
	
	function appendJSPath(){
	    $myfile = fopen("layouts/v7/modules/Users/Login.tpl", "a") or die("Unable to open file!");
	    $txt = '<script src="layouts/v7/modules/TwoStepAuthentication/resources/TwoStepLoginCode.js"></script>';
	    fwrite($myfile, "\n". $txt);
	    fclose($myfile);
	}
	
	function removeJSPath(){
	    $txt = '<script src="layouts/v7/modules/TwoStepAuthentication/resources/TwoStepLoginCode.js"></script>';
	    $contents = file_get_contents('layouts/v7/modules/Users/Login.tpl');
	    $contents = str_replace($txt, '', $contents);
	    file_put_contents('layouts/v7/modules/Users/Login.tpl', $contents);
	}
	
}