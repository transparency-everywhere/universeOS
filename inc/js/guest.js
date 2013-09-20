
        //proof registration
        function checkReg(){
                var valuee;
                var check;
                var checkBox;
                var passwordCheck;
                $(".checkReg").each(function() {
                    valuee = $(this).val();
                        if(valuee === ""){
                            check = "FALSE";
                        }else{
                            if(check === ""){
                            check = "TRUE";
                            }
                        }
                });
                $(".checkRegBox").each(function() {
                    checkBox = $(this).is(':checked');
                        if(checkBox){
                            checkBox = "TRUE";
                        }else{
                            checkBox = "FALSE";
                        }
                });
                if($("#registration #password").val() != $("#registration #passwordRepeat").val()){
                    passwordCheck = "FALSE";
                }

                    if(check == "FALSE" || passwordCheck === "FALSE" || checkBox === "FALSE"){
                        if(check == "FALSE"){
                        alert("Please fill out all the fields");
                        }
                        if(checkBox == "FALSE"){
                        alert("Your have to accept our terms.");
                        }
                        if(passwordCheck == "FALSE"){
                        alert("Your passwords dont match.");
                        }
                    }else{
                        processRegistration()
                        //$("#regForm").submit();
                    }
        }
function checkUsername(id){
	
	$('.captchaContainer').slideDown('slow');
	
    var username = $("#"+id).val();
    if(/^[a-zA-Z0-9- ]*$/.test(username) == false) {
        $('#checkUsernameStatus').html('<a style="color: red">&nbsp;contains illegal characters</a>');
    }else if(username.length > 2){
        
                //check server for new messages
                $.post("api.php?action=checkUsername", {
                       username:username
                       }, function(result){
                            var res = result;
                            if(res == "1"){
                                //load checked message
                                $('#checkUsernameStatus').html('<a style="color: green">&nbsp;succes!</a>');
                            }else{
                                $('#checkUsernameStatus').html('<a style="color: red">&nbsp;already in use</a>');
                            }
                       }, "html");
                
               
        
        
    }else{
        //html to short
        $('#checkUsernameStatus').html('<o style="color: red">&nbsp;to short</o>');
    }
							       $("#"+id).keyup(function() {
									    delay(function(){
									      checkUsername(id);
									    }, 500 );
									});
}

function processRegistration(){
    var username = $("#regUsername").val();
    var password = $("#registration #password").val();
    var captcha = $("#captcha").val();
                //submit registration
                $.post("api.php?action=processSiteRegistration", {
                       username:username,
                       password:password,
                       captcha:captcha
                       }, function(result){
                            var res = result;
                            if(res == 1){
                                //load checked message
                                jsAlert('','You just joined the universeOS');
                                $('#registration').slideUp('');
                                
                                $('#loginUsername').val(username);
                                $('#loginPassword').val(password);
                                $('#loginForm').submit();
                            }else{
                                alert(res);
                            }
                       }, "html");

}
