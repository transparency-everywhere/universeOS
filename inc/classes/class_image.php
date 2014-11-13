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
class image{
    
  // Thumbnails: [url]www.codeschnipsel.net[/url]
function mkthumb($img_src,$img_width, $img_height, $folder_scr,  $des_src){
    // Größe und Typ ermitteln
    list($src_width, $src_height, $src_typ) = getimagesize($folder_scr."/".$img_src);

    // neue Größe bestimmen
    if($src_width >= $src_height)
    {
      $new_image_width = $img_width;
      $new_image_height = $src_height * $img_width / $src_width;
    }
    if($src_width < $src_height)
    {
      $new_image_height = $img_width;
      $new_image_width = $src_width * $img_height / $src_height;
    }

    if($src_typ == 1)     // GIF
    {
      $image = imagecreatefromgif($folder_scr."/".$img_src);
      $new_image = imagecreate($new_image_width, $new_image_height);
      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
      imagegif($new_image, $des_src."/".$img_src, 100);
      imagedestroy($image);
      imagedestroy($new_image);
      return true;
    }
    elseif($src_typ == 2) // JPG
    {
      $image = imagecreatefromjpeg($folder_scr."/".$img_src);
      $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
      imagejpeg($new_image, $des_src."/".$img_src, 100);
      imagedestroy($image);
      imagedestroy($new_image);
      return true;
    }
    elseif($src_typ == 3) // PNG
    {
      $image = imagecreatefrompng($folder_scr."/".$img_src);
      $new_image = imagecreatetruecolor($new_image_width, $new_image_height);
      imagecopyresampled($new_image, $image, 0, 0, 0, 0, $new_image_width,$new_image_height, $src_width, $src_height);
      imagepng($new_image, $des_src."/".$img_src);
      imagedestroy($image);
      imagedestroy($new_image);
      return true;
    }
    else
    {
      return false;
    }
  }
}