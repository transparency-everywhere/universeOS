<html>
<head>
<title>Open Graph Getting Started App - og.likes</title>
<style type="text/css">
div { padding: 10px; }
</style>
<meta charset="UTF-8">
</head>
<body>
<div id="fb-root"></div>
<script type="text/javascript">
  // You probably don't want to use globals, but this is just example code
  var fbAppId = '459795090736643';
  var objectToLike = 'http://universeos.org/';

  // This check is just here to make sure you set your app ID. You don't
  // need to use it in production. 
  if (fbAppId === 'replace me') {
    alert('Please set the fbAppId in the sample.');
  }

  // This is boilerplate code that is used to initialize the Facebook
  // JS SDK.  You would normally set your App ID in this code.

  // Additional JS functions here
  window.fbAsyncInit = function() {
    FB.init({
      appId      : fbAppId,        // App ID
      status     : true,           // check login status
      cookie     : true,           // enable cookies to allow the server to access the session
      xfbml      : true            // parse page for xfbml or html5 social plugins like login button below
    });

    // Put additional init code here
  };

  // Load the SDK Asynchronously
  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/en_US/all.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));

  /*
   * This function makes a call to the og.likes API.  The object argument is
   * the object you like.  Other types of APIs may take other arguments.
   * (i.e. the book.reads API takes a book= argument.)
   *
   * Because it's a sample, it also sets the privacy parameter so that it will
   * create a story that only you can see.  Remove the privacy parameter and
   * the story will be visible to whatever the default privacy was when you
   * added the app.
   *
   * Also note that you can view any story with the id, as demonstrated with
   * the code below.
   *
   * APIs used in postLike():
   * Call the Graph API from JS:
   *   https://developers.facebook.com/docs/reference/javascript/FB.api
   * The Open Graph og.likes API:
   *   https://developers.facebook.com/docs/reference/opengraph/action-type/og.likes
   * Privacy argument:
   *   https://developers.facebook.com/docs/reference/api/privacy-parameter
   */

  function postLike() {
    FB.api(
       'https://graph.facebook.com/me/og.likes',
       'post',
       { object: objectToLike,
         privacy: {'value': 'SELF'} },
       function(response) {
         if (!response) {
           alert('Error occurred.');
         } else if (response.error) {
           document.getElementById('result').innerHTML = 'Error: ' + response.error.message;
         } else {
           document.getElementById('result').innerHTML =
             '<a href=\"https://www.facebook.com/me/activity/' + response.id + '\">' +
             'Story created.  ID is ' + response.id + '</a>';
         }
       }
    );
  }
</script>

<!--
  Login Button - https://developers.facebook.com/docs/reference/plugins/login

  This example needs the 'publish_actions' permission in order to publish an
  action.  The scope parameter below is what prompts the user for that permission.
-->

<div
  class="fb-login-button"
  data-show-faces="true"
  data-width="200"
  data-max-rows="1"
  data-scope="publish_actions">
</div>

<div>
This example creates a story on Facebook using the <a href="https://developers.facebook.com/docs/reference/ogaction/og.likes"><code>og.likes</code></a> API.  That story will just say that you like an <a href="http://techcrunch.com/2013/02/06/facebook-launches-developers-live-video-channel-to-keep-its-developer-ecosystem-up-to-date/">article on TechCrunch</a>.  The story should only be visible to you.
</div>

<div>
<input type="button" value="Create a story with an og.likes action" onclick="postLike();">
</div>

<div id="result"></div>

</body>
</html>