<?php


	class TwoStepAuthentication_QrCode_View extends Vtiger_Index_View {
		
		public function preProcess(Vtiger_Request $request){
			return true;
		}
		
		
		public function process(Vtiger_Request $request){
			
			
			global $adb;
			global $site_URL;
			global $current_user;
			
			$moduleName = $request->get('module');
			$userId = $current_user->id;
			
			$user = $current_user;
	        
	        require_once 'modules/TwoStepAuthentication/googleLib/GoogleAuthenticator.php';
	
	        $email = $user->email1;
	        
			$ga = new GoogleAuthenticator();
			
			$query = $adb->query("SELECT * FROM vtiger_google_secret_keys WHERE user_id= ".$user->id);
			
			if($adb->num_rows($query)){
				$secret = $adb->query_result($query,0,'secret_key');
			}else{
				$secret = $ga->createSecret();
			}
			
			$qrCodeUrl = $ga->getQRCodeGoogleUrl($email, $secret, $site_URL);
		
			$viewer = $this->getViewer($request);
			
		    $viewer->assign('QRCODE',$qrCodeUrl);
	        $viewer->assign('CODE',$secret);
	        $viewer->assign('USERID', $userId);
			$viewer->assign('MODULE', $moduleName);
			$viewer->assign('CURRENT_USER_MODEL', $current_user);
	        $viewer->view('QrCode.tpl',$moduleName);
		}
		
		public function postProcess(Vtiger_Request $request){
			return true;
		}
	}