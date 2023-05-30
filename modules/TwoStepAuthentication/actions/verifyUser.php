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

class TwoStepAuthentication_verifyUser_Action {
    
    function process($data) {
        
        $adb = PearDatabase::getInstance();
        
        $username = $data['username'];
        $password = $data['password'];
        
        $user = CRMEntity::getInstance('Users');
        $user->column_fields['user_name'] = $username;
        
        $userid = '';
        if ($user->doLogin($password)) {
            
            $userid = $user->retrieve_user_id($username);
            
            if(isset($_SESSION['2FA'])){
                $result = array('success'=>true,'action'=>'submit');
            }else{
                $query = $adb->query("SELECT * FROM vtiger_google_secret_keys WHERE user_id= ".$userid);
                if($adb->num_rows($query)){
                    
                    $result = array('success'=>true,'action'=>'code','userid'=>$userid);
                    
                }else{
                    
                    $result = array('success'=>true,'action'=>'submit');
                }
            }
        }else{
            $result = array('success'=>true, 'action'=>'login');
        }
        
        $response = new Vtiger_Response();
        $response->setResult($result);
        $response->emit();
    }
    
}

$verifyUserAction = new TwoStepAuthentication_verifyUser_Action();
$verifyUserAction->process($_REQUEST);

