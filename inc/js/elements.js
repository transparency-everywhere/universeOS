//        This file is published by transparency - everywhere with the best deeds.
//        Check transparency - everywhere.com for further information.
//        Licensed under the CC License, Version 4.0 (the "License");
//        you may not use this file except in compliance with the License.
//        You may obtain a copy of the License at
//        
//        https://creativecommons.org/licenses/by/4.0/legalcode
//        
//        Unless required by applicable law or agreed to in writing, software
//        distributed under the License is distributed on an "AS IS" BASIS,
//        WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//        See the License for the specific language governing permissions and
//        limitations under the License.
//        @author nicZem for Tranpanrency - everywhere.com
        

var elements = new function(){
    
    this.open = function(element){
        filesystem.show();
        var elementData = this.getData(element);
        var elementAuthorData = this.getAuthorData(elementData['author']);
        var link = "./modules/reader/showfile.php?type=" + elementData['type'];
        var html = filesystem.generateLeftNav();
        html += '    <div id="showElement" class="frameRight">';
        html += '        <h2 style="margin-left: 5%; margin-bottom:0px; margin-top:5%;">';
        html += '            ' + htmlspecialchars(elementData['title']) + '&nbsp;<i class="icon-info-sign" onclick="$(\'.elementInfo(' + elementData['id'] + '\').slideDown();"></i>';
        html += '        </h2>';
        html += '        <div class="elementInfo' + elementData['id'] + ' hidden">';
        html += '    <table width="100%" class="fileBox" cellspacing="0">';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td width="110px">Element-Type:</td>';
        html += '            <td>' + elementData['type'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#e5f2ff" height="35px">';
        html += '            <td width="110px">Author:</td>';
        html += '            <td>' + elementData['creator'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td>Title:</td>';
        html += '            <td>' + elementData['name'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#e5f2ff" height="35px">';
        html += '            <td>Year:</td>';
        html += '            <td>' + elementData['year'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td>Original Title:</td>';
        html += '            <td>' + elementData['originalTitle'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#e5f2ff" height="35px">';
        html += '            <td>Language:</td>';
        html += '            <td>' + elementData['language'] + '</td>';
        html += '        </tr>';
        html += '        <tr bgcolor="#FFFFFF" height="35px">';
        html += '            <td>License:</td>';
        html += '            <td>' + elementData['license'] + '</td>';
        html += '        </tr>';
        html += '    </table>';
        html += '    <div style="display:none; float:left; width:40%; margin-top: 3%; background: #c9c9c9; height: 250px;">';
        html += '    </div>';
        html += '    </div>';
        html += '    <div style=" clear: left;margin-left: 5%;">';
        html += '        <a target="_blank" href="http://www.amazon.de/gp/search?ie=UTF8&camp=1638&creative=6742&index=aps&keywords=' + htmlentities(elementData['title']) + '%20' + elementData['creator'] + '&linkCode=ur2&tag=universeos-21">find on amazon</a><img src="http://www.assoc-amazon.de/e/ir?t=universeos-21&l=ur2&o=3" width="1" height="1" border="0" alt="" style="border:none !important; margin:0px !important;" />';
        html += '    </div>';
        html += '    <h3 style="margin-left:5%; margin-bottom:-10px;"><i class="icon-file"></i>&nbsp;Files</h3>';
        html += '        <table cellspacing="0" class="filetable" style="width: 90%; margin-left: 5%; border: 1px solid #c9c9c9; border-right: 1px solid #c9c9c9;">';
        html += '            <tr class="grayBar" height="35">';
        html += '                <td></td>';
        html += '                <td>Title</td>';
        html += '                <td></td>';
        html += '                <td></td>';
        html += '                <td></td>';
        html += '            </tr>';
//            html += elements.showFileList(elementData['id']); //muss ich noch machen :(
        html += '        </table>';
        html += '    <center style="margin-top: 20px; margin-bottom: 20px;">';
        if(proofLogin()){
            html += '    	<a class="btn btn-info" href="#" onclick="filesystem.showCreateUFFForm(\'' + element + '\'); " target="submitter"><i class="icon-file icon-white"></i> Create Document</a>';
            html += '        <a href="#" onclick="filesystem.openUploadTab(\'' + element + '\');" class="btn btn-info"><i class="icon-file icon-white"></i>&nbsp;Upload File</a>';
            html += '        &nbsp;<a href="javascript: links.showCreateLinkForm(\'' + element + '\');" class="btn btn-info"><i class="icon white-link"></i>&nbsp;Add Link</a>';
        }
        html += '    </center>';
        html += '    <hr>';
        html += '    <div>';
//            html += comments.show(elementData['id']); //nic macht function in js fertig
        html += '    </div>';
        html += '</div>';
        filesystem.tabs.addTab(elementData['title'], '', html);
    };
    
    this.showFileList = function(element_id){
        var fileList = this.getFileList;
        var files = fileList['files'];
        var links = fileList['links'];
        var shortcuts = fileList['shortcuts'];
        var html;
        var link;
        var rightLink;
        var image;
        var i = 0;
        $.each(files, function(key, value){
            i++;
            if(value['type'] == "audio/mpeg"){
                link = "openFile('" + value['type'] + "', '" + value['id'] + "', '" + value['title'] + "')";
                rightLink = "startPlayer('file', '" + value['id'] + "')";
                image = "../music.png";
            }
            else if(value['type'] == "video/mp4"){
                link = "openFile('video', '" + value['id'] + "', '" + value['title'] + "');";
                rightLink = "reader.tabs.addTab('See " + value['title'] + "', '',gui.loadPage('./modules/reader/player.php?id='" + value['id'] + "')); return false";
            }
            else if(value['type'] == "UFF"){
                link = "openFile('" + value['type'] + "', '" + value['id'] + "', '" + value['title'] + "')";
            }
            else if(value['type'] == "text/plain" || value['type'] == "application/pdf" || value['type'] == "text/x-c++"){
                link = "openFile('document', '" + value['id'] + "', '" + value['title'] + "');";
            }
            else if(value['type'] == "image/jpeg" || value['type'] == "image/png" || value['type'] == "image/gif"){
                //if a image is opened the tab is not named like the file
                //it is named like the parent element, because images are
                //shown in a gallery with all the images listed in the parent
                //element
                var elementData = this.getData(value['folder']);
                link = "openFile('image', '" + value['id'] + "', '" + elementData['title'] + "');";
            }
            
            
//$filesClass = new files();
//$image = $filesClass->getFileIcon($fileListData['type']);
//    ?>
//    <tr class="strippedRow file_<?=$fileListData['id'];?>" oncontextmenu="showMenu('file<?=$fileListData['id'];?>'); return false;" height="40px">
//        <td width="30px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$fileListData['type'];?>" height="22"></td>
//        <td><a href="<?=$subpath;?>out/?file=<?=$fileListData['id'];?>" onclick="<?=$link;?> return false"><?=substr($fileListData[title],0,30);?></a></td>
//        <td width="80" align="right">
//
//                <?php
//                $item = new item('file', $fileListData['id']);
//                echo $item->showScore();
//                ?>
//        </td>
//        <td width="50"><? if($fileListData['download']){ ?>
//                    <a href="./out/download/?fileId=<?=$fileListData['id'];?>" target="submitter" class="btn btn-mini" title="download file"><i class="icon-download"></i></a>
//                <? } 
//                if(!$git){
//                    $contextMenu = new contextMenu('file', $fileListData['id']);
//                    echo $contextMenu->showItemSettings();
//                }?></td>
//    </tr>
//    <?php
//    if(!$git){
//        $contextMenu = new contextMenu("file", $fileListData['id'], $title10, $openFileType);
//        $contextMenu->showRightClick();
//    }
//
//}}            



        });        
        
        
//while($fileListData = mysql_fetch_array($fileListSQL)) {
//$i++;
//if(authorize($fileListData['privacy'], "show", $fileListData['owner'])){
//$title10 = substr("$fileListData[title]", 0, 10);
//$link = "openFile('".$fileListData['type']."', '".$fileListData['id']."', '$title10');";
//if($fileListData['type'] == "audio/mpeg"){
//    $rightLink = "startPlayer('file', '".$fileListData['id']."')";
//    $image = "../music.png";
//}
//else if($fileListData['type'] == "video/mp4"){
//    //define link for openFileFunction
//    $openFileType = "video";
//
//    //define openFile function
//    $link = "openFile('$openFileType', '".$fileListData['id']."', '$title10');";
//
//    $rightLink = "reader.tabs.addTab('See $title10', '',gui.loadPage('./modules/reader/player.php?id='".$fileListData['id']."')); return false";
//}
//else if($fileListData['type'] == "UFF"){
////standard from know on (19.02.2013)
//
//    //define link for openFileFunction
//    $openFileType = "UFF";
//
//    //define openFile function
//    $link = "openFile('$openFileType', '".$fileListData['id']."', '$title10');";
//}
//else if($fileListData['type'] == "text/plain" OR $fileListData['type'] == "application/pdf" OR $fileListData['type'] == "text/x-c++"){
////standard from know on (19.02.2013)
//
//    //define link for openFileFunction
//    $openFileType = "document";
//
//    //define openFile function
//    $link = "openFile('$openFileType', '".$fileListData['id']."', '$title10');";
//}
//else if($fileListData['type'] == "image/jpeg" OR $fileListData['type'] == "image/png" OR $fileListData['type'] == "image/gif"){
////if a image is opened the tab is not named after the file
////it is named after the parent element, because images are
////shown in a gallery with all the images listed in the parent
////element
//    $elementData = $db->select('elements', array('id', $fileListData['folder']), array('title'));
//    $elementTitle10 = substr($elementData['title'], 0,10);
//
//
//
//    //define link for openFileFunction
//    $openFileType = "image";
//
//    //define openFile function
//    $link = "openFile('$openFileType', '".$fileListData['id']."', '$elementTitle10');";
//}
//$filesClass = new files();
//$image = $filesClass->getFileIcon($fileListData['type']);
//    ?>
//    <tr class="strippedRow file_<?=$fileListData['id'];?>" oncontextmenu="showMenu('file<?=$fileListData['id'];?>'); return false;" height="40px">
//        <td width="30px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$fileListData['type'];?>" height="22"></td>
//        <td><a href="<?=$subpath;?>out/?file=<?=$fileListData['id'];?>" onclick="<?=$link;?> return false"><?=substr($fileListData[title],0,30);?></a></td>
//        <td width="80" align="right">
//
//                <?php
//                $item = new item('file', $fileListData['id']);
//                echo $item->showScore();
//                ?>
//        </td>
//        <td width="50"><? if($fileListData['download']){ ?>
//                    <a href="./out/download/?fileId=<?=$fileListData['id'];?>" target="submitter" class="btn btn-mini" title="download file"><i class="icon-download"></i></a>
//                <? } 
//                if(!$git){
//                    $contextMenu = new contextMenu('file', $fileListData['id']);
//                    echo $contextMenu->showItemSettings();
//                }?></td>
//    </tr>
//    <?php
//    if(!$git){
//        $contextMenu = new contextMenu("file", $fileListData['id'], $title10, $openFileType);
//        $contextMenu->showRightClick();
//    }
//
//}}
//
//--------------------------------------------------------------------------------------------------------------------
//
//$linkListSQL = mysql_query("SELECT * FROM links WHERE $query");
//while($linkListData = mysql_fetch_array($linkListSQL)) {
//$title10 = substr($linkListData['title'], 0, 10);
//
//$link = "$link&id=".$linkListData['id'];
//if($linkListData['type'] == "youTube"){
//    $link = "openFile('youTube', '".$linkListData['id']."', '$title10', '');";
//}
//
//if($linkListData['type'] == "audio/mp3"){
//    $rightLink = "startPlayer('file', '".$fileListData['id']."')";
//}
//
//if($linkListData['type'] == "RSS"){
//    $link = "openFile('RSS', '".$linkListData['id']."', '$title10');";
//}
//$fileClass = new files();
//$image = $fileClass->getFileIcon($linkListData['type']);
//
//
//    $i++;
//?>
//<tr class="strippedRow link_<?=$linkListData['id'];?>" oncontextmenu="showMenu('link<?=$linkListData['id'];?>'); return false;" height="40px">
//    <td width="65px">&nbsp;<img src="<?=$subpath;?>gfx/icons/fileIcons/<?=$image;?>" alt="<?=$linkListData['type'];?>" height="22px"></td>
//    <td><a href="#" onclick="<?=$link;?>"><?=substr($linkListData['title'],0,30);?></a></td>
//    <td width="70" align="right">
//                <?php
//                $item = new item('file', $fileListData['id']);
//                echo $item->showScore();
//                ?>
//        </td>
//
//        <td width="50">
//        <?php
//        if(!$git){
//                $contextMenu = new contextMenu('link', $linkListData['id'], $title10, $linkListData['type']);
//                echo $contextMenu->showItemSettings();
//        }
//        ?></td>
//</tr>
//<?php
//    if(!$git){
//        $contextMenu = new contextMenu("link", $linkListData['id'], $title10, $linkListData['type']);
//        echo $contextMenu->showRightClick();
//    }
//}
//
//
//
//$shortCutSql = mysql_query("SELECT * FROM internLinks $shortCutQuery");
//while($shortCutData = mysql_fetch_array($shortCutSql)){
//    $i++;
//    if($shortCutData['type'] == "file"){
//
//        $shortCutItemData = $db->select('files', array('id', $shortCutData['typeId']), array('title', 'privacy', 'type'));
//        $title10 = substr($shortCutItemData['title'], 0,10);
//        $title = $shortCutItemData['title'];
//        if($shortCutItemData['type'] == "UFF"){
//        //standard from know on (19.02.2013)
//
//            //define link for openFileFunction
//            $openFileType = "UFF";
//
//            //define openFile function
//            $link = "openFile('$openFileType', '".$shortCutItemData['typeId']."', '$title10');";
//        }
//        else if($shortCutItemData['type'] == "text/plain" OR $shortCutItemData['type'] == "application/pdf"){
//        //standard from know on (19.02.2013)
//
//            //define link for openFileFunction
//            $openFileType = "document";
//
//            //define openFile function
//            $link = "openFile('$openFileType', '".$shortCutItemData['typeId']."', '$title10');";
//        }
//
//
//
//
//
//
//    }else if($shortCutData['type'] == "link"){
//
//        $shortCutItemData = $db->select('links', array('id', $shortCutData['typeId']), array('title', 'link', 'privacy', 'type'));
//        $title10 = substr($shortCutItemData['title'], 0,10);
//        $title = $shortCutItemData['title'];
//        if($shortCutItemData['type'] == "youTube"){
//            $youtubeClass = new youtube($shortCutItemData['link']);
//            $vId = $youtubeClass->getId();
//            $link = "openFile('youTube', '$vId', '$title10');";
//        }
//
//        if($shortCutItemData['type'] == "RSS"){
//            $link = "openFile('RSS', '".$shortCutData['typeId']."', '$title10');";
//        }
//    }
//
//    $filesClass = new files();
//    $image = $filesClass->getFileIcon($shortCutItemData['type']);
//
//    echo'<tr>';
//        echo'<td>';
//            echo"&nbsp;<img src=\"$subpath"."gfx/icons/fileIcons/$image\" height=\"22\"><i class=\"shortcutMark\"> </i>";
//        echo"</td>";
//        echo'<td colspan="3">';
//            echo'<a href=\"./out/?'.$shortCutData['type'].'='.$shortCutData['typeId']." onclick=\"$link return false\">$title</a>";
//        echo"</td>";
//    echo'</tr>';
//}
//                        if($i == 0){
//
//            echo'<tr class="strippedRow" style="height: 20px;">';
//                echo'<td colspan="3">';
//                    echo'This Element is empty.';
//                echo'</td>';
//            echo'</tr>';
//                        }
//

        
        
        
    };
    
    this.getFileList = function(element_id){
        return api.query('api/elements/getFileList/',{element_id : element_id});
    };
    
    this.getData = function(element_id){
        return api.query('api/elements/select/',{element_id : element_id});
    };
    
    this.getAuthorData = function(user_id){
        return api.query('api/elements/getAuthorData/',{user_id : user_id});
    };
    
    this.getFileNumbers = function(element_id){
        return api.query('api/elements/getFileNumbers/',{element_id : element_id});
    };
    
    this.create = function(folder, title,  type, privacy, callback){
        var result="";
	$.ajax({
            url:"api/elements/create/",
            async: false,  
            type: "POST",
            data: $.param({folder : folder, title: title, type: type})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    
    this.showCreateElementForm = function(parent_folder){
        var formModal = new gui.modal();
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        fieldArray[0] = field0;
        
        var captions = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        var type_ids = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        
        var field1 = [];
        field1['caption'] = 'Type';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Create Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The element has been added');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+parent_folder));
            };
            elements.create(parent_folder, $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', '', true);
        formModal.init('Create Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.update = function(element_id, folder, title, type, privacy, callback){
        var result="";
	$.ajax({
            url:"api/elements/update/",
            async: false,  
            type: "POST",
            data: $.param({element_id:element_id,folder : folder, title: title, type: type})+'&'+privacy,
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    }
    
    this.showUpdateElementForm = function(element){
        var formModal = new gui.modal();
        var elementData = elements.getData(element);
        
        var fieldArray = [];
        var options = [];
        options['headline'] = '';
        options['buttonTitle'] = 'Save';
        options['noButtons'] = true;
        
        var field0 = [];
        field0['caption'] = 'Title';
        field0['inputName'] = 'title';
        field0['type'] = 'text';
        field0['value'] = elementData['title'];
        fieldArray[0] = field0;
        
        var captions = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        var type_ids = ['document', 'link', 'audio', 'video', 'image', 'execute', 'other'];
        
        var field1 = [];
        field1['caption'] = 'Type';
        field1['inputName'] = 'type';
        field1['values'] = type_ids;
        field1['captions'] = captions;
        field1['type'] = 'dropdown';
        field1['preselected'] = elementData['type'];
        fieldArray[1] = field1;
        
        var field2 = [];
        field2['caption'] = 'Privacy';
        field2['inputName'] = 'privacy';
        field2['type'] = 'html';
        field2['value'] = "<div id=\'privacyField\'></div>";
        fieldArray[2] = field2;
        
        
        
        var modalOptions = {};
        modalOptions['buttonTitle'] = 'Update Element';
        
        modalOptions['action'] = function(){
            var callback = function(){
                jsAlert('', 'The element has been updated');
                $('.blueModal').remove();
                filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+elementData.folder));
            };
            elements.update(element, elementData['folder'], $('#createElementFormContainer #title').val(), $('#createElementFormContainer #type').val(),  $('#createElementFormContainer #privacyField :input').serialize(),callback);
        };
        privacy.load('#privacyField', elementData['privacy'], true);
        formModal.init('Update Element', '<div id="createElementFormContainer"></div>', modalOptions);
        gui.createForm('#createElementFormContainer',fieldArray, options);
    };
    
    this.delete = function(elementId, callback){
        var result="";
	$.ajax({
            url:"api/elements/delete/",
            async: false,  
            type: "POST",
            data: {element_id : elementId},
            success:function(data) {
               result = data;
               if(typeof callback === 'function'){
                   callback(); //execute callback if var callback is function
               }
            }
	});
	return result;
    };
    this.verifyRemoval = function(elementId){
        var confirmParameters = {};
        var elementData = elements.getData(elementId);
        confirmParameters['title'] = 'Delete Element';
        confirmParameters['text'] = 'Are you sure to delete this element?';
        confirmParameters['submitButtonTitle'] = 'Delete';
        confirmParameters['submitFunction'] = function(){
            elements.delete(elementId);
            gui.alert('The element has been deleted');
            filesystem.tabs.updateTabContent(1 , gui.loadPage('modules/filesystem/fileBrowser.php?folder='+elementData.folder));
            
        };
        confirmParameters['cancelButtonTitle'] = 'Cancel';
        confirmParameters['cancelFunction'] = function(){
            //alert('cancel');
        };
        
        gui.confirm(confirmParameters);
        
    };
    
    this.elementIdToElementTitle = function(elementId){
            return elements.getData(elementId)['title'];
    };
              	
};
