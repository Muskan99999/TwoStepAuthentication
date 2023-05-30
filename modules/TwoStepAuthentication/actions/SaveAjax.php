<?php

/*+**********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.1
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ************************************************************************************/

class TwoStepAuthentication_SaveAjax_Action extends Vtiger_Action_Controller {

	const tableName = 'vtiger_google_secret_keys';
	
	function checkPermission(Vtiger_Request $request) {
		return;
	}

    public function process(Vtiger_Request $request) {
       
    	
    	$user_id = $request->get('user_id');
    	
    	$code = $request->get('google_code');
    	
    	$verification_code = $request->get('verification_code');
    	
        $response = new Vtiger_Response();
        try{
            
            require_once 'modules/TwoStepAuthentication/googleLib/GoogleAuthenticator.php';
            $ga = new GoogleAuthenticator();
            $checkResult = $ga->verifyCode($code, $verification_code, 2);
            
            if($checkResult){
                
                $id ='';
                $db = PearDatabase::getInstance();
                
                $query = 'SELECT * FROM '.self::tableName.' WHERE  user_id= ?';
                
                $params = array( $user_id);
                
                $result = $db->pquery($query,$params);
                
                if(!$db->num_rows($result)) {
                    $id = $db->getUniqueID(self::tableName);
                    $query = 'INSERT INTO '.self::tableName.' VALUES(?,?,?)';
                    $db->pquery($query,array($id, $user_id, $code));
                }else{
                    $id = $db->query_result($result, 0, "id");
                    $query = 'UPDATE '.self::tableName.' SET secret_key = ? WHERE user_id = ?';
                    $db->pquery($query,array($code , $user_id));
                }
                
                $_SESSION['2FA'] = true;
                $response->setResult(array('success'=>true,'id'=>$id));
                
            }else{
                $response->setResult(array('success'=>false,'verification'=>true));
            }
            
        }catch(Exception $e) {
            $response->setError($e->getCode(), $e->getMessage());
        }
        $response->emit();
    }
    
   
}