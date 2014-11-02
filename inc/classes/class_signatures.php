<?php
/**
This file is published by transparency-everywhere with the best deeds.
Check transparency-everywhere.com for further information.
Licensed under the CC License, Version 4.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

https://creativecommons.org/licenses/by/4.0/legalcode

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.

@author nicZem for Tranpanrency-everywhere.com
*/

class signatures{
	
	function create($type, $itemId, $privateKey, $publicKey){
		mysql_query("DELETE FROM `signatures` WHERE type='".save($type)."' AND itemId='".save($itemId)."'");
		mysql_query("INSERT INTO `signatures` (`type`, `itemId`, `privateKey`, `publicKey`, `timestamp`) VALUES ('$type', '$itemId', '$privateKey', '$publicKey', '".time()."')");
	}
	
	function get($type, $itemId){
		$data = mysql_fetch_array(mysql_query("SELECT * FROM `signatures` WHERE `type`='$type' AND `itemId`='$itemId'"));
		return $data;
	}
	
	function updatePrivateKey($type, $itemId, $privateKey){
		if(!empty($privateKey)){
			mysql_query("UPDATE `signatures` SET privateKey='".save($privateKey)."' WHERE `type`='".save($type)."' AND `itemId`='".save($itemId)."'");
		}
	}
	
	function delete($type, $itemId){
		mysql_query("DELETE FROM `signatures` WHERE `type`='$type' AND `itemId`='$itemId'");
	}
}
