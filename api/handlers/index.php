<?
include("../../inc/config.php");
include("../../inc/functions.php");
include("../../inc/classes/phpfastcache.php");

if(isset($_POST['handler_title'])){
    $handler = new handler();
    echo $handler->api($_POST['handler_title'], $_POST['action'], $_POST['parameters']);

    die();
}
$handlerType;
    $preLoadResultList = array();
    foreach($_POST['request'] AS $request){
        //var_dump($request);
        if($request['handler_title']  == 'youtube'&&isset($request['parameters']['url'])){
            $handlerType = $request['handler_title'];
            $preLoadResultList[] = $request['parameters']['url'];
        }
    }

if(count($preLoadResultList)>1){
    //var_dump($preLoadResultList);
    $handler = new handler();
    $handler->api($handlerType, 'preload', $preLoadResultList);
}


$requestFunction = function($request){
    //var_dump($request);
    $handler = new handler();
    return $handler->api($request['handler_title'], $request['action'], $request['parameters']);
};

$api = new api();
$api->handleRequest($_POST['request'], $requestFunction);
