<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class folder_handler {
    public function query($query, $offset, $max_results=50){
        $k = (int)$offset.','.(int)$max_results;
        
        $results = array();
        //folders
        $folderSuggestSQL = mysql_query("SELECT id, name, privacy, creator FROM folders WHERE name LIKE '%$query%' LIMIT $k");
        while ($suggestData = mysql_fetch_array($folderSuggestSQL)) {
            if(authorize($suggestData['privacy'], 'show', $suggestData['creator']))
                $results[] = $suggestData['id'];
        }
        return $results;
    }
}

