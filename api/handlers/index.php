<?
include("../../inc/config.php");
include("../../inc/functions.php");

if(isset($_POST['handler_title'])){
    $handler = new handler();
    echo $handler->api($_POST['handler_title'], $_POST['action'], $_POST['parameters']);

    die();
}

$requestFunction = function($request){
    //var_dump($request);
    $handler = new handler();
    return $handler->api($request['handler_title'], $request['action'], $request['parameters']);
};

$api = new api();
$api->handleRequest($_POST['request'], $requestFunction);
