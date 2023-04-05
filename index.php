<?php
if(isset($_POST['youtube-link'])) {
    $youtube_link = $_POST['youtube-link'];
    $format = strtoupper($_POST['format']);
    $quality = $_POST['quality'];
    
    // Extract video ID from the link
    parse_str(parse_url($youtube_link, PHP_URL_QUERY), $params);
    $video_id = $params['v'];
    
    // Get video information using YouTube Data API
    $api_key = 'AIzaSyAlwhcH-e9vQ7PKa-kFS_JAYaMd4iY6eUU';
    $url = "https://www.googleapis.com/youtube/v3/videos?id={$video_id}&key={$api_key}&part=snippet";
    $json = file_get_contents($url);
    $data = json_decode($json, true);
    $title = $data['items'][0]['snippet']['title'];
    
    // Download audio stream using youtube-dl
    $download_link = NULL;
    if($format == "MP3"){
        if($quality == "low")
            $parsed_quality = 10;
        else if($quality == "medium")
            $parsed_quality = 5;
        else 
            $parsed_quality = 0;
        $cmd = "yt-dlp -x --audio-format mp3 --audio-quality {$parsed_quality} --output '{$title}.$quality.%(ext)s' {$youtube_link}";       
        $mp3_file = "{$title}.$quality.mp3";
        $download_link = $_SERVER['REQUEST_SCHEME']. '/' . $mp3_file;

    }
    else if($format == "M4A"){
        $cmd = "yt-dlp -x --audio-format m4a --audio-quality 0 --add-metadata -o '{$title}.{$quality}.%(ext)s' {$youtube_link}";
        $m4a_file = "{$title}.$quality.m4a";
        $download_link = $_SERVER['REQUEST_SCHEME']. '/' . $m4a_file;
    }
    else{
        if($quality == "low")
            $quality = 720;
        else if($quality == "medium")
            $quality = 1080;
        else
            $quality = 1440;
        $cmd = "yt-dlp -f  \"bestvideo[height<={$quality}][ext=mp4]+bestaudio[ext=m4a]/best[height<={$quality}][ext=mp4]\" --output '{$title}.{$quality}.%(ext)s' {$youtube_link}";
        $mp4_file = "{$title}.$quality.mp4";
        $download_link = $_SERVER['REQUEST_SCHEME']. '/' . $mp4_file;
    }
    
    exec($cmd, $output, $return_var);
    
    // Generate a download link
    $thumbnail_img_link = "https://img.youtube.com/vi/{$video_id}/0.jpg";
    include 'index2.html';
?>
<script>
    console.log(<?php echo json_encode($format); ?>);
</script>
<?php
}
?>

