<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class element_handler {
    public function query($query, $offset, $max_results=50){
        $k = (int)$offset.','.(int)$max_results;
        $query = escape::sql($query);
        $results = array();
        $db = new db();
        $elementSuggestSQL = $db->shiftResult($db->query("SELECT id, title, privacy, author FROM elements WHERE title LIKE '%$query%' LIMIT $k"), 'id');
        foreach ($elementSuggestSQL AS $suggestData) {

            if(authorize($suggestData['privacy'], 'show', $suggestData['author']))       
                $results[] = $suggestData['id'];
        }
        return $results;
    }
}

