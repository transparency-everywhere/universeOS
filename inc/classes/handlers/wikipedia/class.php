<?php
/**
 * Description of newPHPClass
 *
 * @author niczem
 */
class youtube_handler {
    private $dev_key;
    function __construct(){
        $this->dev_key = 'AIzaSyDLpuc4T9ZJhniR_-9t3VclQqhTZ15cFIs';
    }
    public function query($query, $offset, $max_results=50){
        
        $counter = $max_results;
        
        if($max_results > 50){
            $url_max_results = 50;
            $extension_times = $max_results/50; //how often does the extend function needs to be runned
        }
        
        // create curl resource 
        $ch = curl_init(); 

        // set url
        curl_setopt($ch, CURLOPT_URL, "/w/api.php?action=query&list=search&format=json&srsearch=".$query."&srprop=timestamp&srlimit=$url_max_results"+"https://www.googleapis.com/youtube/v3/search?part=id%2Csnippet&q=".$query."&maxResults=".$url_max_results."&key=".$this->dev_key); 

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
            if($result['id']){
                $video_ids[] = 'http://ttps://www.youtube.com/watch?v='.$result['id']['videoId'];
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
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_id.'&key='.$this->dev_key); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch);
        
        $results = json_decode($output, true);
        // close curl resource to free up system resources 
        curl_close($ch);
        
        return $results['items'][0]['snippet']['title'];
    }
    
    public function getDescription($link){
        $video_id = $this->getId($link);
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_id.'&key='.$this->dev_key); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch);
        
        $results = json_decode($output, true);
        // close curl resource to free up system resources 
        curl_close($ch);
        
        return $results['items'][0]['snippet']['description'];
        
    }
    public function getThumbnail($link){
        $video_id = $this->getId($link);
        $ch = curl_init(); 

        // set url 
        curl_setopt($ch, CURLOPT_URL, 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id='.$video_id.'&key='.$this->dev_key); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch);
        
        $results = json_decode($output, true);
        // close curl resource to free up system resources 
        curl_close($ch);
        
        return $results['items'][0]['snippet']['thumbnails']['default'];
    }
    function getId($text) {
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
}

