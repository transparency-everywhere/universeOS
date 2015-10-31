<?php
/*
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


require_once('class_xml.php');

function xml2array ( $xmlObject, $out = array () )
{
    foreach ( (array) $xmlObject as $index => $node )
        $out[$index] = ( is_object ( $node ) ) ? xml2array ( $node ) : $node;

    return $out;
}

class search{
    public $query;
    function __construct($query) {
       $this->query = $query;
   }
   
   
    function getSearchResults($type, $limit=49){
        $q = $this->query;
        $qEncoded = urlencode($q);    
        
        $k = $limit+1; //limit+1 because the parseSearchResults function needs to check if the "load all" button is shown
        $results = array();
        
        $handlers = new handler();
        $db = new db();
        
        switch($type){
            case'users':
                
                //search $users
                $userSuggestSQL = $db->shiftResult($db->query("SELECT userid, username FROM user WHERE username LIKE '%$q%' OR realname LIKE '%$q%' OR email='$q' OR userid='$q' LIMIT $k"),'userid');
                foreach ($userSuggestSQL AS $suggestData) {
                    $results[] = $suggestData;
                }
                break;
            case 'folders':
                //folders
                $folderSuggestSQL = $db->shiftResult($db->query("SELECT id, name, privacy, creator FROM folders WHERE name LIKE '%$q%' LIMIT $k"),'id');
                foreach ($folderSuggestSQL AS $suggestData) {
                    if(authorize($suggestData['privacy'], 'show', $suggestData['creator']))
                        $results[] = $suggestData;
                }
                break;
            case 'elements':
                //elements
                $elementSuggestSQL = $db->shiftResult($db->query("SELECT id, title, privacy, author FROM elements WHERE title LIKE '%$q%' LIMIT $k"),'id');
                foreach ($elementSuggestSQL AS $suggestData) {

                    if(authorize($suggestData['privacy'], 'show', $suggestData['author']))       
                        $results[] = $suggestData;
                }
                break;
                
            case 'files':
                $fileSuggestSQL = $db->shiftResult($db->query("SELECT id, title, privacy, type, owner FROM files WHERE title LIKE '%$q%' LIMIT $k"),'id');
                foreach ($fileSuggestSQL AS $suggestData) {

                    if(authorize($suggestData['privacy'], 'show', $suggestData['owner']))       
                        $results[] = $suggestData;
                } 
                break;
            case 'groups':
                //groups
                $groupSuggestSQL = $db->shiftResult($db->query("SELECT id, title FROM groups WHERE title LIKE '%$q%' AND public='1' LIMIT $k"),'id');
                foreach ($groupSuggestSQL AS $suggestData) {
                    $results[] = $suggestData;
                }
                break;
                
            case 'wiki':
                $handlers = new handler();
                foreach(json_decode($handlers->api('wikipedia', 'query', array('query'=>$qEncoded, 'offset'=> 0, 'max_results'=>$k))) AS $result){
                    $results[] = $result;
                }
                break;
                
            case 'youtube':
                $handlers = new handler();
                foreach(json_decode($handlers->api('youtube', 'query', array('query'=>$qEncoded, 'offset'=> 0, 'max_results'=>$k))) AS $result){
                    $results[] = $result;
                }
                break;
                
            case 'vimeo':
                //vimeo
                $vimeo_access_token = 'ace10c8c2e411f308504cfafe237225c';
                $xml2 = xml::curler("https://api.vimeo.com/videos?query=$qEncoded&access_token=$vimeo_access_token");
                foreach ($xml2->entry as $item2) {
                    $youtubeClass = new youtube($item2->id);
                    $vId = $youtubeClass->getId();
                    $results[] = $item2;
                }

                break;
                
            case 'spotify':
                $xml3 = xml::curler("http://ws.spotify.com/search/1/track?q=$qEncoded");
                
                foreach ($xml3->track as $item3) {
                    $results[] = $item3;
                }
                break;
                
        }

        return $results;

    }
    
    function buildLI($icon, $action, $title, $contextMenu=false, $rightclick=false, $dataType=NULL, $dataItemId=NULL){
        if($rightclick){
            $liAppendix = 'class="rightClick" data-type="'.$dataType.'" data-itemId="'.$dataItemId.'"';
        }else{
            $liAppendix = '';
        }
        $title = htmlentities(substr($title, 0, 21));
        
        $output = '<li '.$liAppendix.'>';
            $output .=  $icon;
            $output .= "<a href=\"#\" onclick=\"$action\">$title</a>";
            if($contextMenu){
                $output .= '<span class="icon icon-gear dark"></span><span class="icon white-gear white"></span>';   
            }
        $output .= '</li>';
        
        return $output;
    }
    //loads results if you klick on load more. used in api/search/extendResults
    function extendResults($type, $limit, $offset){
        $items = $this->getSearchResults($type, $limit);
        $i = 0;
        foreach($items AS $item){
            if($i > $offset){
                $resultItems[] = $item;
                
            }
            $i++;
        }
        return $this->parseLists($type, $resultItems, $limit);
    }
    function parseLists($type, $items, $limit=5){
        switch($type){
            case 'users':
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $suggestData){
                    if($i<$limit){
                        $output .= $this->buildLI(showUserPicture($suggestData['userid'], "30", NULL, TRUE), "User.showProfile('".$suggestData['userid']."');", $suggestData['username'], true);

                        $output .= $this->generateSearchResultContext('user', $suggestData['userid']);
                    
                    }else{
                        $loadAll = '<div class="loadAll" data-type="users"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'folders':
                
                $icon = 'folder';
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $suggestData){
                    if($i<$limit){
                            $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', 'openFolder(\''.$suggestData['id'].'\');', $suggestData['name'], true, true, 'folder', $suggestData['id']);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="folder"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
                
                
            case 'elements':
                
                $icon = 'archive';
                $i=0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $suggestData){
                    if($i<$limit){
                            $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', 'openElement(\''.$suggestData['id'].'\');', $suggestData['title']);

                    }else{
                        $loadAll = '<div class="loadAll" data-type="elements"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'files':
                $icon = 'file';
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $suggestData){
                    if($i<$limit){
                    $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "openFile('$suggestData[type]', '$suggestData[id]', '$suggestData[title]');", $suggestData['title']);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="files"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'groups':
                $icon = 'group';
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $suggestData){
                    if($i<$limit){
                        $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "groups.showProfile(".$suggestData['id'].");return false",$suggestData['title']);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="groups"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'wiki':
                $icon = 'wikipedia';
                $i = 0;
                $output .= '<ul class="list resultList">';
                
                $handler = new handler();
                foreach($items AS $item){

                    
                    if($i<$limit){
                        $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "handlers.wikipedia.handler('$item')", $item);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="wiki"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'youtube':
                $icon = 'youtube';
                $i = 0;
                $output .= '<ul class="list resultList">';
                
                $handler = new handler();
                foreach($items AS $item2){

                    if($i<$limit){
                        $title = $handler->api('youtube', 'getTitle', array('url'=>$item2));
                        $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "handlers.youtube.handler('$item2');", substr($title, 0, 23), true);


                        $output .= $this->generateSearchResultContext('youtube', $item2);

                    }else{
                        $loadAll = '<div class="loadAll" data-type="youtube"><a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'spotify':
                $icon = 'spotify';
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $item2){

                                $output .= "<li>";

                                //icon
                                $output .=  '<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>';
                                //title
                                $output .= "<a href=\"$item3[href]\">".substr("$item3->name", 0, 40)."</a>";

                                $output .= "</li>";
                }
                $output .= '</ul>';
                break;
        }
        $output .= $loadAll;
        return $output;
    }
    function parseSearchResultsJSON(){
    
        $result = array();
        
        $icon = 'dark-sc-facebook';
        $iconStyle = 'height: 30px;width: 30px;';
        $output = '';
        if(proofLogin()){
            $users = $this->getSearchResults('users');
        }
            $result['users'] = '';
        if(count($users) > 0){
            $result['users'] = '';
            $result['users'] .= "<div class=\"listContainer\">";
            $result['users']  .= $this->parseLists('users', $users);
            $result['users']  .= '<header>Users</header>';
            $result['users']  .= '</div>';
        }


        $folders = $this->getSearchResults('folders');
        $icon = 'dark-folder';
            $result['folders'] = '';
        if(count($folders) > 0){
            $result['folders']  .= "<div class=\"listContainer\">";
            $result['folders'] .= $this->parseLists('folders', $folders);
            
            
            $result['folders'] .= '<header>Folders</header>';
            $result['folders'] .= '</div>';
        }


        $elements = $this->getSearchResults('elements');
        $icon = 'dark-archive';
            $result['elements'] = '';
        if(count($elements) > 0){
            $result['elements'] .= "<div class=\"listContainer\">";
            $result['elements'] .= $this->parseLists('elements', $folders);
            $result['elements'] .= '<header>Elements</header>';
            $result['elements'] .= '</div>';
        }


        $files = $this->getSearchResults('files');
        $icon = 'dark-file';
            $result['files'] = '';
        if(count($files) > 0){
            $result['files'] .= "<div class=\"listContainer\">";
            $result['files'] .= $this->parseLists('files', $files);
            
            $result['files'] .= '<header>Files</header>';
            $result['files'] .= '</div>';
        }

        $groups = $this->getSearchResults('groups');
        $icon = 'dark-group';
        if(count($groups) > 0){
            $result['groups'] .= "<div class=\"listContainer\">";
            $result['groups'] .= $this->parseLists('groups', $groups);
            $result['groups'] .= '<header>Groups</header>';
            $result['groups'] .= '</div>';
        }

            $wikis = $this->getSearchResults('wiki');
            $icon = 'dark-wikipedia';
            $result['wikis'] = '';
            if(count($wikis) > 0){
                $result['wikis'] .= "<div class=\"listContainer\">";
                $result['wikis'] .= $this->parseLists('wiki', $wikis);
                $result['wikis'] .= '<header>Wiki</header>';
                $result['wikis'] .= '</div>';

        }

            $youtubes = $this->getSearchResults('youtube');
        
            
//            unset($youtubes[0]); // remove item at index 0
//            $youtubes = array_values($youtubes); // 'reindex' array
//        
        
        $icon = 'dark-youtube';
            $result['youtubes'] = '';
        if(count($youtubes) > 0){
            
            
            $result['youtubes'] .= "<div class=\"listContainer\">";
            $result['youtubes'] .= $this->parseLists('youtube', $youtubes);
            $result['youtubes'] .= '<header>Youtube</header>';
            $result['youtubes'] .= '</div>';
        }

        $icon = 'dark-spotify';
        $spotifies = $this->getSearchResults('spotifies');
            $result['spotifies'] = '';
        if(count($spotifies) > 0){
            $result['spotifies'] .= "<div class=\"listContainer\">";
            $result['spotifies'] .= $this->parseLists('spotifies', $spotifies);
            $result['spotifies'] .= '<header>Spotify</header>';
            $result['spotifies'] .= '</div>';
        }

        return json_encode($result);
}
    function parseSearchResults(){
    
        
        $icon = 'dark-sc-facebook';
        $iconStyle = 'height: 30px;width: 30px;';
        $output = '';
        if(proofLogin()){
            $users = $this->getSearchResults('users');
        }
        if(count($users) > 0){

            $output .= "<div class=\"listContainer\">";
            
            $output .= $this->parseLists('users', $users);
            $output .= '<header>Users</header>';
            $output .= '</div>';
        }


        $folders = $this->getSearchResults('folders');
        $icon = 'dark-folder';
        if(count($folders) > 0){
            $output .= "<div class=\"listContainer\">";
            $output .= $this->parseLists('folders', $folders);
            
            
            $output .= '<header>Folders</header>';
            $output .= '</div>';
        }


        $elements = $this->getSearchResults('elements');
        $icon = 'dark-archive';
        if(count($elements) > 0){
            $output .= "<div class=\"listContainer\">";
            $output .= $this->parseLists('elements', $folders);
            $output .= '<header>Elements</header>';
            $output .= '</div>';
        }


        $files = $this->getSearchResults('files');
        $icon = 'dark-file';
        if(count($files) > 0){
            $output .= "<div class=\"listContainer\">";
            $output .= $this->parseLists('files', $files);
            
            $output .= '<header>Files</header>';
            $output .= '</div>';
        }

        $groups = $this->getSearchResults('groups');
        $icon = 'dark-group';
        if(count($groups) > 0){
            $output .= "<div class=\"listContainer\">";
            $output .= $this->parseLists('groups', $groups);
            $output .= '<header>Groups</header>';
            $output .= '</div>';
        }

            $wikis = $this->getSearchResults('wiki');
            $icon = 'dark-wikipedia';
            if(count($wikis) > 0){
                $output .= "<div class=\"listContainer\">";
                $output .= $this->parseLists('wiki', $wikis);
            $output .= '<header>Wiki</header>';
            $output .= '</div>';

        }

            $youtubes = $this->getSearchResults('youtube');
        
            
            unset($youtubes[0]); // remove item at index 0
            $youtubes = array_values($youtubes); // 'reindex' array
        
        
        $icon = 'dark-youtube';
        if(count($youtubes) > 0){
            
            
            $output .= "<div class=\"listContainer\">";
            $output .= $this->parseLists('youtube', $youtubes);
            $output .= '<header>Youtube</header>';
            $output .= '</div>';
        }

        $icon = 'dark-spotify';
        $spotifies = $this->getSearchResults('spotifies');
        if(count($spotifies) > 0){
            $output .= "<div class=\"listContainer\">";
            $output .= $this->parseLists('spotifies', $spotifies);
            $output .= '<header>Spotify</header>';
            $output .= '</div>';
        }

        return $output;
}

    function generateSearchResultContext($type, $item_id){
        
        switch($type){
            case 'user':
                if(proofLogin()){
                    
                    $options[] = array('icon'=>'blue-plus', 'title'=>'Add User', 'action'=>'buddylist.addBuddy(\''.$item_id.'\'); $(this).text(\'request sent\');');
                
                }
                break;
            
            case 'youtube':
                if(proofLogin()){
                    $options[] = array('icon'=>'blue-plus', 'title'=>'Add to Playlist', 'action'=>'playlists.pushItemToPlaylistForm(\'youtube\', \''.$item_id.'\');');
                }
                $options[] = array('icon'=>'blue-play', 'title'=>'Play', 'action'=>"openFile('youTube', '', '".urlencode(substr("$item2->title", 0, 10))."', '$vId');");
                break;
        }
        
        $output = '<li class="searchContext">';
            $output .= '<ul>';
                foreach($options AS $option){
                    $output .= '<li>';
                        $output .= '<span class="icon '.$option['icon'].'"></span>';
                        $output .= '<a href="#" onclick="'.$option['action'].'">';
                        $output .= $option['title'];
                        $output .= '</a>';
                    $output .= '</li>';
                }
            $output .= '</ul>';
        $output .= '</li>';
        
        return $output;
    }
   
}



class services{
    function search($service, $q, $limit, $offset){
        
    }
}