<?php
if(isset($_POST['youtube-link'])) {
    $youtube_link = $_POST['youtube-link'];
    if (preg_match('/^(http(s)?:\/\/)?((w){3}.)?youtu(be|.be)?(\.com)?\/.+/i', $youtube_link)) {
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
        $duration = $data['items'][0]['contentDetails']['duration'];
        $channel_name = $data['items'][0]['snippet']['channelTitle'];
        $channel_id = $data['items'][0]['snippet']['channelId'];
        $published_at = $data['items'][0]['snippet']['publishedAt'];
        $date = new DateTime($published_at);
        $published_at_formatted = $date->format('F j, Y');
        $iframe_src = "https://www.youtube.com/embed/{$video_id}";
        include 'info.html';
        echo "<script>document.getElementById('video-player').src = '{$iframe_src}';</script>";
    }
    else{
        // Handle YouTube search query
        $search_query = urlencode($youtube_link);
        echo "<script>window.open('https://www.youtube.com/results?search_query={$search_query}', '_blank');</script>";
        // header("Location: https://www.youtube.com/results?search_query={$search_query}");
        include 'index.html';
    }
?>
<script>
    console.log(<?php echo json_encode($data['items']); ?>);
    console.log("Hello World");
</script>

<?php
}
?>

