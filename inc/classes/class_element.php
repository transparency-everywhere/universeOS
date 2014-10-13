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
class element {
    //put your code here
}

function getElementData($elementId){
		$query = mysql_query("SELECT * FROM `elements` WHERE id='".save($elementId)."'");
		$data = mysql_fetch_array($query);
		
		return $data;
	}
