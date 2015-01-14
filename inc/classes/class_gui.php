<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class gui {
    //put your code here
    public function showDock(){
        echo " <div id=\"dockplayer\" style=\"display: none\">\n";
        echo "        </div>\n";
        echo "        </div>\n";
        echo "        <div id=\"dock\">\n";
        echo "            <table>\n";
        echo "                <tr>\n";
        echo "                    <td>\n<img src=\"img/arrow_up_bright.png\" alt=\"toggle dashboard\" onclick=\"dashBoard.toggle();\" style=\"margin-right:10px;\">";
        echo "                          <a id=\"startButton\" title=\"toggle Dashboard\" href=\"#dashBoard\">"
           . "                              <img src=\"img/eye_bright.png\"><span class=\"iconAlert\" id=\"appAlerts\"></span><img src=\"img/user_bright.png\"><span class=\"iconAlert\" id=\"openFriendRequests\"></span><img src=\"img/mail_bright.png\"><span class=\"iconAlert\" id=\"newMessages\" onclick=\"chat.applicationVar.show(); return false\"></span>"
           . "                          </a><td>\n";
        echo "                    <!-- <td><div id=\"modulePlayer\" class=\"module\">&nbsp;&nbsp;Player</div>   </td> -->\n";
        echo "                    <td><a href=\"doit.php?action=logout\" target=\"submitter\" style=\"tex-decoration: none; color: #FFF; min-width:10px; margin-left:10px;\" title=\"logout\">&nbsp;<i class=\"icon icon-off\"></i>&nbsp;</a></td>\n";
        echo "                    <td align=\"right\" id=\"clockDiv\" style=\"color: #FFFFFF; float: right\"></td>\n";
        echo "                    <td align=\"right\"><input type=\"text\" name=\"searchField\" id=\"searchField\" placeholder=\"search\"></td>\n";
        echo "                </tr>\n";
        echo "            </table>\n";
        echo "        </div>\n";
    }

    function showLanguageDropdown($value=NULL){
                            //References :
                        //1. http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
                        //2. http://blog.xoundboy.com/?p=235
                            $languages = array(
                    'en' => 'English' , 
                    'aa' => 'Afar' , 
                    'ab' => 'Abkhazian' , 
                    'af' => 'Afrikaans' , 
                    'am' => 'Amharic' , 
                    'ar' => 'Arabic' , 
                    'as' => 'Assamese' , 
                    'ay' => 'Aymara' , 
                    'az' => 'Azerbaijani' , 
                    'ba' => 'Bashkir' , 
                    'be' => 'Byelorussian' , 
                    'bg' => 'Bulgarian' , 
                    'bh' => 'Bihari' , 
                    'bi' => 'Bislama' , 
                    'bn' => 'Bengali/Bangla' , 
                    'bo' => 'Tibetan' , 
                    'br' => 'Breton' , 
                    'ca' => 'Catalan' , 
                    'co' => 'Corsican' , 
                    'cs' => 'Czech' , 
                    'cy' => 'Welsh' , 
                    'da' => 'Danish' , 
                    'de' => 'German' , 
                    'dz' => 'Bhutani' , 
                    'el' => 'Greek' , 
                    'eo' => 'Esperanto' , 
                    'es' => 'Spanish' , 
                    'et' => 'Estonian' , 
                    'eu' => 'Basque' , 
                    'fa' => 'Persian' , 
                    'fi' => 'Finnish' , 
                    'fj' => 'Fiji' , 
                    'fo' => 'Faeroese' , 
                    'fr' => 'French' , 
                    'fy' => 'Frisian' , 
                    'ga' => 'Irish' , 
                    'gd' => 'Scots/Gaelic' , 
                    'gl' => 'Galician' , 
                    'gn' => 'Guarani' , 
                    'gu' => 'Gujarati' , 
                    'ha' => 'Hausa' , 
                    'hi' => 'Hindi' , 
                    'hr' => 'Croatian' , 
                    'hu' => 'Hungarian' , 
                    'hy' => 'Armenian' , 
                    'ia' => 'Interlingua' , 
                    'ie' => 'Interlingue' , 
                    'ik' => 'Inupiak' , 
                    'in' => 'Indonesian' , 
                    'is' => 'Icelandic' , 
                    'it' => 'Italian' , 
                    'iw' => 'Hebrew' , 
                    'ja' => 'Japanese' , 
                    'ji' => 'Yiddish' , 
                    'jw' => 'Javanese' , 
                    'ka' => 'Georgian' , 
                    'kk' => 'Kazakh' , 
                    'kl' => 'Greenlandic' , 
                    'km' => 'Cambodian' , 
                    'kn' => 'Kannada' , 
                    'ko' => 'Korean' , 
                    'ks' => 'Kashmiri' , 
                    'ku' => 'Kurdish' , 
                    'ky' => 'Kirghiz' , 
                    'la' => 'Latin' , 
                    'ln' => 'Lingala' , 
                    'lo' => 'Laothian' , 
                    'lt' => 'Lithuanian' , 
                    'lv' => 'Latvian/Lettish' , 
                    'mg' => 'Malagasy' , 
                    'mi' => 'Maori' , 
                    'mk' => 'Macedonian' , 
                    'ml' => 'Malayalam' , 
                    'mn' => 'Mongolian' , 
                    'mo' => 'Moldavian' , 
                    'mr' => 'Marathi' , 
                    'ms' => 'Malay' , 
                    'mt' => 'Maltese' , 
                    'my' => 'Burmese' , 
                    'na' => 'Nauru' , 
                    'ne' => 'Nepali' , 
                    'nl' => 'Dutch' , 
                    'no' => 'Norwegian' , 
                    'oc' => 'Occitan' , 
                    'om' => '(Afan)/Oromoor/Oriya' , 
                    'pa' => 'Punjabi' , 
                    'pl' => 'Polish' , 
                    'ps' => 'Pashto/Pushto' , 
                    'pt' => 'Portuguese' , 
                    'qu' => 'Quechua' , 
                    'rm' => 'Rhaeto-Romance' , 
                    'rn' => 'Kirundi' , 
                    'ro' => 'Romanian' , 
                    'ru' => 'Russian' , 
                    'rw' => 'Kinyarwanda' , 
                    'sa' => 'Sanskrit' , 
                    'sd' => 'Sindhi' , 
                    'sg' => 'Sangro' , 
                    'sh' => 'Serbo-Croatian' , 
                    'si' => 'Singhalese' , 
                    'sk' => 'Slovak' , 
                    'sl' => 'Slovenian' , 
                    'sm' => 'Samoan' , 
                    'sn' => 'Shona' , 
                    'so' => 'Somali' , 
                    'sq' => 'Albanian' , 
                    'sr' => 'Serbian' , 
                    'ss' => 'Siswati' , 
                    'st' => 'Sesotho' , 
                    'su' => 'Sundanese' , 
                    'sv' => 'Swedish' , 
                    'sw' => 'Swahili' , 
                    'ta' => 'Tamil' , 
                    'te' => 'Tegulu' , 
                    'tg' => 'Tajik' , 
                    'th' => 'Thai' , 
                    'ti' => 'Tigrinya' , 
                    'tk' => 'Turkmen' , 
                    'tl' => 'Tagalog' , 
                    'tn' => 'Setswana' , 
                    'to' => 'Tonga' , 
                    'tr' => 'Turkish' , 
                    'ts' => 'Tsonga' , 
                    'tt' => 'Tatar' , 
                    'tw' => 'Twi' , 
                    'uk' => 'Ukrainian' , 
                    'ur' => 'Urdu' , 
                    'uz' => 'Uzbek' , 
                    'vi' => 'Vietnamese' , 
                    'vo' => 'Volapuk' , 
                    'wo' => 'Wolof' , 
                    'xh' => 'Xhosa' , 
                    'yo' => 'Yoruba' , 
                    'zh' => 'Chinese' , 
                    'zu' => 'Zulu' , 
                    );



                            $return .= "<select name=\"language\">";


                            foreach($languages AS $language){
                                    if(isset($value)){
                                            if($language == $value){
                                                    $selected = 'selected="selected"';
                                            }else{
                                                    $selected = '';
                                            }
                                    }
                                    $return .= "<option $selected>$language</option>";

                            }

                            $return .= "</select>";

                            return $return;
                    }



function universeTime($unixtime){
     $time = time();
     $difference = ($time - $unixtime);
     if($difference < 60){
         $unTime = "just";
     } else if($difference > 60 && $difference < 600){
         $unTime = "some minutes ago";
     } else if($difference > 600 && $difference < 3600){
         $unTime = round($difference / 60);
         $unTime = "$unTime minutes ago";
     } else if($difference > 3600 && $difference < 3600*24){
         $unTime = "one day ago";
     } else if($difference > 3600*24 && $difference < 3600*24*31){
         $udTime = round($difference / 86400);
         $unTime = "$udTime days ago";
     } else if($difference > 3600*24*31){
         $unTime = "one month ago";
     }
     return $unTime;
     
  }
}

                

function jsAlert($text){
        ?>
        <script>
        //check if function is calles from window or from iframe
        //if it is called from iframe parent. needs to be used
        if (typeof(window.jsAlert) === "function") {
		   jsAlert('', '<?=addslashes($text);?>');
		}else{
		   parent.jsAlert('', '<?=addslashes($text);?>');
		}
        </script> 
            <?php
    }