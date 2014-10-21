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

class db{
	public function insert($table, $options){
					
			//generate update query	
			foreach($options AS $row=>$value){
				$query[] = "`".save($row)."`";
				$values[] = "'".save($value)."'";
			}
			
			
			$query = "(".implode(',', $query).")";
			$values = "(".implode(',', $values).");";
			
			
		mysql_query("INSERT INTO `$table` $query VALUES $values");
		
	}
	public function update($table, $options, $primary){
					
			//generate update query	
			foreach($options AS $row=>$value){
				
				//only add row to query if value is not empty
				if(!empty($value)){
					$query[] = " $row='".save($value)."'";
				}
			}
			$query = implode(',', $query);
			
			
		mysql_query("UPDATE `$table` SET $query WHERE $primary[0]='".save($primary[1])."'");
		
	}
}
    function commaToOr($string, $type){
        //converts Strings with Values, which are separeted with commas into SQL conform STRINGS
        $string = explode(";", $string);
        foreach($string as &$value){
            if(empty($deddl)){
                $return = "$type='$value'";
                $deddl = "checked";
            }else{
            
            $return = "$type='$value' OR $return";

            }
        }
        return $return;
    }
    
    
    function save($text){
        //should be a standard way to return save POST, GET or SESSION variables
        
        $text = mysql_real_escape_string($text);
        return $text;
    }