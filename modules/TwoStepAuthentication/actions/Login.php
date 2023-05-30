<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

chdir(dirname(__FILE__). '/../../../');

include_once 'includes/main/WebUI.php';

class TwoStepAuthentication_Login_Action {

    function process($data) {
        
        $adb = PearDatabase::getInstance();
        
		$userId = $data['userid'];

		$code = $data['verification_code'];
		
		$response = new Vtiger_Response();
		
		$query = $adb->query("SELECT * FROM vtiger_google_secret_keys WHERE user_id= ".$userId);
		$secret='';
		if($adb->num_rows($query)){
			$secret = $adb->query_result($query,0,'secret_key');
			if($secret){
				require_once 'modules/TwoStepAuthentication/googleLib/GoogleAuthenticator.php';
				$ga = new GoogleAuthenticator();
				$checkResult = $ga->verifyCode($secret, $code, 2); 
				
				if($checkResult){
				    session_start();
					$_SESSION['2FA'] = true;
					$result = true;
					
				}else{
					$result = false;
				}
				
			}else{
				$result = false;
			}

		}else{
			$result = false;
		}
	 
		$response->setResult($result);	
		
		$response->emit();
	
	}

}

$verifyUserAction = new TwoStepAuthentication_Login_Action();
$verifyUserAction->process($_REQUEST);

