

function randomString(length, chars) {
        //@sec
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


var registration = new function(){
    this.updateRegHintBox = function(html){
        this.resetRegHintBox();
        $('#regHintBox').hide();
        $('#clearBox').append('<div>'+html+'</div>');
    };
    this.resetRegHintBox = function(){
        $('#regHintBox').show();
        $('#clearBox').children().not('#regHintBox').remove();
    };
    this.checkReg = function(username, password, passwordRepeat){
        
                var valuee,check,usernameLengthCheck,checkBox,passwordCheck;
                
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
                
                if(username.length < 3){
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
                if(password != passwordRepeat){
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
                        registration.processRegistration(username, password);
                        console.log('all checks false');
                    }
        
    };
    this.checkPassword = function(id){
            $('#checkPasswordStatus').show();
            var password = $("#"+id).val();
                var score = sec.scorePassword(password);
                var bg,html,help;
                help = 'How to find a Stronger password:<br>Songs<br>Is there a song that has been in your head for years? Use the first letter of each word.<br>Your Favorite Things<br>My favorite is You fill in the blanks. How about:<br>Mfc=Vwb	My favorite car equals Volkswagen Bus.<br>Mff=Lmb	My favorite fish equals Largemouth bass';
                if (score > 80){
                    bg = '#719005';
                    html = '<a style="color: green">Nice! Your password is pretty secure.</a><div class="arrow-right"></div>';
                    registration.resetRegHintBox();
                }
                else if (score > 60){
                    bg = '#c47800';
                    html = '<a style="color: green">Meh. That´s all you got? You can do better!</a><div class="arrow-right"></div>';
                }
                else if (score < 60){
                    bg = '#790125';
                    html = '<a style="color: green">Uh-Oh! My mom could crack that password!</a><div class="arrow-right"></div>';
                }
                
                //show password hints
                if(score<80){
                    support.loadArticle('Create secure passwords', function(data){
                        var jsonObject = JSON.parse(data);
                        var text = nl2br($(jsonObject.parse.text['*']+'').not('#source, .mw-editsection, #toc').text());
                        registration.updateRegHintBox(text.replace(/\[edit\]/g, ''));
                    });
                }
                $('.registerBox p').html(help);
                $('#checkPasswordStatus').html(html);
                $('#checkPasswordStatus').css('background', bg);
                $('#checkPasswordStatus .arrow-right').css('borderRight', '15px solid '+bg);
    };
    this.checkUsername = function(id){
	$('.captchaContainer').slideDown('slow');
	var $checkUsernameStatus = $('#checkUsernameStatus');
        var username = $("#"+id).val();
        
        if(/^[a-zA-Z0-9- ]*$/.test(username) == false) {
            $checkUsernameStatus.html('<a style="color: red">&nbsp;contains illegal characters</a><div class="arrow-right"></div>');
        }else if(username.length > 2){
        
                //check server for new messages
                $.post("api.php?action=checkUsername", {
                       username:username
                       }, function(result){
                            var res = result;
                            if(res == "1"){
                                //load checked message
                                $checkUsernameStatus.hide();
                            }else{
                                $checkUsernameStatus.show();
                                $checkUsernameStatus.html('<a style="color: red">&nbsp;this username is already in use</a><div class="arrow-right"></div>');
                            }
                       }, "html");
        }else{
            //html to short
            $checkUsernameStatus.html('<a style="color: red">&nbsp;to short</a><div class="arrow-right"></div>');
        }
        
        $("#"+id).keyup(function(){
            delay(function(){
              registration.checkUsername(id);
            }, 500 );
	});
    };
    this.processRegistration = function(username, password){
            registration.resetRegHintBox();

            //hide registration form, show loading wheel
            $('.registerBox div').hide();
            gui.alert('The universe is creating your keypair now, this may take some minutes..');
            $('.registerBox img').show();


            //cypher password into two hashes and two salts
            //passwordHash is used to cypher the password for db
            //keyHash is used to encrypt the pricate Key


            //generate Keypair
            var crypt,publicKey,privateKey;

            crypt = new JSEncrypt({default_key_size: 4096});
            
            crypt.getKey(function () {

                    //generate salts and keys from password
                    var keys = cypher.createKeysForUser(password);

                    privateKey = sec.symEncrypt(keys['keyHash'], crypt.getPrivateKey()); //encrypt privatestring, using the password hash
                    publicKey = crypt.getPublicKey();

                    //submit registration
                    //@async
                    $.post("api/user/create/", {
                                            username:username,
                                            password:keys['authHash'],
                                            authSalt:keys['authSaltEncrypted'],
                                            keySalt:keys['keySaltEncrypted'],
                                            publicKey:publicKey,
                                            privateKey:privateKey,
                                        }, function(result){
                                        var res = result;
                                            if(res == 1){
                                                    //load checked message
                                                    gui.alert('You just joined the universeOS');
                                                    $('.registerBox').slideUp('');

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
    };
    this.init = function(){
        console.log('init reg');
        $(document).ready(function(){
            
            $('#regUsername').on('blur',function(){
                registration.checkUsername('regUsername');
            });
            $('#registrationForm #password').on('keyup',function(){
                registration.checkPassword('password');
            });
            
            
            
            $('#registrationForm').submit(function(e){
                e.preventDefault();
                e.stopImmediatePropagation(); //otherwise form will be submitted twice
               registration.checkReg($('#registrationForm #regUsername').val(), $('#registrationForm #password').val(), $('#registrationForm #passwordRepeat').val());
               return false;
            });
            
        });
    };
};

registration.init();

function login(){
	var username = $('#loginUsername').val();
	var password = $('#loginPassword').val();
	var userid = usernameToUserid(username);
	var userCypher = User.getCypher(userid);
	
	var shaPass = hash.SHA512(password);
                
	var passwordHash = cypher.getKey('auth', userid, shaPass);
        
        //@async
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
	                                gui.alert('Wrong username and password combination.');
	                            }
	                            return false;
	                       }, "html");
                               
}

$(document).ready(function(){
   registration.init();
   
        //open menu
        $("#moduleMenu").click(function () {
            $("#startbox").hide("slow");
            $("#dockMenu").slideToggle("slow");
        }); 
        
        //open login box
        $("#personalButton").click(function () {
            $("#dockMenu").hide("slow");
            $("#startbox").slideToggle("slow");
            $("#startbox").css('z-index', 9999);
            $("#startbox").css('position', 'absolute');
        });
});