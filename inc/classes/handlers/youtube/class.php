<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */


include(dirname(__FILE__) ."/../../phpfastcache.php");

class youtube_handler {
    private $dev_key;
    function __construct(){
        $this->dev_key = 'AIzaSyDLpuc4T9ZJhniR_-9t3VclQqhTZ15cFIs';
    }
    public function query($query, $offset, $max_results=50){
        
        $counter = $max_results;
        $url_max_results = $max_results;
        $extension_times = 0;
        if($max_results > 50){
            $url_max_results = 50;
            $extension_times = $max_results/50; //how often does the extend function needs to be runned
        }
        
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=id%2Csnippet&q=".$query."&maxResults=".$url_max_results."&key=".$this->dev_key); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch);
        
        $results = json_decode($output, true);
        //get next page token from result in case that extendResultFunction is called
        $next_page_token = $results['nextPageToken'];
        
        $video_ids = array();
        
        foreach($results['items'] AS $result){
            //var_dump($result);
            if($result['id']['videoId']){
                $video_ids[] = 'http://www.youtube.com/watch?v='.$result['id']['videoId'];
                $max_results--;
            }
        }

        // close curl resource to free up system resources 
        curl_close($ch);    
        $i = 0;
        while($i < $extension_times){
            if($next_page_token)
                $video_ids = $this->extendQuery($query, $video_ids, $next_page_token, $counter);
            else
                break;
            $i++;
        }
        return array_splice($video_ids, $offset); //remove offset from array
    }
    public function extendQuery($query, $video_ids, &$nextPageToken, &$counter){
        $max_results = 50; //if rest is less than 50 the rest will be ignoreded because of $counter
        
        // create curl resource 
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, "https://www.googleapis.com/youtube/v3/search?part=id%2Csnippet&q=".$query."&maxResults=".$max_results."&pageToken=".$nextPageToken."&key=".$this->dev_key); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch);
        
        $results = json_decode($output, true);
        if($results['nextPageToken'])
            $nextPageToken = $results['nextPageToken'];
        else{
            $nextPageToken = false;
            return $video_ids;
        }
        echo $nextPageToken.'<br>';
        foreach($results['items'] AS $result){
            //var_dump($result);
            if($result['id'] && $counter > 0){
                $counter--;
                $video_ids[] = 'http://ttps://www.youtube.com/watch?v='.$result['id']['videoId'];
            }
        }

        // close curl resource to free up system resources 
        curl_close($ch);
        return $video_ids;
    }
    public function getTitle($link){
        $video_id = $this->getId($link);
        $results = $this->getSnippet($video_id);
        return $results['items'][0]['snippet']['title'];
    }
    
    public function getDescription($link){
        $video_id = $this->getId($link);
        $results = $this->getSnippet($video_id);
        return $results['items'][0]['snippet']['description'];
        
    }
    public function getThumbnail($link){
        $video_id = $this->getId($link);
        $results = $this->getSnippet($video_id);
        return $results['items'][0]['snippet']['thumbnails']['default']['url'];
    }
    //is used to preload snippets into cache to reduce execution time
    public function preload($links){
        
        
        $id = $this->getId($links);
        $idChecksum = md5($id);
        
        //it would suck to check if any of the snippets is cached already.
        //instead every new request of a certain link combination(the array $links) is saved as a md5 checksum to prevent double requests
        
        $cache = phpFastCache();
        //check if result is cached allready
        $output = $cache->get("youtube_snippet_checksum_".$idChecksum);
        if($output == NULL){
            $this->getSnippet($id);

            $cache->set("youtube_snippet_checksum_".$idChecksum, 3600*24);
        }
        return true;
    }
    public function getSnippet($video_id){
        $multi = false;
        $output = null;
        if (strpos($video_id,',') !== false) {
            $multi = true;
        }
        
        $cache = phpFastCache();
        
        if(!$multi){
            $output = $cache->get("youtube_snippet_".$video_id);
        }
        // try to get from Cache first.
        if(empty($output)) {
            
            $ch = curl_init(); 

            // set url 
            curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_id.'&key='.$this->dev_key); 
            //return the transfer as a string 
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            
            
            $output = curl_exec($ch);
            
            // close curl resource to free up system resources 
            curl_close($ch);
            $json = json_decode($output, true);
            //var_dump($json);
            if($multi){
                //json[0] is an info object with totalResults and resultsPerPage, json[1] are the results in an array
                foreach($json['items'] AS $singleOutput){
                    $saveResult['items'][0] = $singleOutput;
                    $cache->set("youtube_snippet_".$singleOutput['id'], json_encode($saveResult,true), 3600*24);
                }
                $output = array();
                $output['items'] = $json['items'];
                return $output;
            }else{
                $cache->set("youtube_snippet_".$video_id, $output, 3600*24);
            }
        }
        return json_decode($output, true);
    }
    function getSingleId($text){
        $text = preg_replace('~
            # Match non-linked youtube URL in the wild. (Rev:20111012)
            https?://         # Required scheme. Either http or https.
            (?:[0-9A-Z-]+\.)? # Optional subdomain.
            (?:               # Group host alternatives.
              youtu\.be/      # Either youtu.be,
            | youtube\.com    # or youtube.com followed by
              \S*             # Allow anything up to VIDEO_ID,
              [^\w\-\s]       # but char before ID is non-ID char.
            )                 # End host alternatives.
            ([\w\-]{11})      # $1: VIDEO_ID is exactly 11 chars.
            (?=[^\w\-]|$)     # Assert next char is non-ID or EOS.
            (?!               # Assert URL is not pre-linked.
              [?=&+%\w]*      # Allow URL (query) remainder.
              (?:             # Group pre-linked alternatives.
                [\'"][^<>]*>  # Either inside a start tag,
              | </a>          # or inside <a> element text contents.
              )               # End recognized pre-linked alts.
            )                 # End negative lookahead assertion.
            [?=&+%\w-]*        # Consume any URL (query) remainder.
            ~ix', 
            '$1',
            $text);
        
        return $text;
    }
    function getId($url) {
        $result = array();
        if(is_array($url)&&count($url)>0){
            //var_dump($url);
            foreach($url AS $singleUrl){
                $result[] = $this->getSingleId($singleUrl);
            }
            return implode(',', $result);
        }
        return $this->getSingleId($url);
    }
}

