<?php
/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class TwoStepAuthentication_DisableAuthentication_Action extends Vtiger_Action_Controller {

	function __construct() {
		parent::__construct();
		$this->exposeMethod('disbale');
		$this->exposeMethod('check');
		
	}
	
	function checkPermission(Vtiger_Request $request) {
		return true;
	} 

	function process(Vtiger_Request $request) {

		$mode = $request->getMode();
		if(!empty($mode)) {
			echo $this->invokeExposedMethod($mode, $request);
			return;
		}
		
	}
	
	public function disbale(Vtiger_Request $request) {
		
		global $adb,$current_user;
		
		$record = $request->get('record');
		
		$response = new Vtiger_Response();	
		
		$currentUser = Users_Record_Model::getCurrentUserModel();
		
		$result = '';
		
		if($record){
			if($currentUser->isAdminUser()){
				$query = $adb->pquery("DELETE FROM vtiger_google_secret_keys WHERE user_id = ?",array($record));
				
				if($query){
					$result = array('success'=>true);
				}else{
					$result = array("success"=>false,'msg'=>'Try Again');
				}
				
			}else{
				
				$query = $adb->pquery("DELETE FROM vtiger_google_secret_keys WHERE user_id = ?",array($record));
				
				if($query){
					$result =array('success'=>true);
				}else{
					$result =array("success"=>false,'msg'=>'Try Again');
				}
			}
		}
		
		$response->setResult($result);	
		
	 	$response->emit();
		
	}
	
	
	public function check(Vtiger_Request $request) {
		
		global $adb,$current_user;
		
		$record = $request->get('record');
		
		$response = new Vtiger_Response();	
		
		$result = '';
		
		if($record){
			$query = $adb->pquery("SELECT * FROM vtiger_google_secret_keys WHERE user_id = ?",array($record));
			
			if($adb->num_rows($query)){
				$result = true;
			}else{
				$result = false;
			}
		}
		
		$response->setResult($result);	
		
	 	$response->emit();
		
	}

}
