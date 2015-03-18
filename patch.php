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
//    Delete Users where cypher is md5 or sha512
//
//    user
//        +profile_info
//        move old userpictures from/userPictures
//    groups
//        public: public->1
//                not publ->0
//
//
//    add table playlists/Delete Table 'playlist'
//
//    Add Table 'user_privacy_rights' and create entry for each user