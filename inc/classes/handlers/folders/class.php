<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class folder_handler {
    public function query($query, $offset, $max_results=50){
        $k = (int)$offset.','.(int)$max_results;
        $query = escape::sql($query);
        $results = array();
        //folders
        $db = new db();
        $folderSuggestSQL = $db->shiftResult($db->query("SELECT id, name, privacy, creator FROM folders WHERE name LIKE '%$query%' LIMIT $k"),'id');
        foreach ($folderSuggestSQL AS $suggestData) {
            if(authorize($suggestData['privacy'], 'show', $suggestData['creator']))
                $results[] = $suggestData['id'];
        }
        return $results;
    }
}

