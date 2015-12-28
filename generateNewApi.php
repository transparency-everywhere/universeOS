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

	public $postVarName = 'post_vars';

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
		$output = "<?php\nclass uni_routes{";
		$output .= "\npublic static function getRoutes(){\n";
		foreach($oldApiFilePaths AS $filePath){
			$this->log('...reading file "'.str_replace("./api/", "", str_replace("/index.php", "/", $filePath)).'"');
			$output .= '$routes[] = '."array(\n";
			$output .= "\t'path'=>'".str_replace("./api/", "", str_replace("/index.php", "/", $filePath))."',\n";

			$oldFile = $this->parseOldApiFile($filePath);

			$functionValue = '';

			if (strpos($oldFile,'$'.$this->postVarName) !== false) {
			    $functionValue = '$'.$this->postVarName;
			}

			$output .= "\t'callback'=> function($functionValue){\n";

			$output .= $oldFile;


			$output .= "\n\t\t\t}\n\t);\n";

			$this->log('......done');

		}
			$output .= 'return $routes;'."\n}\n}";
			$this->saveRouteClass($output);
		echo $output;


	}


	public function saveRouteClass($content){
		$filePath = 'inc/classes/uni_routes.php';
		unlink($filePath);
		$fp = fopen($filePath, 'w');
		fwrite($fp, $content);
		fclose($fp);
	}



}


$apiGenerator = new apiGenerator();
$apiGenerator->run();