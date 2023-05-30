{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
* ("License"); You may not use this file except in compliance with the License
* The Original Code is:  vtiger CRM Open Source
* The Initial Developer of the Original Code is vtiger.
* Portions created by vtiger are Copyright (C) vtiger.
* All Rights Reserved.
*
********************************************************************************/
-->*}
{strip}
    <!DOCTYPE html>
    <html>
    <head>
        <title>Vtiger</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <link REL="SHORTCUT ICON" HREF="layouts/v7/skins/images/favicon.ico">
        <link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="resources/styles.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="libraries/jquery/select2/select2.css" />
        <script type="text/javascript" src="libraries/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="libraries/jquery/jquery.class.min.js"></script>
        <link rel="stylesheet" href="libraries/jquery/posabsolute-jQuery-Validation-Engine/css/validationEngine.jquery.css" />
        <script type="text/javascript" src="libraries/jquery/posabsolute-jQuery-Validation-Engine/js/jquery.validationEngine.js" ></script>
        <script type="text/javascript" src="libraries/jquery/posabsolute-jQuery-Validation-Engine/js/languages/jquery.validationEngine-en.js?v=6.3.0"></script>

        <style type="text/css">{literal}
            body { background: lightsteelblue; background-size: 100%; font-size: 14px; }
            .modal-backdrop { opacity: 0.35; }
            .tooltip { z-index: 1055; }
            input, select, textarea { font-size: 14px; }
            {/literal}</style>
    </head>
    <body>

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-backdrop"></div>
            <form class="form" id="QRAuthentication" name="QRAuthentication" method="post" action="index.php">
           		<input type="hidden" name="module" value="{$MODULE}" />
				
				<input type="hidden" name="user_id" value="{$CURRENT_USER_MODEL->id}" />
				<input type="hidden" name="google_code" value="{$CODE}"/>
				
				<div class="hide errorMessage">
					<div class="alert alert-danger">
						{vtranslate('Error', $MODULE)}<strong>{vtranslate('Error', $MODULE)}</strong>
					</div>
				</div>
				
                <div class="modal" style="width: 500px">
                    <div class="modal-header">
                        <div class="clearfix">
                            <div class="pull-right signout">
                                <a id="loginhistory_LBL_SIGN_OUT" href="index.php?module=Users&action=Logout">Sign Out</a>
                            </div>
                            <h5 class="pull-left"><div class="titleheader"> Scan this QR code with the Google authenticator app or similar apps like Google auth, Google authenticator, Authy</div></h5>
                        </div>
                    </div>
                    
                    <div class="modal-body">
                        <div class="control-group">
                            <div class="col-lg-8">
                                <div class="controls">
                                     <div class="form-group" style="text-align: center;">
									 	<img src='{$QRCODE}'/>
									 </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                         <center>
				            {assign var=BUTTON_LABEL value={vtranslate('Done', $MODULE)}}
				            <button {if $BUTTON_ID neq null} id="{$BUTTON_ID}" {/if} class="btn btn-success" type="submit" name="saveButton"><strong>{$BUTTON_LABEL}</strong></button>
                         </center>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {literal}
    <script>
      var form = jQuery('#QRAuthentication');
		form.on('submit',function(e){
			e.preventDefault();
			QRAuthentication();
		});
       
        function QRAuthentication() {
            var form=jQuery("#QRAuthentication");
            var google_code  = form.find('[name="google_code"]');
            var user_id = form.find('[name="user_id"]').val();
            if(google_code.val() !=''){
                jQuery.ajax({
                    type: "POST",
                    url: "index.php",
                    data: {
                        module: "TwoStepAuthentication",
                        action: "SaveAjax",
                        google_code : google_code.val(),
                        user_id : user_id
                    },
                    success:function (data) {
                    	
                        if (!data.result.success){
                            form.find('.errorMessage').show();
                        	return false;
                        }else{
                        	$(".modal-backdrop").hide();
                            $(".modal").hide();
                           
                            setTimeout(function(){
                                var currentUrl=window.location.href;
                                window.location= currentUrl.replace('index.php?module=TwoStepAuthentication&view=QrCode','');
                            }, 100);
                        }
                    }
                });
            }
        }
       
    </script>
    {/literal}
    </body>
    </html>
{/strip}
