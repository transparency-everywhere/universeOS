<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_universe
 *
 * @author niczem
 */
class universe {
    //put your code here
    public function init(){
	if(proofLogin()){
            $checkSql = mysql_query("SELECT userid, hash FROM user WHERE userid='".getUser()."'");
            $checkData = mysql_fetch_array($checkSql);
	}else{
		
		$_SESSION['loggedOut'] = true;
	}
    }
    //reload page if session is expired is used in reload.php
    function proofSession(){
            if((!$_SESSION['loggedOut'])&&(!proofLogin())){
                    echo"<script>window.location.href='index.php'</script>";
                    $_SESSION['loggedOut'] = true;
            }
    }

    function universeText($str){

        $str = str_replace(":'(", '<a class="smiley smiley1"></a>', $str);//crying smilye /&#039; = '
        $str = str_replace(':|', '<a class="smiley smiley2"></a>', $str);
        $str = str_replace(';)', '<a class="smiley smiley3"></a>', $str);
        $str = str_replace(':P', '<a class="smiley smiley4"></a>', $str);
        $str = str_replace(':-D', '<a class="smiley smiley5"></a>', $str);
        $str = str_replace(':D', '<a class="smiley smiley5"></a>', $str);
        $str = str_replace(':)', '<a class="smiley smiley6"></a>', $str);
        $str = str_replace(':(', '<a class="smiley smiley7"></a>', $str);
        $str = str_replace(':-*', '<a class="smiley smiley8"></a>', $str);
                    $str = preg_replace("#(^|[^\"=]{1})(http://|ftp://|mailto:|https://)([^\s<>]+)([\s\n<>]|$)#sm","\\1<a target=\"_blank\" href=\"\\2\\3\">\\3</a>\\4",$str);
        # Links
        $str = preg_replace_callback("#\[itemThumb type=(.*)\ typeId=(.*)\]#", 'showChatThumb' , $str);



        return $str;
    }
}
	
	