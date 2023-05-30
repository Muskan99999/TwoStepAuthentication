<?php


	class TwoStepAuthentication_TwoStepAuthentication_View extends Vtiger_Index_View {
		
		public function preProcess(Vtiger_Request $request){
			return true;
		}
		
		
		public function process(Vtiger_Request $request){
			
			
			global $adb;
			global $current_user;
			
			$moduleName = $request->get('module');
			$userId = $current_user->id;
			
			$viewer = $this->getViewer($request);
			
			$viewer->assign('USERID', $userId);
			$viewer->assign('MODULE', $moduleName);
			$viewer->assign('CURRENT_USER_MODEL', $current_user);
	        $viewer->view('TwoStepAuthentication.tpl',$moduleName);
		}
		
		public function postProcess(Vtiger_Request $request){
			return true;
		}
	}