<?php

function rsearch($folder) {
$it = new RecursiveDirectoryIterator($folder);
$allowed=array("php");

$fileList = array();
foreach(new RecursiveIteratorIterator($it) as $file) {
    if(in_array(substr($file, strrpos($file, '.') + 1),$allowed)) {
        $fileList[] = (string)$file;
    }
}
    return $fileList;
}
class apiGenerator{

	public $postVarName = 'route_post';

	public function log($string){

	}

	public function getOldApiFilePaths(){
		return rsearch('./api');
	}

	public function parseOldApiFile($filePath){



		$fileContent = file_get_contents($filePath, FILE_USE_INCLUDE_PATH);

		$expStr=explode('/functions.php");',$fileContent);
		if(!isset($expStr[1]))
			$expStr=explode("/functions.php');",$fileContent);
		$fileContent=$expStr[1];

		$postVarName = $this->postVarName;

		$fileContent = str_replace("\n\t", "\n", $fileContent);
		$fileContent = str_replace("\n", "\n\t\t\t\t", $fileContent);
		$fileContent = str_replace('?>', '', $fileContent);
		$fileContent = str_replace('$_POST', '$'.$postVarName, $fileContent);

		return $fileContent;

	}


	public function run(){

		$oldApiFilePaths = $this->getOldApiFilePaths();
		$output = '';
		foreach($oldApiFilePaths AS $filePath){
			$this->log('...reading file "'.$filePath.'"');
			$output .= '$routes[] = '."array(\n";
			$output .= "\t'path'='$filePath',\n";

			$oldFile = $this->parseOldApiFile($filePath);

			$functionValue = '';

			if (strpos($oldFile,'$'.$this->postVarName) !== false) {
			    $functionValue = '$'.$this->postVarName;
			}

			$output .= "\t'function'= function($functionValue){\n";

			$output .= $oldFile;

			$output .= "\n\t\t\t}\n\t);\n";
			$this->log('......done');

		}
		echo $output;


	}




}

var_dump(rsearch('./api'));


$apiGenerator = new apiGenerator();
$apiGenerator->run();