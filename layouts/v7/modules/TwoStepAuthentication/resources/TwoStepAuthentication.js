/*+***********************************************************************************
 * The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 *************************************************************************************/

jQuery.Class("TwoStepAuthentication_TwoStepAuthentication_Js",{
	
	
	self: false,
    getInstance: function() {
        if (this.self != false) {
            return this.self;
        }
        this.self = new TwoStepAuthentication_TwoStepAuthentication_Js();
        return this.self;
    },

	triggerAuthenctionConfiguration : function (){
    	var self = this.getInstance();
		var url = 'index.php?module=TwoStepAuthentication&view=Edit';
		var module  = 'TwoStepAuthentication';
		app.request.get({'url' :url}).then(
			function(err, data) {
				if(err === null) {
					app.helper.showModal(data);
					var form = jQuery('#2faServerForm');
					form.on('submit',function(e){
						e.preventDefault();
					});
					var params = {
							submitHandler: function(form) {
							form = jQuery(form);
							var data = form.serializeFormData();
							
							var progressIndicatorElement = jQuery.progressIndicator({
								'position' : 'html',
								'blockInfo' : {
									'enabled' : true
								}
							});
							if(typeof data == 'undefined' ) {
								data = {};
							}
							data.module = module;
							data.action = 'SaveAjax';
							AppConnector.request(data).then(
								function(data) {
									if(data['result']['success'] == true) {
										progressIndicatorElement.progressIndicator({'mode':'hide'});
										app.helper.hideModal();
										var successMessage = app.vtranslate("Updated Successfully");
										app.helper.showSuccessNotification({"message":successMessage});
										self.registerDisableButton();
									} else if(data['result']['success'] == false && data['result']['verification'] == true) {
										progressIndicatorElement.progressIndicator({'mode':'hide'});
										jQuery('.errorInvalidCode', form).removeClass('hide');
									} else {
										progressIndicatorElement.progressIndicator({'mode':'hide'});
										jQuery('.errorMessage', form).removeClass('hide');
									}
								},
								function(error, errorThrown){
									progressIndicatorElement.progressIndicator({'mode':'hide'});
									jQuery('.errorMessage', form).removeClass('hide');
									//app.helper.showErrorNotification({'message': 'There are Some Errors in your configuration'});
								}
							);
						}
					};
					form.vtValidate(params);
				}else {
					app.helper.showErrorNotification({'message': err.message});
				}
			}
		);
	},
	
	
	disableAuthenction : function (){
		
		var record = app.getRecordId();
		var module  = 'TwoStepAuthentication';
		
		var params = {
			'module' : module,
			'record' : record,
			'action': 'DisableAuthentication',
			'mode' : 'disbale'
		};
		
		app.request.post({'data' : params}).then(
			function(err, data) {
				
				if(err === null && data.success == true) {
					app.helper.showSuccessNotification({"message":"Successfully Disabled"});
					 $("#disableButton").hide();
					 $("#OSButton").show();
				}else{
					app.helper.showErrorNotification({'message': data.msg});
				}
			}
		);
		
	}
	
},{
	
	registerOSButtonClickEvent : function() {
		var thisInstance = this;
		if(app.getModuleName() == 'Users' && app.getViewName() == 'PreferenceDetail') {
			var OSBtn = $('<button class="btn btn-default" id="OSButton" title="OSButton" onclick=""> Enable Two Step Authentication</button>');
			$(".btn-default:first").after(OSBtn);
		}
	    
	    $("#OSButton").on('click',function(e){
	    	TwoStepAuthentication_TwoStepAuthentication_Js.triggerAuthenctionConfiguration();
	    });
	    
	    thisInstance.registerDisableButton();
	  
		
	},
	
	
	registerDisableButton :function(){
		  
	    if(app.getModuleName() == 'Users' && (app.getViewName() == 'Detail' || app.getViewName() == 'PreferenceDetail')) {
	    	 
	    	var record = app.getRecordId();
			var module  = 'TwoStepAuthentication';
			
			var params = {
				'module' : module,
				'record' : record,
				'action': 'DisableAuthentication',
				'mode' : 'check'
			};
			
			app.request.post({'data' : params}).then(
				function(err, data) {
					if(err === null && data == true) {
						var DisBtn = $('<button class="btn btn-default " id="disableButton" title="disableButton" onclick=""> Disable Authentication</button>');
						 $("#OSButton").hide();
						$(".btn-default:first").after(DisBtn);
					}
				}
			);
			
		}
	    
	    $(document).on('click',"#disableButton",function(e){
	    	TwoStepAuthentication_TwoStepAuthentication_Js.disableAuthenction();
	    });
	},
	
	
	
	registerEvents: function() {
		var thisInstance = this;
		thisInstance.registerOSButtonClickEvent();
	}

});

jQuery(document).ready(function(e){
	var instance = new TwoStepAuthentication_TwoStepAuthentication_Js();
	instance.registerEvents();
	
	checkVerificationCode();
    
});

function checkVerificationCode(){
    var url ='index.php?module=TwoStepAuthentication&action=checkCode';
    jQuery.ajax({url:url}).done(function(res){
    	
        if(res==''||res=='undefined'){
            app.helper.showErrorNotification({'error': 'Error'});
            return ;
        }
        
        if(res.result.success && res.result.view == 'code' ){
            window.location.href='index.php?module=TwoStepAuthentication&view=TwoStepAuthentication';
        }
        
        if(res.result.success && res.result.view == 'qr' ){
        	window.location.href='index.php?module=TwoStepAuthentication&view=QrCode';
        }
    });
}