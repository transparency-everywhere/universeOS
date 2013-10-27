<?php
      session_start();
  include_once("../../inc/config.php");
  include_once("../../inc/functions.php");
  
                    $AccSetSql = mysql_query("SELECT * FROM user WHERE userid='$_SESSION[userid]'");
                    $AccSetData = mysql_fetch_array($AccSetSql);
                    if($AccSetData[birthdate]){
                    $birth_day = date("d", $AccSetData[birthdate]);
                    $birth_month = date("m", $AccSetData[birthdate]);
                    $birth_year = date("Y", $AccSetData[birthdate]);
                    }
                
                    if(isset($_POST[submit])) {
                        $time = time();
                        $imgSql = mysql_query("SELECT `userid`, `profilepictureelement` FROM `user` WHERE userid='".getUser()."'");
                        $imgData = mysql_fetch_array($imgSql);

                        $target_path = "../../upload/userFiles/$_SESSION[userid]/userPictures/";
                        $path = "$target_path";
                        $thumbPath25 = "$target_path/thumb/25";
                        $thumbPath40 = "$target_path/thumb/40";
                        $thumbPath300 = "$target_path/thumb/300";

                        $target_path = $target_path . basename( $_FILES['uploadedfile']['name']);


                        $imgName = basename($_FILES['uploadedfile']['name']);

                        if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
                        	
							$type = getMime($imgName);
                        mkthumb("$imgName",25,25,$path,$thumbPath25); 
                        mkthumb("$imgName",40,40,$path,$thumbPath40); 
                        mkthumb("$imgName",300,300,$path,$thumbPath300);

                        mysql_query("INSERT INTO `files` (`folder`, `title`, `type`, `filename`, `owner`, `timestamp`, `privacy`) VALUES ( '$imgData[profilepictureelement]', '$imgName', '$type',  '$imgName', '$_SESSION[userid]', '$time', 'p');");
                        mysql_query("UPDATE user SET userPicture='$imgName' WHERE userid='$_SESSION[userid]'");
                        jsAlert("The file ".  basename( $_FILES['uploadedfile']['name']). " has been uploaded");

                        } else{
                                echo "There was an error uploading the file, please try again!";
                            }
                        $time = time();





                    }
                    ?>
               <form action="modules/settings/picture.php" method="post" enctype="multipart/form-data" target="submitter" style="margin: 0 10px 0 10px">
                    <h2>upload new profilepicture</h2>
                    <div>
                        
                            <input type="file" name="uploadedfile"><br><br><br>
                        <p>Please keep always in mind that everyone can see this infos till you switched it off in your <mark>privacy settings</mark>.<br>It also don't overwrites your old one and is shown beside all of your posts.</p>       
                    </div>
                    <footer>
                    	<a href="javascript: loader('settingsFrame', 'modules/settings/general.php');" class="btn pull-left">&lt;&lt;back</a>
                        <input type="submit" name="submit" value="upload" class="btn btn-success pull-right">  
                    </footer>
				</form>   