<?php
class uni_routes{
    public static function getRoutes(){
        $routes[] = array(
                'path'=>'appCenter/addAppToUserDashboard/',
                'callback'=> function($post_vars){

                                        error_reporting(E_ALL);
                                        echo var_dump($post_vars['parameters']);
                                        $appCenterApp = new appCenterApp();
                                        $appCenterApp->addAppToUserDashboard($post_vars['parameters']);

                                }
                );
        $routes[] = array(
                'path'=>'appCenter/createApp/',
                'callback'=> function($post_vars){

                                        $appCenterApp = new appCenterApp();
                                        $appCenterApp->create($post_vars['parameters']['appId'],$post_vars['parameters']);

                                }
                );
        $routes[] = array(
                'path'=>'appCenter/getAppDetailsForUser/',
                'callback'=> function(){

                                        error_reporting(E_ALL);
                                        $appCenter = new appCenter();
                                        echo json_encode($appCenter->getAppDetailsForUser());

                                }
                );
        $routes[] = array(
                'path'=>'appCenter/getApps/',
                'callback'=> function(){


                                        $appCenter = new appCenter();
                                        echo json_encode($appCenter->getApps());

                                }
                );
        $routes[] = array(
                'path'=>'appCenter/getAppsForUser/',
                'callback'=> function(){


                                        $appCenter = new appCenter();
                                        echo json_encode($appCenter->getAppsForUser());

                                }
                );
        $routes[] = array(
                'path'=>'appCenter/prepareAppCreation/',
                'callback'=> function(){


                                        $appCenterApp = new appCenterApp();
                                        echo json_encode($appCenterApp->prepareAppCreation(),true);

                                }
                );
        $routes[] = array(
                'path'=>'appCenter/removeAppFromUserDashboard/',
                'callback'=> function($post_vars){


                                        $appCenterApp = new appCenterApp();
                                        $appCenterApp->removeAppFromUserDashboard($post_vars['parameters']);

                                }
                );
        $routes[] = array(
                'path'=>'buddies/acceptRequest/',
                'callback'=> function($post_vars){


                                        $buddyListClass = new buddylist();
                                        $check = $buddyListClass->replyRequest($post_vars['buddy_id']);


                                }
                );
        $routes[] = array(
                'path'=>'buddies/add/',
                'callback'=> function($post_vars){



                                                $buddyListClass = new buddylist();

                                                $check = $buddyListClass->addBuddy($post_vars['buddy']);
                                        if($check){
                                                $message = "The Buddy was added.";
                                                $_SESSION['personalFeed'] = 0;
                                                        echo 1;
                                        }else{

                                                    echo 0;
                                        }


                                }
                );
        $routes[] = array(
                'path'=>'buddies/get/',
                'callback'=> function($post_vars){



                                                $buddyListClass = new buddylist();
                                        echo json_encode($buddyListClass->buddyListArray($post_vars['user_id']));


                                }
                );
        $routes[] = array(
                'path'=>'buddies/remove/',
                'callback'=> function($post_vars){



                                                $buddyListClass = new buddylist();

                                                $check = $buddyListClass->deleteBuddy($post_vars['buddy']);
                                        if($check){
                                                $_SESSION['personalFeed'] = 0;
                                                        echo 1;
                                        }else{
                                                    echo 0;
                                        }


                                }
                );
        $routes[] = array(
                'path'=>'calendar/events/create/',
                'callback'=> function($post_vars){


                                                //set privacy
                                                $customShow = $post_vars['privacyCustomSee'];
                                                $customEdit = $post_vars['privacyCustomEdit'];

                                                $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);


                                        $events = new events();
                                        $events->create(getUser(), $post_vars['startStamp'], $post_vars['stopStamp'], $post_vars['title'], $post_vars['place'], $privacy, $post_vars['users']);

                                }
                );
        $routes[] = array(
                'path'=>'calendar/events/getEventData/',
                'callback'=> function($post_vars){





                                                $events = new events();
                                                echo json_encode($events->getData($post_vars['eventId']));
                                }
                );
        $routes[] = array(
                'path'=>'calendar/events/getEvents/',
                'callback'=> function($post_vars){



                                        $requestFunction = function($request){

                                            $events = new events();
                                            return ($events->get(getUser(), $request['startStamp'], $request['stopStamp'], $request['privacy']));

                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);
                                }
                );
        $routes[] = array(
                'path'=>'calendar/events/join/',
                'callback'=> function($post_vars){




                                        $events = new events();
                                        $events->joinEvent($post_vars['originalEventId'], getUser(), $post_vars['addToVisitors']);
                                }
                );
        $routes[] = array(
                'path'=>'calendar/tasks/create/',
                'callback'=> function($post_vars){


                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];

                                        $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);


                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];
                                        $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);

                                        $tasks = new tasks();
                                        $tasks->create(getUser(), $post_vars['timestamp'], $post_vars['status'], $post_vars['title'], $post_vars['description'], $privacy);

                                }
                );
        $routes[] = array(
                'path'=>'calendar/tasks/delete/',
                'callback'=> function($post_vars){


                                        $tasks = new tasks();
                                        $tasks->delete($post_vars['id']);

                                }
                );
        $routes[] = array(
                'path'=>'calendar/tasks/get/',
                'callback'=> function($post_vars){



                                        $requestFunction = function($request){

                                            $tasks = new tasks();
                                            return ($tasks->get(getUser(), $request['startStamp'], $request['stopStamp'], $request['privacy']));

                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);
                                }
                );
        $routes[] = array(
                'path'=>'elements/create/',
                'callback'=> function($post_vars){


                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];

                                        $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);

                                        $elementClass = new element();
                                        $elementClass->create($post_vars['folder'], $post_vars['title'],  $post_vars['type'],  getUser(), $privacy);


                                }
                );
        $routes[] = array(
                'path'=>'elements/delete/',
                'callback'=> function($post_vars){



                                        $element = new element($post_vars['element_id']);
                                        echo $element->delete();
                                }
                );
        $routes[] = array(
                'path'=>'elements/getAuthorData/',
                'callback'=> function($post_vars){


                                        $element = new element();
                                        echo json_encode($element->getAuthorData($post_vars['user_id']));

                                }
                );
        $routes[] = array(
                'path'=>'elements/getFileList/',
                'callback'=> function($post_vars){


                                        $element = new element($post_vars['element_id']);
                                        echo json_encode($element->getFileList());

                                }
                );
        $routes[] = array(
                'path'=>'elements/getFileNumbers/',
                'callback'=> function($post_vars){


                                        $element = new element($post_vars['element_id']);
                                        echo json_encode($element->getFileNumbers());

                                }
                );
        $routes[] = array(
                'path'=>'elements/select/',
                'callback'=> function($post_vars){






                                        $requestFunction = function($request){
                                            $element = new element($request['element_id']);
                                            return ($element->getData($request['element_id']));
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);


                                }
                );
        $routes[] = array(
                'path'=>'elements/update/',
                'callback'=> function($post_vars){



                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];

                                        $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);

                                        $element = new element($post_vars['element_id']);
                                        echo $element->update($post_vars['folder'], $post_vars['title'], $post_vars['type'] , $privacy);

                                }
                );
        $routes[] = array(
                'path'=>'fav/add/',
                'callback'=> function($post_vars){


                                        //set privacy
                                        $favClass = new fav();
                                        echo $favClass->addFav($post_vars['type'], $post_vars['typeid']);
                                }
                );
        $routes[] = array(
                'path'=>'fav/remove/',
                'callback'=> function($post_vars){



                                        //set privacy
                                        $favClass = new fav();
                                        echo $favClass->remove($post_vars['type'], $post_vars['typeId']);

                                }
                );
        $routes[] = array(
                'path'=>'fav/select/',
                'callback'=> function($post_vars){


                                        error_reporting(E_ALL);

                                        $fav = new fav();
                                        echo json_encode($fav->select($post_vars['user']));

                                }
                );
        $routes[] = array(
                'path'=>'feed/create/',
                'callback'=> function($post_vars){


                                            if(!empty($post_vars['content'])){

                                                            //set privacy
                                                            $customShow = $post_vars['privacyCustomSee'];
                                                            $customEdit = $post_vars['privacyCustomEdit'];

                                                            $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);
                                                            $user = getUser();
                                                            $feed = $post_vars['content'];

                                                            //create feed
                                                            $feedClass = new feed();
                                                            $id = $feedClass->create(getUser(), $feed, "", "feed", "p", $post_vars['attachedItemType'], $post_vars['attachedItemId']);
                                                    echo"<script>";
                                                    echo"parent.reloadFeed('friends');";
                                                    echo"parent.$('#feedInput').val('');";
                                                    echo"</script>";
                                            }
                                }
                );
        $routes[] = array(
                'path'=>'feed/delete/',
                'callback'=> function($post_vars){


                                        $feed = new feed();
                                        echo $feed->delete($post_vars['feed_id']);
                                }
                );
        $routes[] = array(
                'path'=>'feed/load/',
                'callback'=> function($post_vars){


                                            $feedClass = new feed($fileId);
                                            $dbClass = new db();

                                            $result = $feedClass->load($post_vars['type'], $post_vars['typeId'], $post_vars['limit']);
                                            $result = $dbClass->shiftResult($result, 'id');
                                            echo json_encode($result);

                                }
                );
        $routes[] = array(
                'path'=>'feed/loadFrom/',
                'callback'=> function($post_vars){


                                        $feed = new feed();
                                        echo json_encode($feed->loadFeedsFrom($post_vars['start_id'], $post_vars['type'], $post_vars['type_id']));

                                }
                );
        $routes[] = array(
                'path'=>'feed/select/',
                'callback'=> function($post_vars){


                                        $feed = new feed();
                                        echo json_encode($feed->getData($post_vars['feedId']));

                                }
                );
        $routes[] = array(
                'path'=>'files/delete/',
                'callback'=> function($post_vars){



                                        error_reporting(E_ALL);
                                                        $fileId = $post_vars['file_id'];
                                                        $dbClass = new db();
                                                        $fileData = $dbClass->select('files', array('id', $fileId));
                                                        if(authorize($fileData['privacy'], "edit", $fileData['owner'])){
                                                            $fileClass = new file($fileId);
                                                            if($fileClass->delete()){
                                                                echo 1;
                                                            }else{
                                                                echo 0;
                                                            }
                                                }

                                }
                );
        $routes[] = array(
                'path'=>'files/getMyFiles/',
                'callback'=> function($post_vars){


                                        $class = new files();
                                        echo json_encode($class->getMyFiles($post_vars['userid']));

                                }
                );
        $routes[] = array(
                'path'=>'files/read/',
                'callback'=> function($post_vars){



                                        $fileClass = new file($post_vars['file_id']);
                                        echo $fileClass->read();


                                }
                );
        $routes[] = array(
                'path'=>'files/reader/',
                'callback'=> function($post_vars){



                                        error_reporting(E_ALL);
                                                        $fileId = $post_vars['file_id'];
                                                        $dbClass = new db();
                                                        $fileData = $dbClass->select('files', array('id', $fileId));
                                                        if(authorize($fileData['privacy'], "edit", $fileData['owner'])){
                                                            $fileClass = new file($fileId);
                                                            if($fileClass->delete()){
                                                                echo 1;
                                                            }else{
                                                                echo 0;
                                                            }
                                                }

                                }
                );
        $routes[] = array(
                'path'=>'files/readJson/',
                'callback'=> function($post_vars){


                                        $fileClass = new file($post_vars['file_id']);
                                        echo $fileClass->readJson();


                                }
                );
        $routes[] = array(
                'path'=>'files/report/',
                'callback'=> function($post_vars){



                                        $timstamp = time();
                                        $db = new db();
                                        $db->query("INSERT INTO `adminMessages` (`timestamp` ,`author` ,`category` ,`type` ,`message`) VALUES ('$time', '".getUser()."', '1', '".escape::sql($post_vars['reason'])."', 'reported file id: ".escape::sql($post_vars['file_id'])."     ".escape::sql($post_vars['message'])."');");

                                }
                );
        $routes[] = array(
                'path'=>'files/select/',
                'callback'=> function($post_vars){


                                        $requestFunction = function($request){
                                            $folder = new file($request['file_id']);
                                            return ($folder->getFileData());
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);

                                }
                );
        $routes[] = array(
                'path'=>'files/submitUploader/',
                'callback'=> function($post_vars){



                                        //handler for form submit in upload.php
                                        //adds privacy and removes temp status
                                        //from temp files
                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];

                                        $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);

                                        $files = $post_vars['uploadedFiles'];
                                        $successfullUploadedFiles = 0;
                                        foreach($files AS $file){
                                                $fileClass = new file($file);
                                                if($fileClass->validateTempFile($privacy)){
                                                    $successfullUploadedFiles++;
                                                }else{
                                                    $filesWithError[] = $file; //add fileid to error list
                                                }
                                        }
                                        echo'<script> filesystem.tabs.removeTab(' + uploaderTabId + '); elements.open(\'' + element + '\', \'' + elementTabId + '\'); </script>';
                                        jsAlert("The files have successfully been added to the Element.");






                                }
                );
        $routes[] = array(
                'path'=>'files/uff/create/',
                'callback'=> function($post_vars){


                                                            $element = save($post_vars['element']);
                                                            $title = save($post_vars['title']);
                                                            $filename = save($post_vars['filename']);
                                                            //set privacy
                                                            $customShow = $post_vars['privacyCustomSee'];
                                                            $customEdit = $post_vars['privacyCustomEdit'];

                                                            $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);


                                                            $title10 = addslashes(substr($elementData['title'], 0, 10));

                                                            $uffClass = new uff();
                                                            if($uffClass->create($element, $title, $filename, $privacy)){

                                                                jsAlert("your file has been created");
                                                                echo'<script>';

                                                                           echo"parent.filesystem.tabs.updateTabContent('$title10' ,parent.gui.loadPage('modules/filesystem/showElement.php?element=$element&reload=1'));";

                                                                echo'</script>';
                                                            }

                                }
                );
        $routes[] = array(
                'path'=>'files/uff/write/',
                'callback'=> function($post_vars){


                                        $uff = new uff($post_vars['file_id']);
                                        echo $uff->write($post_vars['input']);

                                }
                );
        $routes[] = array(
                'path'=>'files/updateFileContent/',
                'callback'=> function($post_vars){


                                                    error_reporting(E_ALL);
                                                    $fileClass = new file($post_vars['file_id']);
                                                    $fileClass->overwrite($post_vars['content']);

                                }
                );
        $routes[] = array(
                'path'=>'files/upload/',
                'callback'=> function($post_vars){



                                        //upload temp_file
                                        $file = $_FILES['Filedata'];

                                        $user = getUser();
                                        $filesClass = new files();
                                        $id = $filesClass->uploadTempfile($file, $post_vars['element'], '', $privacy, $user);

                                        $li = "<li data-fileid=\"$id\"><div>".$file['name']."</div>  <input type=\"hidden\" name=\"uploadedFiles[]\" value=\"$id\">    <span class=\"icon icon-trash\"  onclick=\"$(this).parent(\\'li\\').remove()\"></span></li>";

                                        //add file to filelist in the uploader
                                        echo'$(".tempFilelist").append(\''.$li.'\');';

                                }
                );
        $routes[] = array(
                'path'=>'files/uploadTemp/',
                'callback'=> function($post_vars){



                                        //upload temp_file
                                        $file = $_FILES['Filedata'];

                                        $user = getUser();
                                        $filesClass = new files();
                                        $id = $filesClass->uploadTempfile($file, $post_vars['element'], '', $privacy, $user);

                                        $li = "<li data-fileid=\"$id\"><div>".$file['name']."</div>  <input type=\"hidden\" name=\"uploadedFiles[]\" value=\"$id\">    <span class=\"icon icon-trash\"  onclick=\"$(this).parent(\\'li\\').remove()\"></span></li>";

                                        //add file to filelist in the uploader
                                        echo'$(".tempFilelist").append(\''.$li.'\');';

                                }
                );
        $routes[] = array(
                'path'=>'folders/create/',
                'callback'=> function($post_vars){


                                        $db = new db();
                                        $checkData = $db->select('folders', array('id', $post_vars['folder']));
                                        if(authorize($checkData['privacy'], "edit", $checkData['creator'])){
                                            //set privacy
                                            $customShow = $post_vars['privacyCustomSee'];
                                            $customEdit = $post_vars['privacyCustomEdit'];


                                            $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);
                                            $user = getUser();
                                            if(!empty($post_vars['folder']) AND !empty($post_vars['name'])){
                                                $folder = new folder();
                                                $answer = $folder->create($post_vars['folder'], $post_vars['name'], $user, $privacy);

                                            }
                                        }

                                }
                );
        $routes[] = array(
                'path'=>'folders/delete/',
                'callback'=> function($post_vars){



                                        $folderClass = new folder($post_vars['folder_id']);
                                        echo $folderClass->delete();
                                }
                );
        $routes[] = array(
                'path'=>'folders/getItems/',
                'callback'=> function($post_vars){


                                        $classFolder = new folder($post_vars['folder_id']);
                                        echo json_encode($classFolder->getItems());

                                }
                );
        $routes[] = array(
                'path'=>'folders/getPath/',
                'callback'=> function($post_vars){


                                        $classFolder = new folder($post_vars['folder_id']);
                                        echo json_encode($classFolder->getPath(false));


                                }
                );
        $routes[] = array(
                'path'=>'folders/select/',
                'callback'=> function($post_vars){


                                        $requestFunction = function($request){
                                            $folder = new folder($request['folder_id']);
                                            return ($folder->select());
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);

                                }
                );
        $routes[] = array(
                'path'=>'folders/update/',
                'callback'=> function($post_vars){



                                            //set privacy
                                            $customShow = $post_vars['privacyCustomSee'];
                                            $customEdit = $post_vars['privacyCustomEdit'];


                                            $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);
                                            $folder = new folder($post_vars['folder_id']);
                                            echo $folder->update($post_vars['parent_folder'], $post_vars['title'], $privacy);

                                }
                );
        $routes[] = array(
                'path'=>'getPage/',
                'callback'=> function(){

                                }
                );
        $routes[] = array(
                'path'=>'groups/create/',
                'callback'=> function($post_vars){



                                        $invitedUsers = json_decode($post_vars['invitedUsers']);

                                        $groupsClass = new groups();
                                        echo $groupsClass->createGroup($post_vars['title'], $post_vars['type'], $post_vars['description'], $invitedUsers);
                                }
                );
        $routes[] = array(
                'path'=>'groups/delete/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo json_encode($groupsClass->delete($post_vars['group_id']));
                                }
                );
        $routes[] = array(
                'path'=>'groups/get/',
                'callback'=> function($post_vars){


                                        $groups = new groups();
                                        echo json_encode($groups->get($post_vars['userid']));
                                }
                );
        $routes[] = array(
                'path'=>'groups/getData/',
                'callback'=> function($post_vars){







                                        $requestFunction = function($request){
                                            $groupsClass = new groups();
                                            return ($groupsClass->getGroupData($request['group_id']));
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);
                                }
                );
        $routes[] = array(
                'path'=>'groups/getPicture/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo $groupsClass->getPicture($post_vars['group_id']);
                                }
                );
        $routes[] = array(
                'path'=>'groups/getPublicGroups/',
                'callback'=> function(){


                                        $groupsClass = new groups();
                                        echo json_encode($groupsClass->getPublicGroups());
                                }
                );
        $routes[] = array(
                'path'=>'groups/getUsers/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo json_encode($groupsClass->getMembers($post_vars['group_id']));
                                }
                );
        $routes[] = array(
                'path'=>'groups/join/',
                'callback'=> function($post_vars){


                                        $groups = new groups();
                                        $groups->userJoinGroup($post_vars['group_id']);
                                }
                );
        $routes[] = array(
                'path'=>'groups/leave/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        $groupsClass->userLeaveGroup($post_vars['group_id'], getUser());
                                }
                );
        $routes[] = array(
                'path'=>'groups/makeUserAdmin/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo $groupsClass->makeUserAdmin($post_vars['group_id'],$post_vars['user_id']);
                                }
                );
        $routes[] = array(
                'path'=>'groups/removeFromAdmins/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo $groupsClass->groupRemoveAdmin($post_vars['group_id'],$post_vars['user_id']);
                                }
                );
        $routes[] = array(
                'path'=>'groups/removeUser/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo json_encode($groupsClass->deleteUserFromGroup($post_vars['user_id'],$post_vars['group_id']));
                                }
                );
        $routes[] = array(
                'path'=>'groups/update/',
                'callback'=> function($post_vars){


                                        $groupsClass = new groups();
                                        echo json_encode($groupsClass->update($post_vars['group_id'], $post_vars['type'], $post_vars['description'], $post_vars['members_invite']));
                                }
                );
        $routes[] = array(
                'path'=>'groups/uploadGroupPicture/',
                'callback'=> function($post_vars){


                                        $user = new groups();
                                        $user->updateGroupPicture($post_vars['group'], $_FILES['groupPicture']);
                                }
                );
        $routes[] = array(
                'path'=>'handlers/',
                'callback'=> function($post_vars){

                                        include("../../inc/classes/phpfastcache.php");
                                        $cache = new phpFastCache();
                                        $cache->clean();
                                        if(isset($post_vars['handler_title'])){
                                            $handler = new handler();
                                            echo $handler->api($post_vars['handler_title'], $post_vars['action'], $post_vars['parameters']);

                                            die();
                                        }
                                        $handlerType;
                                            $preLoadResultList = array();
                                            foreach($post_vars['request'] AS $request){
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
                                        $api->handleRequest($post_vars['request'], $requestFunction);

                                }
                );
        $routes[] = array(
                'path'=>'IM/addMessage/',
                'callback'=> function($post_vars){



                                        $messageClass = new message();
                                        $message = $messageClass->send($post_vars['receiver_id'], $post_vars['message'], '0', getUser());
                                        if($message)
                                        echo $message;

                                }
                );
        $routes[] = array(
                'path'=>'IM/selectMessages/',
                'callback'=> function($post_vars){


                                        $requestFunction = function($request){
                                            $messages = new message();
                                            return ($messages->getMessagesNew(getUser(), $request['user_b'], $request['offset'], $request['limit']));
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);


                                }
                );
        $routes[] = array(
                'path'=>'item/comments/count/',
                'callback'=> function($post_vars){



                                        $requestFunction = function($request){

                                            $commentClass = new comments();
                                            return $commentClass->countComment($request['type'], $request['item_id']);
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);

                                }
                );
        $routes[] = array(
                'path'=>'item/comments/create/',
                'callback'=> function($post_vars){


                                        $commentClass = new comments();
                                        $commentClass->addComment($post_vars['type'], $post_vars['itemid'], getUser(), $post_vars['comment']);

                                }
                );
        $routes[] = array(
                'path'=>'item/comments/delete/',
                'callback'=> function($post_vars){


                                        $commentClass = new comments();
                                        $commentClass->delete($post_vars['comment_id']);

                                }
                );
        $routes[] = array(
                'path'=>'item/comments/load/',
                'callback'=> function($post_vars){



                                        $classComments = new comments();
                                        echo json_encode($classComments->loadComments($post_vars['type'], $post_vars['item_id']), true);
                                }
                );
        $routes[] = array(
                'path'=>'item/getItemThumb/',
                'callback'=> function($post_vars){


                                        $requestFunction = function($request){
                                            $item = new item();
                                            return $item->showItemThumb($request['itemType'], $request['itemId']);
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);

                                }
                );
        $routes[] = array(
                'path'=>'item/getOptions/',
                'callback'=> function($post_vars){


                                        $requestFunction = function($request){
                                            $item = new contextMenu($request['type'], $request['itemId']);
                                            return $item->getOptions();
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);

                                }
                );
        $routes[] = array(
                'path'=>'item/getScore/',
                'callback'=> function($post_vars){



                                        $requestFunction = function($request){
                                            $item = new item($request['type'], $request['itemId']);
                                            return $item->getScore();
                                        };

                                        $api = new api();
                                        $api->handleRequest($post_vars['request'], $requestFunction);
                                }
                );
        $routes[] = array(
                'path'=>'item/loadMiniFileBrowser/',
                'callback'=> function($post_vars){



                                        fileSystem::showMiniFileBrowser($post_vars['folder'], $post_vars['element'], $post_vars['level'], $post_vars['showGrid'], $post_vars['select']);
                                }
                );
        $routes[] = array(
                'path'=>'item/makeDeletable/',
                'callback'=> function($post_vars){



                                        $item = new item($post_vars['type'], $post_vars['itemId']);
                                        $item->makeDeletable($post_vars['type'], $post_vars['itemId']);
                                }
                );
        $routes[] = array(
                'path'=>'item/makeUndeletable/',
                'callback'=> function($post_vars){



                                        $item = new item($post_vars['type'], $post_vars['itemId']);
                                        $item->makeUndeletable($post_vars['type'], $post_vars['itemId']);
                                }
                );
        $routes[] = array(
                'path'=>'item/privacy/',
                'callback'=> function($post_vars){

                                            if(proofLogin()){
                                                            //set privacy
                                                            $customShow = $post_vars['privacyCustomSee'];
                                                            $customEdit = $post_vars['privacyCustomEdit'];

                                                            $privacyString = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);


                                                            $privacy = new privacy();
                                                            $privacy->updateItemPrivacy($post_vars['type'], $post_vars['itemId'], $privacyString);
                                            }

                                }
                );
        $routes[] = array(
                'path'=>'item/privacy/load/',
                'callback'=> function($post_vars){

                                                        //is used bei js privacy.load to load privacy selection with privacy = $post_vars['val'] into DOM
                                                $editable =  ($post_vars['editable'] == 'true'); //str to bool

                                                        $privacyClass = new privacy($post_vars['val']);
                                                        $privacyClass->showPrivacySettings($editable);
                                }
                );
        $routes[] = array(
                'path'=>'item/protect/',
                'callback'=> function($post_vars){



                                        $item = new item($post_vars['type'], $post_vars['itemId']);
                                        $item->protect($post_vars['type'], $post_vars['itemId']);
                                }
                );
        $routes[] = array(
                'path'=>'item/removeProtection/',
                'callback'=> function($post_vars){



                                        $item = new item($post_vars['type'], $post_vars['itemId']);
                                        $item->removeProtection($post_vars['type'], $post_vars['itemId']);
                                }
                );
        $routes[] = array(
                'path'=>'item/score/scoreMinus/',
                'callback'=> function($post_vars){


                                        $type = $post_vars['type'];
                                        $typeid = $post_vars['item'];//
                                        $item = new item($type, $typeid);
                                        echo $item->minusOne();

                                }
                );
        $routes[] = array(
                'path'=>'item/score/scorePlus/',
                'callback'=> function($post_vars){


                                        $type = $post_vars['type'];
                                        $typeid = $post_vars['item'];//
                                        $item = new item($type, $typeid);
                                        echo $item->plusOne();

                                }
                );
                
    //links            
                
        $routes[] = array(
                'path'=>'links/create/',
                'callback'=> function($post_vars){



                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];
                                        $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);
                                        $linkClass = new link();
                                        echo $linkClass->create($post_vars['folder'], $post_vars['title'], $post_vars['type'], $privacy, $post_vars['link']);
                                }
                );
        $routes[] = array(
                'path'=>'links/delete/',
                'callback'=> function($post_vars){



                                        $linkClass = new link();
                                        echo $linkClass->deleteLink($post_vars['link_id']);
                                }
                );
        $routes[] = array(
                'path'=>'links/select/',
                'callback'=> function($post_vars){



                                        $linkClass = new link();
                                        echo json_encode($linkClass->select($post_vars['link_id']));
                                }
                );
        $routes[] = array(
                'path'=>'links/update/',
                'callback'=> function($post_vars){



                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];
                                        $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);
                                        $linkClass = new link();
                                        echo $linkClass->update($post_vars['link_id'], $post_vars['element'], $post_vars['title'], $post_vars['link'], $post_vars['type'], $privacy);
                                }
                );
                
                
    //playlists
                
        $routes[] = array(
                'path'=>'playlists/create/',
                'callback'=> function($post_vars){

                                        error_reporting(E_ALL);

                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];

                                        $privacy = exploitPrivacy($post_vars['privacyPublic'], $post_vars['privacyHidden'], $customEdit, $customShow);

                                        $playlistClass = new playlist();
                                        echo $playlistClass->create(array('element'=>$post_vars['element'],'title'=>$post_vars['title'],'privacy'=>$privacy));

                                }
                );
        $routes[] = array(
                'path'=>'playlists/getGroupPlaylists/',
                'callback'=> function($post_vars){


                                        $playlist = new playlist();
                                        echo json_encode($playlist->getGroupPlaylistArray($post_vars['type'], $post_vars['group_id']));

                                }
                );
        $routes[] = array(
                'path'=>'playlists/getPublicPlaylists/',
                'callback'=> function(){


                                        $playlist = new playlist();
                                        echo json_encode($playlist->getPublicPlaylists());

                                }
                );
        $routes[] = array(
                'path'=>'playlists/getUserPlaylists/',
                'callback'=> function($post_vars){


                                        $playlist = new playlist();
                                        echo json_encode($playlist->getUserPlaylistArray($post_vars['type'], $post_vars['user']));

                                }
                );
        $routes[] = array(
                'path'=>'playlists/idToTitle/',
                'callback'=> function($post_vars){


                                        $playlist = new playlist($post_vars['playlist_id']);
                                        echo $playlist->getPlaylistTitle();

                                }
                );
        $routes[] = array(
                'path'=>'playlists/pushItem/',
                'callback'=> function($post_vars){

                                        $playlistClass = new playlist($post_vars['playlist']);
                                        echo $playlistClass->pushItem(array('item_type'=>$post_vars['item_type'],'item_id'=>$post_vars['item_id']));

                                }
                );
        $routes[] = array(
                'path'=>'playlists/removeItem/',
                'callback'=> function($post_vars){

                                        $playlistClass = new playlist($post_vars['playlist']);
                                        echo $playlistClass->removeItem($post_vars['order_id']);

                                }
                );
        $routes[] = array(
                'path'=>'playlists/select/',
                'callback'=> function($post_vars){


                                        $playlist = new playlist($post_vars['playlist_id']);
                                        echo json_encode($playlist->select());

                                }
                );
        $routes[] = array(
                'path'=>'playlists/update/',
                'callback'=> function($post_vars){



                                        //set privacy
                                        $customShow = $post_vars['privacyCustomSee'];
                                        $customEdit = $post_vars['privacyCustomEdit'];
                                        $privacy = exploitPrivacy("".$post_vars['privacyPublic']."", "".$post_vars['privacyHidden']."", $customEdit, $customShow);
                                        $playlists = new playlists($post_vars['playlist_id']);
                                        echo $playlists->update($post_vars['title'], $privacy);
                                }
                );
                
    //reload
                
        $routes[] = array(
                'path'=>'reload/',
                'callback'=> function($post_vars){


                                        echo json_encode(universe::reload($post_vars['request']));




                                }
                );
                
    //search
                
        $routes[] = array(
                'path'=>'search/extendResults/',
                'callback'=> function($post_vars){


                                        include("../../../inc/classes/class_search.php");


                                            $search = new search($post_vars['search']);

                                            echo $search->parseSearchResults();

                                        echo '<script>';
                                            echo"search.initResultHandlers('".htmlentities($post_vars['search'])."');";
                                        echo '</script>';
                                }
                );
        $routes[] = array(
                'path'=>'search/loadDockList/',
                'callback'=> function($post_vars){


                                        include("../../../inc/classes/class_search.php");


                                            $search = new search($post_vars['query']);

                                            echo $search->parseSearchResultsJSON();


                                }
                );
        $routes[] = array(
                'path'=>'search/query/',
                'callback'=> function($post_vars){

                                        include('../../../inc/classes/class_search.php');

                                        $search = new search($post_vars['query']);

                                        echo $search->parseSearchResultsJSON();
                                }
                );
                
                
    //sessions
        $routes[] = array(
                'path'=>'sessions/checkFingerprint/',
                'callback'=> function(){


                                        include("inc/classes/class_sessions.php");


                                        //@sec
                                }
                );
        $routes[] = array(
                'path'=>'sessions/create/',
                'callback'=> function($post_vars){


                                        include("inc/classes/class_sessions.php");


                                        $sessions = new sessions();

                                        echo $sessions->createSession($post_vars['fingerprint'], $post_vars['type'], $post_vars['title']);
                                }
                );
        $routes[] = array(
                'path'=>'sessions/getSessionInfo/',
                'callback'=> function($post_vars){




                                        $sessions = new sessions();
                                        echo $sessions->getSessionInformation($post_vars['fingerprint']);
                                }
                );
        $routes[] = array(
                'path'=>'sessions/updateFingerprint/',
                'callback'=> function($post_vars){


                                        include("inc/classes/class_sessions.php");


                                        $sessions = new sessions();

                                        echo $sessions->updateFingerprint($post_vars['old_fingerprint'], $post_vars['new_fingerprint']);
                                }
                );
        $routes[] = array(
                'path'=>'sessions/updateSessionInfo/',
                'callback'=> function($post_vars){


                                        include("inc/classes/class_sessions.php");


                                        $sessions = new sessions();
                                        $sessions->updateSessionInformation($post_vars['fingerprint'], $post_vars['key'], $post_vars['value']);
                                }
                );
                
        //shortcuts
                
            $routes[] = array(
                    'path'=>'shortcuts/create/',
                    'callback'=> function($post_vars){


                                            $shortcutClass = new shortcut();
                                            $shortcutClass->create($post_vars['parent_type'], $post_vars['parent_id'], $post_vars['type'], $post_vars['type_id'], $post_vars['title']);

                                    }
                    );
            $routes[] = array(
                    'path'=>'shortcuts/delete/',
                    'callback'=> function($post_vars){



                                            $shortcutClass = new shortcut();
                                            echo $shortcutClass->delete($post_vars['shortcut_id']);
                                    }
                    );
            $routes[] = array(
                    'path'=>'shortcuts/getData/',
                    'callback'=> function($post_vars){

                                            include('../../../inc/classes/class_search.php');

                                            $shortcutClass = new shortcut();

                                            echo json_encode($shortcutClass->getData($post_vars['shortcut_id']));
                                    }
                    );
          
        //salts       
            $routes[] = array(
                    'path'=>'salts/get/',
                    'callback'=> function($post_vars){
                                            $saltClass = new salt();
                                            echo $saltClass->get($post_vars['type'], $post_vars['itemId']);

                                    }
                    );    
                    
                    
                    
                    
                
        //user
            $routes[] = array(
                    'path'=>'user/authorize/',
                    'callback'=> function($post_vars){
                                            //checks if user is authorized, to edit an item with privacy $_POST['privacy'].
                                            echo authorize($post_vars['privacy'], 'edit', $post_vars['author']);
                                    }
                    );   
		
                    
            
            $routes[] = array(
                    'path'=>'user/getUserPicture/',
                    'callback'=> function($post_vars){
                                                    $api = new api();
                                                    echo $api->getUserPicture($post_vars['request']);
                                    }
                    );
                    
            $routes[] = array(
                    'path'=>'user/create/',
                    'callback'=> function($post_vars){

                                                    $classUser = new user();
                                                    $classUser->create($post_vars['username'], $post_vars['password'], $post_vars['authSalt'], $post_vars['keySalt'], $post_vars['privateKey'], $post_vars['publicKey']);


                                                    echo "1";
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/deleteAccount/',
                    'callback'=> function($post_vars){

                                            $classUser = new user();
                                            echo $classUser->deleteAccount($post_vars['passwordHash']);
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/getAllData/',
                    'callback'=> function($post_vars){


                                            $user = new user($post_vars['userid']);
                                            if($post_vars['userid'] == getUser())
                                            echo json_encode($user->getData());
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/getGroups/',
                    'callback'=> function(){


                                            //groups
                                            $groupsClass = new groups();
                                            echo json_encode($groups = $groupsClass->getGroups());
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/getLastActivity/',
                    'callback'=> function($post_vars){





                                            $requestFunction = function($request){
                                                $user = new user();
                                                return $user->getLastActivity($request['userid']);
                                            };

                                            $api = new api();
                                            $api->handleRequest($post_vars['request'], $requestFunction);

                                    }
                    );
            $routes[] = array(
                    'path'=>'user/getPicture/',
                    'callback'=> function($post_vars){

                                            $requestFunction = function($request){
                                                $user = new user();
                                                return $user->getUserPictureBASE64($request['userid']);
                                            };

                                            $api = new api();
                                            $api->handleRequest($post_vars['request'], $requestFunction);

                                    }
                    );
            $routes[] = array(
                    'path'=>'user/getPrivacy/',
                    'callback'=> function(){


                                            $user = new userPrivacy(getUser());
                                            echo json_encode($user->getRights());
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/getProfileInfo/',
                    'callback'=> function($post_vars){

                                            $requestFunction = function($request){
                                                $user = new user();
                                                return $user->getProfileInfo($request['user_id']);
                                            };

                                            $api = new api();
                                            $api->handleRequest($post_vars['request'], $requestFunction);

                                    }
                    );
            $routes[] = array(
                    'path'=>'user/logout/',
                    'callback'=> function(){



                                                    unset($_SESSION['userid']);
                                            session_unset();
                                                    echo "1";
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/proofLogin/',
                    'callback'=> function(){



                                            echo proofLogin();
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/updatePassword/',
                    'callback'=> function($post_vars){


                                            $user = new user();
                                            echo $user->updatePassword($post_vars['oldPasswordHash'], $post_vars['newPasswordHash'], $post_vars['newAuthSalt'], getUser());
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/updatePrivacy/',
                    'callback'=> function($post_vars){



                                            var_dump($post_vars['values']);

                                            $user = new userPrivacy(getUser());
                                            $user->updateRights($post_vars['values']);


                                    }
                    );
            $routes[] = array(
                    'path'=>'user/updateProfileInfo/',
                    'callback'=> function($post_vars){



                                            $user = new user($post_vars['userid']);
                                            echo json_encode($user->updateProfileInfo($post_vars['realname'], $post_vars['city'], $post_vars['hometown'], $post_vars['birthdate'], $post_vars['school'], $post_vars['university'], $post_vars['work']));
                                    }
                    );
            $routes[] = array(
                    'path'=>'user/uploadUserPicture/',
                    'callback'=> function(){


                                            $user = new user(getUser());
                                            $user->updateUserPicture($_FILES['userpicture']);
                                    }
                    );
        return $routes;
    }
}