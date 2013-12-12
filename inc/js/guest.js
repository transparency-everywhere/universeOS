
        //proof registration
        function checkReg(){
                var valuee;
                var check;
                var usernameLengthCheck;
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
                
                if($('#regUsername').val().length < 3){
                	usernameLengthCheck = 'FALSE';
                }
                
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

                    if(check == "FALSE" || passwordCheck === "FALSE" || checkBox === "FALSE" || usernameLengthCheck === "FALSE"){
                        if(check == "FALSE"){
                        alert("Please fill out all the fields");
                        }
                        if(usernameLengthCheck == "FALSE"){
                        alert("Your Username needs at least three chars.");
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

function randomString(length, chars) {
	//nimphios at http://stackoverflow.com/questions/10726909/random-alpha-numeric-string-in-javascript
	
    var mask = '';
    if (chars.indexOf('a') > -1) mask += 'abcdefghijklmnopqrstuvwxyz';
    if (chars.indexOf('A') > -1) mask += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    if (chars.indexOf('#') > -1) mask += '0123456789';
    if (chars.indexOf('!') > -1) mask += '~`!@#$%^&*()_+-={}[]:";\'<>?,./|\\';
    var result = '';
    for (var i = length; i > 0; --i) result += mask[Math.round(Math.random() * (mask.length - 1))];
    return result;
}


function processRegistration(){
    var username = $("#regUsername").val();
    var password = $("#registration #password").val();
    var captcha = $("#captcha").val();
    
    
	//cypher password into two hashes
	//passwordHash is used to cypher the password for db
	//keyHash is used to encrypt the pricate Key
	var md = CryptoJS.MD5(password);
	password = md.toString(CryptoJS.enc.Hex);
	
	
    var salt = CryptoJS.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
    
    var shaPass = CryptoJS.SHA512(salt+password);
    var passwordHash = shaPass.toString(CryptoJS.enc.Hex); //parse cypher object to string
    
    
    var shaKey = CryptoJS.SHA512(password+salt);
    var keyHash = shaKey.toString(CryptoJS.enc.Hex);
    var salt = symEncrypt(password, salt.toString(CryptoJS.enc.Hex));				  //encrypt salt, using md5-pw hash
    
    
    			//generate Keypair
			      var crypt;
			      var publicKey;
			      var privateKey;
			      crypt = new JSEncrypt({default_key_size: 1024});
				  jsAlert('', 'The universe creates now your keypair, this may take some seconds..');
			      crypt.getKey(function () {
			      	privateKey = symEncrypt(keyHash, crypt.getPrivateKey()); //encrypt privatestring, usering the password hash
			      	publicKey = crypt.getPublicKey();
    
                //submit registration
                $.post("../../api.php?action=processSiteRegistration", {
                       username:username,
                       password:passwordHash,
                       salt:salt,
                       publicKey:publicKey,
                       privateKey:privateKey,
                       captcha:captcha
                       }, function(result){
                            var res = result;
                            if(res == 1){
                                //load checked message
                                jsAlert('','You just joined the universeOS');
                                $('#registration').slideUp('');
                                
                                $('#loginUsername').val(username);
                                $('#loginPassword').val($("#registration #password").val());
					            $("#startbox").show("slow");
					            $("#startbox").css('z-index', 9999);
					            $("#startbox").css('position', 'absolute');
                            }else{
                                alert(res);
                            }
                       }, "html");

			      });
}


function login(){
	var username = $('#loginUsername').val();
	var password = $('#loginPassword').val();
	var userid = usernameToUserid(username);
	
	if(getUserCypher(userid) === 'md5'){
		updatePasswordAndCreateSignatures(userid, password);
	}else{
		
		//cypher password
		var md = CryptoJS.MD5(password);
		var passwordHash  = md.toString(CryptoJS.enc.Hex);
		
		var salt = getSalt('auth', userid, passwordHash); //get auth salt, using md5 hash as key
		
		//old passwords(<0.2) are only using md5, new passwords use (sha512(md5(password)+salt))
		if(getUserCypher(userid) != 'md5'){
	    	var shaPass = CryptoJS.SHA512(salt+passwordHash);
	    	passwordHash = shaPass.toString(CryptoJS.enc.Hex);
		}
		
	                $.post("../../api.php?action=authentificate", {
	                       username:username,
	                       password:passwordHash,
	                       }, function(result){
	                            var res = result;
	                            if(res == 1){
	    							localStorage.currentUser_userid = userid;
	    							localStorage.currentUser_username = username;
	    							
	                                //load checked message
	                                $('#bodywrap').slideUp();
	                                window.location.href='index.php';
	                            }else{
	                                alert(res);
	                            }
	                       }, "html");
		
		
		
	}
	
	
}

function updatePasswordAndCreateSignatures(userid, password){
	
	jsAlert('', 'You started using the universeOS with version 0.1, that means your password was not stored with the biggest security and your signatures are not up to date. We use the password you just entered to catch up.');
	
	//cypher password into two hashes
	//passwordHash is used to cypher the password for db
	//keyHash is used to encrypt the pricate Key
	var md = CryptoJS.MD5(password);
	password = md.toString(CryptoJS.enc.Hex);
	var oldPassword = password;
	
    var salt = CryptoJS.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
    console.log('salt not encrypted:'+salt);
    var shaPass = CryptoJS.SHA512(salt+password);
    var passwordHash = shaPass.toString(CryptoJS.enc.Hex); //parse cypher object to string
    
    
    var shaKey = CryptoJS.SHA512(password+salt);
    var keyHash = shaKey.toString(CryptoJS.enc.Hex);
    var salt = symEncrypt(password, salt.toString(CryptoJS.enc.Hex));				  //encrypt salt, using md5-pw hash
    console.log('salt encrypted:'+salt);
    console.log('keyHash:'+keyHash);
    
    			//generate Keypair
			      var crypt;
			      var publicKey;
			      var privateKey;
			      crypt = new JSEncrypt({default_key_size: 1024});
				  jsAlert('', 'The universe creates now your asymetric keypair, this may take some seconds..');
			      crypt.getKey(function () {
			      	privateKey = symEncrypt(keyHash, crypt.getPrivateKey()); //encrypt privatestring, usering the password hash
			      	console.log('encrypted privateKey:'+privateKey);
			      	console.log('publicKey'+publicKey);
			      	publicKey = crypt.getPublicKey();
			      	console.log(privateKey);
			      	console.log(publicKey);
	                $.post("../../api.php?action=updatePasswordAndCreateSignatures", {
	                       userid:userid,
	                       password:passwordHash,
	                       salt:salt,
	                       publicKey:publicKey,
	                       privateKey:privateKey,
	                       oldPassword: oldPassword
	                       }, function(result){
	                            var res = result;
	                            if(res == 1){
	                                //load checked message
	                                jsAlert('','The update worked, you can sign in now with your default userdata.');
	                                
	                            }else{
	                                jsAlert('', 'Oops something went wrong :(');
	                            }
	                       }, "html");
	
				      });
}
