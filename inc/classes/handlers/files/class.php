<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class file_handler {
    public function query($query, $offset, $max_results=50){
        $k = (int)$offset.','.(int)$max_results;
        
        $results = array();
        $fileSuggestSQL = mysql_query("SELECT id, title, privacy, type, owner FROM files WHERE title LIKE '%$q%' LIMIT $k");
        while ($suggestData = mysql_fetch_array($fileSuggestSQL)) {

            if(authorize($suggestData['privacy'], 'show', $suggestData['owner']))       
                $results[] = $suggestData['id'];
        } 
        return $results;
    }
}

