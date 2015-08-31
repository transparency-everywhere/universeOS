<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class element_handler {
    public function query($query, $offset, $max_results=50){
        $k = (int)$offset.','.(int)$max_results;
        $query = mysql_real_escape_string($query);
        $results = array();
        $elementSuggestSQL = mysql_query("SELECT id, title, privacy, author FROM elements WHERE title LIKE '%$query%' LIMIT $k");
        while ($suggestData = mysql_fetch_array($elementSuggestSQL)) {

            if(authorize($suggestData['privacy'], 'show', $suggestData['author']))       
                $results[] = $suggestData['id'];
        }
        return $results;
    }
}

