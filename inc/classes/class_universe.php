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
}
