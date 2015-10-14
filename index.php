<!DOCTYPE html>
<html>
<!-- Call Twitter's API with PHP -->
<?php
if (($_GET['q'] && $_GET['q2']) && !($_GET['q3'] && $_GET['q4'])) {
  require_once('TwitterAPIExchange.php');

  // Set access tokens.
  $settings = array(
    'oauth_access_token' => "XXX",
    'oauth_access_token_secret' => "XXX",
    'consumer_key' => "XXX",
    'consumer_secret' => "XXX"
  );

  $url = "https://api.twitter.com/1.1/search/tweets.json";
  $requestMethod = "GET";
  $getfield = "q=".$_GET['q']."+youtu.be&count=1";
  $twitter = new TwitterAPIExchange($settings);

  $string = json_decode($twitter->setGetfield($getfield)
  ->buildOauth($url, $requestMethod)
  ->performRequest(),$assoc = TRUE);
  if($string["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string[errors][0]["message"]."</em></p>";exit();}

  // Grab first tweet text.
  foreach($string['statuses'] as $tweets) {
  $text1 = ($tweets['text']);
  }

  $getfield = "q=".$_GET['q2']."+youtu.be&count=1";
  $string2 = json_decode($twitter->setGetfield($getfield)
  ->buildOauth($url, $requestMethod)
  ->performRequest(),$assoc = TRUE);
  if($string2["errors"][0]["message"] != "") {echo "<h3>Sorry, there was a problem.</h3><p>Twitter returned the following error message:</p><p><em>".$string2[errors][0]["message"]."</em></p>";exit();}

  // Grab second tweet text.
  foreach($string2['statuses'] as $tweets) {
  $text2 = ($tweets['text']);
  }

  // Grab first expanded URL.
  $url1 = "";
  foreach($string['statuses'] as $tweets) {
  $url1 = ($tweets['entities']['urls']['0']['expanded_url']);
  }

  // Grab second expanded URL.
  $url2 = "";
  foreach($string2['statuses'] as $tweets) {
  $url2 = ($tweets['entities']['urls']['0']['expanded_url']);
  }

  // Send user input to URL.
  $name1 = $_GET['q'];
  $name2 = $_GET['q2'];
}
// If inputs already set, use those from URL.
if ($_GET['q3'] && $_GET['q4']) {
  $url1 = $_GET['q3'];
  $url2 = $_GET['q4'];
  $name1 = $_GET['q'];
  $name2 = $_GET['q2'];
}
?>
<head>
 <!-- Meta Image Properties -->
  <meta name="twitter:image"       content="http://img.youtube.com/vi/<?php echo $url1 ?>/hqdefault.jpg" />
  <meta property="og:image"        content="http://img.youtube.com/vi/<?php echo $url1 ?>/hqdefault.jpg" />
  <meta property="og:title"        content="" />
  <meta property="og:description"  content="" />
  
  <!-- Display user input in title -->
  <title><?php echo $name1; ?> + <?php echo $name2; ?></title>
  
  <!-- Stylesheets -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/material-design-iconic-font/2.1.2/css/material-design-iconic-font.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
  <body>
    <div class="container">
      <!-- Get user input -->
      <div class="input-container">
        <form method="get" onsubmit="checkifempty()">
            <input id="input1" type="text" name="q" placeholder="<?php echo (isset($name1) ? $name1 : 'Kim Kardashian'); ?>"  autocomplete="off" autofocus>
            <input id="input2" type="text" name="q2" placeholder="<?php echo (isset($name2) ? $name2 : 'Vladimir Putin'); ?>"  autocomplete="off">

            <button value="submit" class="search"><i class="zmdi zmdi-search"></i></button>
        </form>
            <button id="mute"   onclick="muteOnClick()" class="mute"><i class="zmdi zmdi-volume-up"></i></button>
            <button id="unmute" onclick="unmuteOnClick()" class="unmute"><i class="zmdi zmdi-volume-off"></i></button>
      </div>

        <!-- Display player iframe -->
        <div class="video-container">
          <iframe class="video-iframe"
                  id ="player" 
                  width="100%" height="100%"
                  src=""
                  frameborder="0"
                  allowfullscreen>
          </iframe>
        </div>
      <!-- Display audio iframe -->
        <iframe class="audio-iframe"
                id = "audio"
                width="560" height="315" 
                src=""
                frameborder="0" 
                allowfullscreen>
        </iframe>
    </div>

    <script>
      // This loads the IFrame Player API code asynchronously.
      var tag = document.createElement('script');
      tag.src = "https://www.youtube.com/iframe_api";
      var firstScriptTag = document.getElementsByTagName('script')[0];
      firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

      // Parse Youtube URL
      var url1 = "<?php echo $url1; ?>";
      var url2 = "<?php echo $url2; ?>";

      result1 = YouTubeGetID(url1);
      console.log(result1);

      result2 = YouTubeGetID(url2);
      console.log(result2);
      if(!(url1.length == 0)) {
        document.getElementById('player').src = 'https://www.youtube.com/embed/'+result1+'?showinfo=0&controls=0&autoplay=1&iv_load_policy=3&enablejsapi=1';
        document.getElementById('audio').src = 'https://www.youtube.com/embed/'+result2+'?showinfo=0&controls=0&autoplay=1&iv_load_policy=3&enablejsapi=1';
      }
      function YouTubeGetID(url){
      var ID = '';
      url = url.replace(/(>|<)/gi,'').split(/(vi\/|v=|\/v\/|youtu\.be\/|\/embed\/)/);
        if(url[2] !== undefined) {
          ID = url[2].split(/[^0-9a-z_\-]/i);
          ID = ID[0];
        } else {
            ID = url;
          }
          return ID;
        }

      var name1 = "<?php echo $name1; ?>";
      var name2 = "<?php echo $name2; ?>";

      if(!(url1.length == 0)) {
        window.history.pushState('page2', 'Title', 'http://kelseylegault.com/waitwhat/?q='+name1+'&q2='+name2+'&q3='+result1+'&q4='+result2+'');
      }
      function checkifempty() {
        var input1 = document.getElementById('input1');
        if(input1.value.length == 0) {        
            input1.value = "<?php echo $name1; ?>";
        }
        var input2 = document.getElementById('input2');
        if(input2.value.length == 0) {        
            input2.value = "<?php echo $name2; ?>";
        }
      }

      // Create 2 youtube players, 'player, and 'audio'.
      var player;
      var audio;
      function onYouTubeIframeAPIReady() {
        player = new YT.Player('player', {
          //videoId: result1,
          videoId: result1,
          events: {
          'onReady': onPlayerReady,
          'onStateChange': onPlayerStateChange
          }
        });
        audio = new YT.Player('audio', {
          // videoId: result2,
          videoId: result2,
          events: {
          'onStateChange': onPlayerStateChange2
          }
        });
      }

      // The API will call this function when 'player' is ready.
      function onPlayerReady() {
        // Mute!
        player.mute();
      }

      // The API calls this function when the player's state changes.
      var done = false;
      function onPlayerStateChange(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          setTimeout(loopVideo, 30000);
          done = false;
        }
      }
      // loopVideo is called from the setTimeout function.
      function loopVideo() {
        player.seekTo(1);
      }

      var done2 = false;
      function onPlayerStateChange2(event) {
        if (event.data == YT.PlayerState.PLAYING && !done) {
          setTimeout(loopVideo2, 30000);
          done2 = false;
        }
      }
      // loopVideo is called from the setTimeout function.
      function loopVideo2() {
        audio.seekTo(1);
      }

      //Mute on click
      function muteOnClick() {
        audio.mute();
        document.getElementById('mute').style.display = "none";
        document.getElementById('unmute').style.display = "inline-block";
      }

       //Unmute on click
      function unmuteOnClick() {
        audio.unMute();
        document.getElementById('unmute').style.display = "none";
        document.getElementById('mute').style.display = "inline-block";
      }
    </script>
  </body>
</html>