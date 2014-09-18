
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
	//get user input
    var username = $("#regUsername").val();
    var password = $("#registration #password").val();
    var captcha = $("#captcha").val();
    
    
    //hide registration form, show loading wheel
    $('#regForm').slideUp();
	jsAlert('', 'The universe is creating your keypair now, this may take some minutes..');
	$('#regLoad').show();
    
    
	//cypher password into two hashes and two salts
	//passwordHash is used to cypher the password for db
	//keyHash is used to encrypt the pricate Key
	
	
    				//generate Keypair
			      	var crypt;
					var publicKey;
			      	var privateKey;
			      	
			      	crypt = new JSEncrypt({default_key_size: 4096});
			      	crypt.getKey(function () {
			      		
			      		//generate salts and keys from password
    					var keys = cypher.createKeysForUser(password);
    					
			      		privateKey = sec.symEncrypt(keys['keyHash'], crypt.getPrivateKey()); //encrypt privatestring, using the password hash
			      		publicKey = crypt.getPublicKey();
    					
                		//submit registration
                		$.post("api.php?action=processSiteRegistration", {
                       		username:username,
                       		password:keys['authHash'],
                       		authSalt:keys['authSaltEncrypted'],
                       		keySalt:keys['keySaltEncrypted'],
                       		publicKey:publicKey,
                       		privateKey:privateKey,
                       		captcha:captcha
                       		
                       	}, function(result){
                            var res = result;
                            	if(res == 1){
                                	//load checked message
                                	jsAlert('','You just joined the universeOS');
                                	$('#regLoad').slideUp('');
                                
                                	$('#loginUsername').val(username);
                                	$('#loginPassword').val($("#registration #password").val());
					            	$("#startbox").show("slow");
					            	$("#startbox").css('z-index', 9999);
					            	$("#startbox").css('position', 'absolute');
                            	}else{
                                	alert(res);
                                	$('#regLoad').slideUp('');
                                	$('#regForm').show();
                            	}
                       	}, "html");

			      });
}



function login(){
	var username = $('#loginUsername').val();
	var password = $('#loginPassword').val();
	var userid = usernameToUserid(username);
	var userCypher = getUserCypher(userid);
	
	
	if(userCypher === 'md5'){
		updatePasswordAndCreateSignatures(userid, password);
	}else if(userCypher == 'sha512'){
		update.sha512TOsha512_2(userid, password);
	}else if(userCypher == 'sha512_2'){
		var shaPass = hash.SHA512(password);
    	console.log('dude');
		var passwordHash = cypher.getKey('auth', userid, shaPass);
		console.log('dude');
		
	                $.post("api.php?action=authentificate", {
	                       username:username,
	                       password:passwordHash,
	                       }, function(result){
	                            var res = result;
	                            if(res == 1){
	                            	
	                            	//store needed values in localStorage
	    							localStorage.currentUser_userid = userid;
	    							localStorage.currentUser_username = username;
	    							localStorage.currentUser_passwordHashMD5 = false; // <- delete!
	    							localStorage.currentUser_shaPass = shaPass;
	    							
	    							
	                                //load checked message
	                                $('#bodywrap').slideUp();
	                                window.location.href='index.php';
	                            }else{
	                                jsAlert('', 'Wrong username and password combination.');
	                            }
	                            return false;
	                       }, "html");
		
		
		
	}else{
		jsAlert('', 'There is no user with this username.');
	}
	
	
}

var update = new function(){
	this.sha512TOsha512_2 = function(userid, password){
			//is used to erase use of md5
		
			//UPDATE PASSWORD
	    		var cypherOld = sec.passwordCypher(password, 'auth', userid);
	    		var passwordHashOld = cypherOld[0];
	    	
				var saltDecrypted = cypherOld[3];
				console.log('s:'+saltDecrypted);
				if(saltDecrypted.length == 0||passwordHashOld.length==0||saltDecrypted=='undefined'){
					jsAlert('', 'The Password you entered was wrong');
					return false;
				}
				//generate new password
				var passwordSHA512 = hash.SHA512(password); //stretch password
				var password_new = hash.SHA512(passwordSHA512+saltDecrypted);
				//encrypt salt with sha512(password)
				var saltEncrypted_new = sec.symEncrypt(passwordSHA512, saltDecrypted);
		
		
		
			//UPDATE PRIVATE KEY
			
				//get old private key
				var privateKey = sec.getPrivateKey('user', userid, saltDecrypted, cypherOld[1]);
				
				//save new private key
				
				//generate salt for random key
    			var keySalt = hash.SHA512(randomString(64, '#aA'));  //generate salt and hash it.
    			
			    var privateKeyHash = hash.SHA512(passwordSHA512+keySalt);
			    
			    
			    //save salt
			    var encryptedKeySalt = sec.symEncrypt(passwordSHA512, keySalt);
			    //createSalt('privateKey', userid, '', '', encryptedKeySalt);
			    
			    var privateKeyNew = sec.symEncrypt(privateKeyHash, privateKey);
			
				//save new password, new private key and send oldpw & userid
				$.post("api.php?action=update_sha512TOsha512_2", {
	                       userid:userid,
	                       oldPassword:passwordHashOld,
	                       newPassword:password_new,
	                       newPrivateKey:privateKeyNew,
	                       saltAuthNew: saltEncrypted_new,
	                       saltKeyNew: encryptedKeySalt
	                       }, function(result){
	                            var res = result;
	                            if(res){
	                            	
	                            	jsAlert(res);
	                            }else{
	                                jsAlert('', 'Your account has been updated. You can login with your default userdata.');
	                            }
	                            return false;
	                       }, "html");
			
		
		
		
		
	};
};

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
    
    
    var salt = sec.symEncrypt(password, salt.toString(CryptoJS.enc.Hex));				  //encrypt salt, using md5-pw hash
    console.log('salt encrypted:'+salt);
    console.log('keyHash:'+keyHash);
    
    			//generate Keypair
			      var crypt;
			      var publicKey;
			      crypt = new JSEncrypt({default_key_size: 1024});
				  jsAlert('', 'The universe creates now your asymetric keypair, this may take some seconds..');
			      crypt.getKey(function () {
			      	var temp = crypt.getPrivateKey();
			      	
			      	console.log('P:::'+temp+':::P');
			      	
			      	var privateKey = sec.symEncrypt(keyHash, temp); //encrypt privatestring, usering the password hash
			      	
			      	console.log('encrypted privateKey:'+privateKey);
			      	publicKey = crypt.getPublicKey();
			      	console.log(publicKey);
	                $.post("api.php?action=updatePasswordAndCreateSignatures", {
	                       userid:userid,
	                       password:passwordHash,
	                       salt:salt,
	                       publicKey:publicKey,
	                       privateKey:privateKey,
	                       oldPassword: oldPassword
	                       }, function(result){
	                            var res = result;
	                            if(res == '1'){
	                                //load checked message
	                                jsAlert('','The update worked, you can sign in now with your default userdata.');
	                                
	                            }else{
	                                jsAlert('', 'Oops something went wrong :(');
	                            }
	                       }, "html");
	
				      });
}
