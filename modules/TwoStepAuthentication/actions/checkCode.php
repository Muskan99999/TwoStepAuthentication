<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class TwoStepAuthentication_checkCode_Action extends Vtiger_Action_Controller {

	
	function checkPermission(Vtiger_Request $request) {
		return true;
	} 

	function process(Vtiger_Request $request) {

		global $adb,$current_user;
		
		$userid = $current_user->id;
		$response = new Vtiger_Response();	
		$result='';
		
		if(isset($_SESSION['2FA'])){
	        $result = array('success'=>false);
	    }else{
	        
	        $query = $adb->query("SELECT * FROM vtiger_google_secret_keys WHERE user_id= ".$userid);
	        if($adb->num_rows($query)){
	            
	            $result = array('success'=>true,'view'=>'code');
	            
	        }
	    }
		
		$response->setResult($result);	
		
	 	$response->emit();
	}

}
