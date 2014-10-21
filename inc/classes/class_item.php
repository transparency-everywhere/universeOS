<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class item {
    function __construct($type, $typeid){
       $this->type = $type;
       $this->typeid = $typeid;
    }
    //put your code here
    function plusOne(){
       $type = $this->type;
       $typeid = $this->typeid;
       if($type == "comment"){
           mysql_query("UPDATE comments SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
       }else if($type == "feed"){
           mysql_query("UPDATE feed SET votes = votes + 1, score = score + 1 WHERE id='$typeid'");
       }
       if($type == "file"){
           mysql_query("UPDATE files SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
       }
       if($type == "folder"){
           mysql_query("UPDATE folders SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
       }
       if($type == "element"){
           mysql_query("UPDATE elements SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       if($type == "file"){
           mysql_query("UPDATE file SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       if($type == "link"){
           mysql_query("UPDATE links SET votes = votes + 1, score = score + 1 WHERE id='$typeid'"); 
   
       }
       //score++
       }
function minusOne(){
    
       $type = $this->type;
       $typeid = $this->typeid;
       if($type == "comment"){
           mysql_query("UPDATE comments SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
           
       }else if($type == "feed"){
           mysql_query("UPDATE feed SET votes = votes + 1, score = score - 1 WHERE id='$typeid'");
       }
       if($type == "file"){
           mysql_query("UPDATE files SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "folder"){
           mysql_query("UPDATE folders SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "element"){
           mysql_query("UPDATE elements SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
       }
       if($type == "file"){
           mysql_query("UPDATE file SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
   
       }
       if($type == "link"){
           mysql_query("UPDATE links SET votes = votes + 1, score = score - 1 WHERE id='$typeid'"); 
   
       }
       } //score--
       
    
function showScore($reload=NULL) {
    
       $type = $this->type;
       $typeid = $this->typeid;
        if(proofLogin()){
               if($type == "comment"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM comments WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql);
               }
               else if($type == "feed"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM feed WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "folder"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM folders WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "element"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM elements WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "file"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM files WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "link"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM links WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               if(!isset($reload)){
                   $output =  "<div class=\"score$type$typeid\">";
               }
			   
			   
			   if($scoreData['score'] > 0){
			   	$class = "btn-success";
			   }else if($scoreData['score'] < 0){
			   	$class = "btn-warning";
			   }else{
			   	$class = '';
			   }
			   
			   $output .= '<div class="btn-toolbar" style="margin: 0px;">';
			   $output .= '<div class="btn-group">';
			   $output .="<a class=\"btn btn-mini\" href=\"doit.php?action=scoreMinus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-down\"></i></a>";
			   $output .= "<p class=\"btn btn-mini $class\" href=\"#\">$scoreData[score]</p>";
			   $output .= "<a class=\"btn btn-mini\" href=\"doit.php?action=scorePlus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-up\"></i></a>";
			   $output .= '</div>';
			   $output .= '</div>';
               if(!isset($reload)){
                   $output .=  "</div>";
               }
			   
			   return $output;
        }
           
       }
}



function showScore($type, $typeid, $reload=NULL) {
    debug::write('use of show score');
        if(proofLogin()){
               if($type == "comment"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM comments WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql);
               }
               else if($type == "feed"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM feed WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "folder"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM folders WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "element"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM elements WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "file"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM files WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               else if($type == "link"){
               $scoreSql = mysql_query("SELECT id, votes, score FROM links WHERE id='$typeid'");
               $scoreData = mysql_fetch_array($scoreSql); 
               }
               if(!isset($reload)){
                   $output =  "<div class=\"score$type$typeid\">";
               }
			   
			   
			   if($scoreData['score'] > 0){
			   	$class = "btn-success";
			   }else if($scoreData['score'] < 0){
			   	$class = "btn-warning";
			   }else{
			   	$class = '';
			   }
			   
			   $output .= '<div class="btn-toolbar" style="margin: 0px;">';
			   $output .= '<div class="btn-group">';
			   $output .="<a class=\"btn btn-mini\" href=\"doit.php?action=scoreMinus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-down\"></i></a>";
			   $output .= "<p class=\"btn btn-mini $class\" href=\"#\">$scoreData[score]</p>";
			   $output .= "<a class=\"btn btn-mini\" href=\"doit.php?action=scorePlus&type=$type&typeid=$typeid\" target=\"submitter\"><i class=\"icon-thumbs-up\"></i></a>";
			   $output .= '</div>';
			   $output .= '</div>';
               if(!isset($reload)){
                   $output .=  "</div>";
               }
			   
			   return $output;
        }
           
       }