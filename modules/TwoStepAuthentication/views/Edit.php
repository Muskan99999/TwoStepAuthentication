<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class TwoStepAuthentication_Edit_View extends Vtiger_Index_View {
	
	public function checkPermission(Vtiger_Request $request) {
		return true;
	}
	
    public function process(Vtiger_Request $request) {
    	
    	global $adb;
		global $site_URL;
        $viewer = $this->getViewer($request);
        $moduleName = $request->getModule();
		
        $user = Users_Record_Model::getCurrentUserModel();
        
        require_once 'modules/TwoStepAuthentication/googleLib/GoogleAuthenticator.php';

        $email = $user->get('email1');
        
		$ga = new GoogleAuthenticator();
		
		$query = $adb->query("SELECT * FROM vtiger_google_secret_keys WHERE user_id= ".$user->get('id'));
		
		if($adb->num_rows($query)){
			$secret = $adb->query_result($query,0,'secret_key');
		}else{
			$secret = $ga->createSecret();
		}
		
		$qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret, $site_URL);
      
       	$viewer->assign('QRCODE',$qrCodeUrl);
        $viewer->assign('CODE',$secret);
		$viewer->assign('MODULE', $moduleName);
		$viewer->assign('CURRENT_USER_MODEL', $user);
        $viewer->view('Edit.tpl',$moduleName);
        
    }
		
	function getPageTitle(Vtiger_Request $request) {
		$qualifiedModuleName = $request->getModule(false);
		return vtranslate('Two Step Authentication',$qualifiedModuleName);
	}
	
	
	
}