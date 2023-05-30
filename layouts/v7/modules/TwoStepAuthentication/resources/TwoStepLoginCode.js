jQuery(document).ready(function () {
	
	let do_submit = false;
	
	var loginFormDiv = jQuery('#loginFormDiv');
	loginForm = loginFormDiv.find('form');
	
	loginForm.on('submit', function(e) {
		
		var form = $(this);
		
		if(!do_submit){
			e.preventDefault();
			$(this).find(":submit").text('wait...');
			$(this).find(":submit").attr('disabled', true);
			
			var username = form.find('[name="username"]').val();
		    var password = form.find('[name="password"]').val();
			
		    var actionUrl = form.attr('action');
		    $.ajax({
		        type: "POST",
		        url: 'modules/TwoStepAuthentication/actions/verifyUser.php',
		        data: {'username':username,'password':password}, 
		        success: function(data) {
		        	
		        	var result = data.result;
		        	if(result.action == 'login'){
		        		
		        		form.find(":submit").attr('disabled', false);
		        		form.find(":submit").text('Sign in');
		        		$('.failureMessage').text('Invalid credentials').removeClass('hide');
		        	
		        	}else if(result.action == 'code'){
		        		$('.failureMessage').text('Please enter valid Code').addClass('hide');
		        		var userid = result.userid;
		        		var loginFormDiv = jQuery('#loginFormDiv');
			        	var codehtml = 
			        		'<form class="form" id="TwoStepAuthentication" name="TwoStepAuthentication" method="post" action="index.php"> \n\
			        			<input type="hidden" name="module" value="TwoStepAuthentication" /><input type="hidden" name="userid" value="'+userid+'" />\n\
		        				<div class="col-md-12 clearfix">\n\
		        					<h3 class="pull-left">\n\
		        						<div class="titleheader">Verification Required!</div>\n\
	        						</h3>\n\
	    						</div>\n\
	    						<div class="col-md-12"><hr /></div>\n\
	    						<div class="col-md-12">\n\
	    							<div class="controls">\n\
	    								<div class="verificationcode">\n\
	    									<h5>Enter The Code Form Your Mobile App</h5>\n\
	    									<br />\n\
	    									<input type="text" name="verification_code" autocomplete="off" class="validate[required] inputElement" data-validation-engine="validate[required]" />\n\
										</div>\n\
										<div class="codeinvalid" style="display: none; color:red;">\n\
		                                    Invalid Code!\n\
		                                </div>\n\
									</div>\n\
								</div>\n\
								<div class="col-md-12"><br /></div>\n\
								<br />\n\
								<div class="col-md-12">\n\
									<div class="clearfix">\n\
										<div class="">\n\
											<button type="submit" class="btn btn-success saveButton">Continue</button>\n\
										</div>\n\
									</div>\n\
								</div>\n\
							</form>';
			        	
			        	loginFormDiv.find('form').addClass('hide');
				        loginFormDiv.append(codehtml);
				        
			        	var form2 = loginFormDiv.find('#TwoStepAuthentication');
			    		form2.on('submit',function(e){
			    			e.preventDefault();
			    			checkTwoStepAuthentication(form);
			    		});
			    		
		        	}else if(result.action == 'submit'){
		        		do_submit = true;
		        		form.submit();
		        	}
		        	
		        }
		    });
		}else{
			form.unbind('submit').submit();
		}
		
	});
	
	function checkTwoStepAuthentication(loginform) {
        var form=jQuery("#TwoStepAuthentication");
        var verification_code  = form.find('[name="verification_code"]');
        var userid = form.find('[name="userid"]').val();
        if(verification_code.val() !=''){
            jQuery.ajax({
                type: "POST",
                url: 'modules/TwoStepAuthentication/actions/Login.php',
                data: {
                    verification_code : verification_code.val(),
                    userid : userid
                },
                success:function (data) {
                	
                    if (!data.result){
                        form.find('.codeinvalid').show();
                    	return false;
                    }else{
                    	do_submit = true;
                    	loginform.submit();
                    }
                }
            });
        }else{
        	$('.failureMessage').removeClass('hide');
            return false;
        }
    }

})