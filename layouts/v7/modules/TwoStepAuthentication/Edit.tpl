{*<!--
/*********************************************************************************
** The contents of this file are subject to the vtiger CRM Public License Version 1.0
 * ("License"); You may not use this file except in compliance with the License
 * The Original Code is:  vtiger CRM Open Source
 * The Initial Developer of the Original Code is vtiger.
 * Portions created by vtiger are Copyright (C) vtiger.
 * All Rights Reserved.
 ********************************************************************************/
-->*}
{strip}
	<div id="massEditContainer" class="modal-dialog modal-lg modelContainer">
		{assign var=HEADER_TITLE value={vtranslate('Two Step Authentication', $MODULE)}}
		
		{include file="ModalHeader.tpl"|vtemplate_path:$MODULE TITLE=$HEADER_TITLE}
		<div class="modal-content">
			<form class="form-horizontal" id="2faServerForm" name="2faServerForm" method="post" action="index.php">
				<input type="hidden" name="module" value="{$MODULE}" />
				
				<input type="hidden" name="user_id" value="{$CURRENT_USER_MODEL->get('id')}" />
				<input type="hidden" name="google_code" value="{$CODE}"/>
			
				<div class="hide errorMessage">
					<div class="alert alert-danger">
						{vtranslate('Error', $MODULE)}<strong>{vtranslate('Error', $MODULE)}</strong>
					</div>
				</div>
				
				<div class="hide errorInvalidCode">
					<div class="alert alert-danger">
						{vtranslate('Error :', $MODULE)}&nbsp;<strong>{vtranslate('Invalid Code', $MODULE)}</strong>
					</div>
				</div>
				
				<div name='massEditContent'>
					<div class="modal-body ">
					
		
						<div style="float:left;width: 30%;height:400px">
							<div class="form-group text-center">
							 	<img src='{$QRCODE}'/>
							 </div>
							 <div class="form-group text-center">
							 	<label class="control-label">{vtranslate('Secret Code', $MODULE)}:</label>
							 </div>
							 <div class="form-group text-center">
			                	<input class="inputElement" type="text" readonly value="{$CODE}" style="width: 160px;"/>
			                </div>
						</div>
						
						<div>
							<h5> <strong> {vtranslate('Step 1', $MODULE)} </strong> </h5>
							
							<p> {vtranslate('Install the following app in your mobile', $MODULE)} </p>
							
							<ul>
								{*<li style="margin-left: 30% !important;font-weight: bold;">Authy</li>*}
								<li style="margin-left: 30% !important;font-weight: bold;">Google Authenticator</li>
							</ul>
							
							<br>
							
							<h5> <strong> {vtranslate('Step 2', $MODULE)} </strong> </h5>
							
							<p> {vtranslate('Open app then scan QR code or enter secret code', $MODULE)} </p>
							
							</br>
							
							<h5> <strong> {vtranslate('Step 3', $MODULE)} </strong> </h5>

							<p>{vtranslate('Get code and put in the following field and then click on the button to verify', $MODULE)} </p>
							
							<div class="row">
								{if $BUTTON_NAME neq null}
					                {assign var=BUTTON_LABEL value=$BUTTON_NAME}
					            {else}
					                {assign var=BUTTON_LABEL value={vtranslate('Verify', $MODULE)}}
					            {/if}
				                <div class="controls col-sm-5" style="padding-left: 0px !important;">
				                	<input type="text" class="inputElement" required name="verification_code" style="width: 50%;"/>
				                	&nbsp;
    					           	<button {if $BUTTON_ID neq null} id="{$BUTTON_ID}" {/if} class="btn btn-primary" type="submit" name="saveButton"><strong>{$BUTTON_LABEL}</strong></button>
				                </div>
							</div>
							
						</div>
					</div>
				</div>
				<div class="modal-footer ">
			        <center>
			            <a href="#" class="cancelLink" type="reset" data-dismiss="modal">{vtranslate('LBL_CANCEL', $MODULE)}</a>
			        </center>
				</div>
			</form>
		</div>
	</div>
{/strip}

