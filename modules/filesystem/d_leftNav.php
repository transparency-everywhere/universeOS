<?php

//This file is published by transparency-everywhere with the best deeds.
//Check transparency-everywhere.com for further information.
//Licensed under the CC License, Version 4.0 (the "License");
//you may not use this file except in compliance with the License.
//You may obtain a copy of the License at
//
//https://creativecommons.org/licenses/by/4.0/legalcode
//
//Unless required by applicable law or agreed to in writing, software
//distributed under the License is distributed on an "AS IS" BASIS,
// WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//See the License for the specific language governing permissions and
//limitations under the License.
//
//@author nicZem for Tranpanrency-everywhere.com
?>

          <div class="leftNav">
              <ul>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=pupularity&reload=1'));return false"><img src="gfx/icons/filesystem/side_suggestions.png"> Suggestions</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?reload=1'));return false"><img src="gfx/icons/filesystem/side_allFiles.png"> All Files</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=document&reload=1'));return false"><img src="gfx/icons/filesystem/side_documents.png"> Documents</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=audio&reload=1'));return false"><img src="gfx/icons/filesystem/side_audio.png"> Audio Files</a></li>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=video&reload=1'));return false"><img src="gfx/icons/filesystem/side_video.png"> Video Files</a></li>
					<?
					if(proofLogin()){
					?>
                  <li><a href="#" onclick="filesystem.tabs.updateTabContent(1 ,gui.loadPage('modules/filesystem/fileBrowser.php?special=fav&reload=1'));return false"><img src="gfx/icons/filesystem/side_fav.png"> Fav</a></li>
 				  <? } ?>
                  <!-- <li><i class="icon-warning-sign"></i> deleted</li> -->
              </ul>
          </div>
