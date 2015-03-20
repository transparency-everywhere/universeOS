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
        
        switch($type){
            case'users':
                
                //search $users
                $userSuggestSQL = mysql_query("SELECT userid, username FROM user WHERE username LIKE '%$q%' OR realname LIKE '%$q%' OR email='$q' OR userid='$q' LIMIT $k");
                while ($suggestData = mysql_fetch_array($userSuggestSQL)) {
                    $results[] = $suggestData;
                }
                break;
            case 'folders':
                //folders
                $folderSuggestSQL = mysql_query("SELECT id, name, privacy, creator FROM folders WHERE name LIKE '%$q%' LIMIT $k");
                while ($suggestData = mysql_fetch_array($folderSuggestSQL)) {
                    if(authorize($suggestData['privacy'], 'show', $suggestData['creator']))
                        $results[] = $suggestData;
                }
                break;
            case 'elements':
                //elements
                $elementSuggestSQL = mysql_query("SELECT id, title, privacy, author FROM elements WHERE title LIKE '%$q%' LIMIT $k");
                while ($suggestData = mysql_fetch_array($elementSuggestSQL)) {

                    if(authorize($suggestData['privacy'], 'show', $suggestData['author']))       
                        $results[] = $suggestData;
                }
                break;
                
            case 'files':
                $fileSuggestSQL = mysql_query("SELECT id, title, privacy, type, owner FROM files WHERE title LIKE '%$q%' LIMIT $k");
                while ($suggestData = mysql_fetch_array($fileSuggestSQL)) {

                    if(authorize($suggestData['privacy'], 'show', $suggestData['owner']))       
                        $results[] = $suggestData;
                } 
                break;
            case 'groups':
                //groups
                $groupSuggestSQL = mysql_query("SELECT id, title FROM groups WHERE title LIKE '%$q%' AND public='1' LIMIT $k");
                while ($suggestData = mysql_fetch_array($groupSuggestSQL)) {
                    $results[] = $suggestData;
                }
                break;
                
            case 'wiki':
                $xml = xml::curler("http://en.wikipedia.org/w/api.php?action=opensearch&limit=$k&namespace=0&format=xml&search=$qEncoded");
                foreach ($xml->Section->Item as $item) {
                    $results[]  = $item;
                }
                break;
                
            case 'youtube':

                //youtube
                $xml2 = xml::curler("http://gdata.youtube.com/feeds/api/videos?max-results=$k&restriction=DE&q=$qEncoded");
                foreach ($xml2->entry as $item2) {
                    $youtubeClass = new youtube($item2->id);
                    $vId = $youtubeClass->getId();
                    $results[] = $item2;
                }

                break;
                
            case 'spotify':
                $xml3 = xml::curler("http://ws.spotify.com/search/1/track?q=$qEncoded");
                $i = 0;
                foreach ($xml3->track as $item3) {
                    $results[] = $item3;
                }
                break;
                
        }

        return $results;

    }
    
    function buildLI($icon, $action, $title, $contextMenu=false, $rightclick=false, $dataType=NULL, $dataItemId=NULL){
        if($rightclick){
            $liAppendix = 'class"rightClick" data-type="'.$dataType.'" data-itemId="'.$dataItemId.'"';
        }else{
            $liAppendix = '';
        }
        $title = htmlentities(substr($title, 0, 33));
        
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
        $numberOfItems = count($items);
        switch($type){
            case 'users':
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $suggestData){
                    if($i<$limit){
                        $output .= $this->buildLI(showUserPicture($suggestData['userid'], "30", NULL, TRUE), "showProfile('".$suggestData['userid']."');", $suggestData['username'], true);

                        $output .= $this->generateSearchResultContext('user', $suggestData['userid']);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="users">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
                    }
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
                        $loadAll = '<div class="loadAll" data-type="folder">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
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
                            $output .= $this->buildLI('<<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', 'openElement(\''.$suggestData['id'].'\');', $suggestData['title']);

                    }else{
                        $loadAll = '<div class="loadAll" data-type="elements">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
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
                        $loadAll = '<div class="loadAll" data-type="files">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
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
                        $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "reader.applicationVar.show(); reader.tabs.addTab('".$suggestData['title']."', '',gui.loadPage('./group.php?id=".$suggestData['id']."'));return false",$suggestData['title']);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="groups">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'wiki':
                $icon = 'wikipedia';
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $item){

                    if($i<$limit){
                    $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "openFile('wikipedia', '".urlencode($item->Text)."', '".urlencode(substr("$item->Text", 0, 10))."');", $item->Text);
                    }else{
                        $loadAll = '<div class="loadAll" data-type="wiki">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
                    }
                    $i++;
                }
                $output .= '</ul>';
                break;
            case 'youtube':
                $icon = 'youtube';
                $i = 0;
                $output .= '<ul class="list resultList">';
                foreach($items AS $item2){

                    if($i<$limit){

                        $youtubeClass = new youtube($item2->id);
                        $vId = $youtubeClass->getId();
                        $output .= $this->buildLI('<span class="icon dark-'.$icon.' dark" style="'.$iconStyle.'"></span><span class="icon white-'.$icon.' white" style="'.$iconStyle.'"></span>', "openFile('youTube', '', '".urlencode(substr("$item2->title", 0, 10))."', '$vId');", substr("$item2->title", 0, 40), true);


                        $data = xml2array($item2->link);
                        $output .= $this->generateSearchResultContext('youtube', $data['@attributes']['href']);

                    }else{
                        $loadAll = '<div class="loadAll" data-type="youtube">results '.$i.' of '.$numberOfItems.' <a href="#">show all</a></div>';
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
                    
                    $options[] = array('icon'=>'blue-plus', 'title'=>'Add User', 'action'=>'showProfile(\''.$item_id.'\');');
                
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



