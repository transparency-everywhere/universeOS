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
        include('views/desktop/dock.html');
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
  
   
    function generate($options=null){

            if(!proofLogin()){
                $_SESSION['loggedOut'] = true;
            }
            
        $timestamp = time();


        if(getUser()){
            $userClass = new user(getUser());
            $userData = $userClass->getData();
            $login = true;
        }else{
            $login = false;
            $userData['startLink'] = ''; //otherwise notice 'undefined index'
        }
        switch($options['action']){
            case 'kickstarter':

                $options['js_init_obj'] = ['kickstarter' => ['type'=>$options['action_data']['type'], 'itemId'=>(int)$options['action_data']['itemId']]];
            break;
            case 'embed':
                $options['js_init_obj'] = ['embed' => ['type'=>$options['action_data']['type'], 'itemId'=>(int)$options['action_data']['itemId']]];
            break;
        }


        $this->generateHeader($options);
        echo '<body onclick="clearMenu()" onload="'.$userData['startLink'].'">';

                //define bg-image
                if(!empty($userdata['backgroundImg'])){ 
                    ?>
                    <style type="text/css">
                        body{
                            background-image: url(<?=$userdata['backgroundImg'];?>);
                            background-attachment: no-repeat;
                        }
                    </style>
                    <?php }
                    
                    
            echo '<!--';
            var_dump($options);
            echo'-->';
            
        if(!$login) {
            echo '<link rel="stylesheet" type="text/css" href="'.$this->absURL('inc/css/guest.css').'" media="all" />';
            echo '<div id="bodywrap">';
                if(!in_array($options['action'], ['kickstarter','embed','open']))
                    echo gui::parseView('views/guestpage/guest_area.html');

                echo '<div id="alerter"></div><div id="loader"></div><iframe name="submitter" style="display:none;" id="submitter"></iframe>';
                echo '<div id="suggest">';
                echo '</div>';
            echo '</div>';

            include('views/guestpage/login_menu.html');
            include('views/guestpage/dock_menu.html');
            if(!isset($options['show_dock'])||$options['show_dock']==true)
                include('views/guestpage/dock.html');

            echo analytic_script;

            include('actions/openFileFromLink.php');
            include('views/guestpage/search_menu.html');

            echo $this->parseView("views/header/scripts.html");
            echo '<script type="text/javascript" src="'.$this->absURL('inc/js/guest.js').'"></script>';


        }
        else{
            //set userid
            echo $this->parseView("views/header/scripts.html");
            echo '<script>User.userid = '.getUser().';</script>';


            //include dashboard
            include("modules/desktop/dashboard.php");


            echo    '<div id="bodywrap">';
            echo '<div id="alerter"></div>';
            echo        '<ul id="systemAlerts">';
            echo        '</ul>';
            echo        '<div id="loader"></div>';
            echo        '<div id="popper"></div>';
            echo        '<iframe name="submitter" style="display:none;" id="submitter"></iframe>';
            echo        '<div id="suggest"></div>';

            echo        '<div id="notifications">';
                echo        '<ul></ul>';
            echo        '</div>';

        //    echo        '<div id="playListPlayer">';
        //    
        //    echo        '</div>';

            if(!isset($options['show_dock'])||$options['show_dock']==true){
                $gui = new gui();
                $gui->showDock();
            }
            
            echo    '</div>';

            include('views/guestpage/search_menu.html');

            include('actions/openFileFromLink.php');

        }
        if(!isset($options['js_init_obj']))
            $options['js_init_obj'] = '{}';
        else
            $options['js_init_obj'] = json_encode($options['js_init_obj']);
        echo '<script>$(document).ready(function() {universe.init('.$options['js_init_obj'].');});</script>';
        echo '</body>';
            
    }
    //@param path  relative path
    function absURL($path){
        return uniConfig::$uni_url.'/'.$path;
    }
    
    public static function parsingCallback($expression){
        
        switch($expression[1]){
            case 'uni_url':
                return uni_config_url;
                break;
        }
        
        return '';
        
    }
    
    public static function parseView($path){
        
        
        
        return preg_replace_callback(
            '!\{\{(\w+)\}\}!',
            "gui::parsingCallback",
            file_get_contents($path));
        
        
        
    }
    
    function generateHeader($options=null){
        if(!isset($options['title']))
            $options['title'] = 'universeOS';
                
            $output = "<!DOCTYPE html>\n";
            $output .= "<html lang=\"en\" dir=\"ltr\" class=\"client-nojs\">\n";
            $output .= "        <!--meta information-->\n";
            $output .= "        <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge,chrome=1\">\n";
            $output .= "		<meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no\">\n";
            $output .= "		\n";
            $output .= "		<meta property=\"twitter:user_id\" content=\"1656521474\" />\n";
            $output .= "        <meta name=\"twitter:site\" content=\"@universeOS\">\n";
            $output .= "        <meta name=\"twitter:card\" content=\"summary\">\n";
            $output .= "        \n";
            $output .= "        <meta name=\"language\" content=\"en\" />\n";
            $output .= "        <meta name=\"robots\" content=\"index, follow\" />\n";
            $output .= "        <meta name=\"author\" content=\"Transparency Everywhere\" />\n";
            $output .= "        <meta name=\"medium\" content=\"webDesktop\" />\n";
            $output .= "        \n";
            $output .= "        <meta property=\"og:site_name\" content=\"universeOS\" />\n";
            $output .= "        <meta property=\"identifier-URL\" content=\"".$this->absURL('')."\" />\n";
            $output .= "        <meta name=\"description\" content=\"Discover the social webOS. Connect with your friends, read your favourite book or RSS-Feed, watch your favourite movie, listen your favourite song and be creative...\">\n";
            $output .= "        <meta name=\"keywords\" content=\"universe, universeos, universe os, webdesktop, web desktop, social webdesktop , youtube, youtube playlist, documents, rss, free speech, human rights, privacy, community, social\">\n";
            $output .= "        <meta name=\"title\" content=\"universeOS\">\n";
            $output .= "        <meta name=\"Robots\" content=\"index\">\n";
            $output .= "        <meta name=\"author\" content=\"Transparency Everywhere\">\n";
            $output .= "        <meta name=\"classification\" content=\"\">\n";
            $output .= "        <meta name=\"reply-to\" content=\"info@transparency-everywhere.com\">\n";
            $output .= "        <meta name=\"Identifier-URL\" content=\"universeOS.org\">\n";
            $output .= "        <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";
            $output .= "        \n";
            $output .= "        <meta name=\"viewport\" content=\"width=device-width, initial-scale=1.0\">\n";
            $output .= "        \n";
            $output .= "        <!--facebook open graph-->\n";
            $output .= "        <meta property=\"og:image\" content=\"".$this->absURL('gfx/logo.png')."\">\n";
            $output .= "        <meta property=\"og:site_name\" content=\"universeOS\">\n";
            $output .= "        \n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"57x57\" href=\"".$this->absURL('gfx/favicon/apple-icon-57x57.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"60x60\" href=\"".$this->absURL('gfx/favicon/apple-icon-60x60.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"72x72\" href=\"".$this->absURL('gfx/favicon/apple-icon-72x72.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"76x76\" href=\"".$this->absURL('gfx/favicon/apple-icon-76x76.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"114x114\" href=\"".$this->absURL('gfx/favicon/apple-icon-114x114.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"120x120\" href=\"".$this->absURL('gfx/favicon/apple-icon-120x120.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"144x144\" href=\"".$this->absURL('gfx/favicon/apple-icon-144x144.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"152x152\" href=\"".$this->absURL('gfx/favicon/apple-icon-152x152.png')."\">\n";
            $output .= "        <link rel=\"apple-touch-icon\" sizes=\"180x180\" href=\"".$this->absURL('gfx/favicon/apple-icon-180x180.png')."\">\n";
            $output .= "        <link rel=\"icon\" type=\"image/png\" sizes=\"192x192\" href=\"".$this->absURL('gfx/favicon/android-icon-192x192.png')."\">\n";
            $output .= "        <link rel=\"icon\" type=\"image/png\" sizes=\"32x32\" href=\"".$this->absURL('gfx/favicon/favicon-32x32.png')."\">\n";
            $output .= "        <link rel=\"icon\" type=\"image/png\" sizes=\"96x96\" href=\"".$this->absURL('gfx/favicon/favicon-96x96.png')."\">\n";
            $output .= "        <link rel=\"icon\" type=\"image/png\" sizes=\"16x16\" href=\"".$this->absURL('gfx/favicon/favicon-16x16.png')."\">\n";
            $output .= "        <link rel=\"manifest\" href=\"".$this->absURL('gfx/favicon/manifest.json')."\">\n";
            $output .= "        <meta name=\"msapplication-TileColor\" content=\"#ffffff\">\n";
            $output .= "        <meta name=\"msapplication-TileImage\" content=\"".$this->absURL('gfx/favicon/ms-icon-144x144.png')."\">\n";
            $output .= "        <meta name=\"theme-color\" content=\"#ffffff\">\n";
            $output .= "        \n";
            $output .= "        <link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->absURL('inc/css/plugins.css')."\" />\n";
            $output .= "        <link rel=\"stylesheet\" type=\"text/css\" href=\"".$this->absURL('inc/css/style.css')."\" media=\"all\" />\n";
            $output .= "        \n";
            $output .= "        \n";
            $output .= "        <title>".$options['title']."</title>\n";
            $output .= "    </head>\n";
            echo $output;
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