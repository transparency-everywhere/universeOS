<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class outController{

    //!!!kickstarter and open will be the same!!
    /*public static function kickStarter($type, $itemId){
            $gui = new gui();
            $title = 'no.'.$itemId;
            
            $gui->generateHeader();
            
            $reloadTime = 30;//s
            
            echo '<h2>You are seeing the preview of '.$type.' '.$title.'</h2>';
            echo '<h3>You will be redirected to the universeOS in '.$reloadTime.' seconds.';
            echo '<a href="#">Stop</a>';
            
                $gui = new gui();
                $gui->generate(['action'=>'kickstarter','action_data'=>['type'=>$type, 'itemId'=>$itemId]]);
    }*/
    public function embed($type, $itemId){
        
                $gui = new gui();
                $gui->generate(['action'=>'embed','action_data'=>['type'=>$type, 'itemId'=>$itemId], 'show_dock'=>false]);
    }
    public function open($type, $itemId){

            
            echo '<h2>You are seeing the preview of '.$type.' '.$title.'</h2>';
            echo '<h3>You will be redirected to the universeOS in '.$reloadTime.' seconds.';
            echo '<a href="#">Stop</a>';
            
                $gui = new gui();
                $gui->generate(['action'=>'kickstarter','action_data'=>['type'=>$type, 'itemId'=>$itemId]]);
    }
}