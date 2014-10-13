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
        echo "                    <td>\n";
        echo "                        <a class=\"module\" id=\"startButton\" title=\"toggle Dashboard\" href=\"#dashBoard\">".showUserPicture(getUser(), 24)."<i class=\"icon-retweet icon-white\" style=\"margin-left:15px;\"><span class=\"iconAlert\" id=\"appAlerts\"></span></i><i class=\"icon-user icon-white\"><span class=\"iconAlert\" id=\"openFriendRequests\"></span></i><i class=\"icon-envelope icon-white\"><span class=\"iconAlert\" id=\"newMessages\" onclick=\"showApplication(\'chat\'); return false\"></span></i></a><td>\n";
        echo "                    <!-- <td><div id=\"modulePlayer\" class=\"module\">&nbsp;&nbsp;Player</div>   </td> -->\n";
        echo "                    <td><a href=\"doit.php?action=logout\" target=\"submitter\" class=\"module\" style=\"tex-decoration: none; color: #797979; min-width:10px;\" title=\"logout\">&nbsp;<i class=\"icon-white icon-off\"></i>&nbsp;</a></td>\n";
        echo "                    <td align=\"right\" id=\"clockDiv\" style=\"color: #FFFFFF; float: right\"></td>\n";
        echo "                    <td align=\"right\"><input type=\"text\" name=\"searchField\" id=\"searchField\" class=\"border-radius\" placeholder=\"search\"></td>\n";
        echo "                </tr>\n";
        echo "            </table>\n";
        echo "        </div>\n";
    }
}

