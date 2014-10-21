<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_xml
 *
 * @author niczem
 */
class class_xml {
    //put your code here
}

/**
  * Opens an URL with curl and outputs xml
  *
  * @param string $url      Contains URL
  *
  * @return array Contains the returned xm.
  */
function curler($url){
 	
        //umgehen des HTTP Verbots
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_USERAGENT, "universeOS");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
        
        //Result in DomDocument Laden
        $dom = new DOMDocument;
        $dom->loadXML($result);
        //Array der XML Datei auslesen.
        $xml = simplexml_import_dom($dom);
		return $xml;
 }