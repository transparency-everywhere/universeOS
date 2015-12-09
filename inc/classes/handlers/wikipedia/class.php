<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */


include(dirname(__FILE__) ."/../../phpfastcache.php");

class wikipedia_handler {
    public function query($query, $offset, $max_results=50){
        $counter = $max_results;
        $url_max_results = $max_results;
        
        if($max_results > 50){
            $url_max_results = 50;
            $extension_times = $max_results/50; //how often does the extend function needs to be runned
        }
        
        $url_offset = $offset;
        
        // create curl resource 
        $ch = curl_init(); 

        // set url
        curl_setopt($ch, CURLOPT_URL, "https://en.wikipedia.org/w/api.php?action=query&list=search&format=json&srsearch=".$query."&srprop=timestamp&srlimit=$url_max_results&sroffset=$url_offset&continue="); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch);
        
        $results = json_decode($output, true);
        
        //get next page token from result in case that extendResultFunction is called
        
        $wiki_titles = array();
        
        
        foreach($results['query']['search'] AS $result){
            if($result['title']){
                $wiki_titles[] = 'http://en.wikipedia.org/wiki/'.$result['title'];
                $max_results--;
            }
        }

        // close curl resource to free up system resources 
        curl_close($ch);    
        $i = 1;
        while($i < $extension_times){
            $wiki_titles = array_merge($wiki_titles, $this->query($query, ($i)*50, 50));
            $i++;
        }
        return $wiki_titles;
    }
    public function getTitle($link){
        return urldecode(str_replace(array('https://en.wikipedia.org/wiki/','http://en.wikipedia.org/wiki/', 'Germany/'), '', $link));
    }
    
    public function getDescription($link){
        $title = $this->getTitle($link);
        
        
        $cache = phpFastCache();

        // try to get from Cache first.
        $results = $cache->get("wikipedia_snippet_".$title);

        if($results == null) {
            
            
            $ch = curl_init(); 

            // set url 
            curl_setopt($ch, CURLOPT_URL, 'http://en.wikipedia.org/w/api.php?action=parse&section=0&prop=text&page='.$title.'&format=json'); 

            //return the transfer as a string 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

            // $output contains the output string 
            $output = curl_exec($ch);
            
            // Write to Cache Save API Calls next time
            $cache->set("wikipedia_snippet_".$title, $output, 3600*24);
        }
        
        
        
        $results = json_decode($output, true);
        
        //var_dump($results['parse']['text']['*']);
        // close curl resource to free up system resources 
        curl_close($ch);
        
        $dom = new DOMDocument();
        $dom->validateOnParse = true; //<!-- this first
        $dom->loadHTML($results['parse']['text']['*']);        //'cause 'load' == 'parse

        $dom->preserveWhiteSpace = false;

        $belement = $dom->getElementsByTagName("p");
        return $belement->item(0)->nodeValue;
    }
    public function getThumbnail($link){
        //really slow!!!
//        $title = $this->getTitle($link);
//        $ch = curl_init(); 
//
//        // set url 
//        curl_setopt($ch, CURLOPT_URL, 'http://en.wikipedia.org/w/api.php?action=parse&section=0&prop=text&page='.$title.'&format=json'); 
//
//        //return the transfer as a string 
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
//
//        // $output contains the output string 
//        $output = curl_exec($ch);
//        
//        $results = json_decode($output, true);
//        
//        //var_dump($results['parse']['text']['*']);
//        // close curl resource to free up system resources 
//        curl_close($ch);
//        
//        error_reporting(E_ALL);
//        $dom = new DOMDocument();
//        $dom->validateOnParse = true; //<!-- this first
//        $dom->loadHTML($results['parse']['text']['*']);        //'cause 'load' == 'parse
//
//        $dom->preserveWhiteSpace = false;
//
//        $belement = $dom->getElementsByTagName("img");
//        foreach($belement AS $element){
//            return $element->getAttribute('src');
//        }
        //return $belement[0]->getAttribute('src');
        return '';
    }
}

