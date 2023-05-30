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
        <link rel="stylesheet" href="libraries/bootstrap/css/bootstrap.css" type="text/css" media="screen" />
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
            <form class="form" id="TwoStepAuthentication" name="TwoStepAuthentication" method="post" action="index.php">
                <input type="hidden" name="module" value="{$MODULE}" />
                <input type="hidden" name="userid" value="{$USERID}" />
                <div class="modal" style="width: 500px">
                    <div class="modal-header">
                        <div class="clearfix">
                            <div class="pull-right signout">
                                <a id="loginhistory_LBL_SIGN_OUT" href="index.php?module=Users&action=Logout">Sign Out</a>
                            </div>
                            <h3 class="pull-left"><div class="titleheader"> Verification Required!</div></h3>
                        </div>
                    </div>
                    <div class="modal-body">
                        <div class="control-group">
                            <div class="col-lg-8">
                                <div class="controls">
                                    
                                    <div class="verificationcode">
                                        {vtranslate('Enter The Code Form Your Mobile App', $MODULE)}<br><br>
                                        <input type="text" name="verification_code" data-validation-engine="validate[required]" class="validate[required]" autocomplete="off"/>
                                    </div>
                                    <div class="codeinvalid" style="display: none; color:red;">
                                        Invalid Code!
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <div class="pull-right">
                            <button class="btn btn-success" type="submit" name="saveButton"><strong>{vtranslate('Continue', $MODULE)}</strong></button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    {literal}
    <script>
      	
      	var form = jQuery('#TwoStepAuthentication');
		form.on('submit',function(e){
			e.preventDefault();
			checkTwoStepAuthentication();
		});
       
        function checkTwoStepAuthentication() {
            var form=jQuery("#TwoStepAuthentication");
            var verification_code  = form.find('[name="verification_code"]');
            var userid = form.find('[name="userid"]').val();
            if(verification_code.val() !=''){
                jQuery.ajax({
                    type: "POST",
                    url: "index.php",
                    data: {
                        module: "TwoStepAuthentication",
                        action: "Login",
                        verification_code : verification_code.val(),
                        userid : userid
                    },
                    success:function (data) {
                    	
                        if (!data.result){
                            form.find('.codeinvalid').show();
                        	return false;
                        }else{
                        	$(".modal-backdrop").hide();
                            $(".modal").hide();
                           
                            setTimeout(function(){
                                var currentUrl=window.location.href;
                                window.location= currentUrl.replace('index.php?module=TwoStepAuthentication&view=TwoStepAuthentication','');
                            }, 100);
                        }
                    }
                });
            }else{
                verification_code.validationEngine('showPrompt', 'Enter your code' , 'error','topLeft',true);
                return false;
            }
        }
       
    </script>
    {/literal}
    </body>
    </html>
{/strip}
