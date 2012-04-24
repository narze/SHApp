var showForceLikePage = function(){
  $('div#content').text('please like us first');
}

var showPlayPage = function(){
  $('div#content').text('let\'s play');
}

var showLoginPage = function(){
  $('div#content').html('<a href="#" id="auth-loginlink">Login</a>');
  
  // respond to clicks on the login and logout links
  $('#auth-loginlink').on('click', function(e){
    e.preventDefault();
    FB.login();
  });
}

var checkLike = function(user_id){
  console.log('user logged in, checking like of user_id', user_id);
  
  if(user_id != 0 && user_id != '0'){
    $('#auth-loginlink').hide();
  }
  
  var page_id = "135287989899131"; //
  var fql_query = "SELECT uid FROM page_fan WHERE page_id = "+page_id+"and uid="+user_id;
  var the_query = FB.Data.query(fql_query);

  the_query.wait(function(rows) {

      if (rows.length == 1 && rows[0].uid == user_id) {
          $("#container_like").show();

          //here you could also do some ajax and get the content for a "liker" instead of simply showing a hidden div in the page.
          showPlayPage();
      } else {
          $("#container_notlike").show();
          //and here you could get the content for a non liker in ajax...
          showForceLikePage();
      }
  });
  
}

$(function() {
  window.fbAsyncInit = function() {
    FB.init({
      appId : '204755022911798', // App ID
      channelUrl : 'channel/fb.php', // Channel File
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml : true  // parse XFBML
    });

    // Additional initialization code here

    console.log('facebook is ready');
    
    FB.Canvas.setAutoResize(7);
    FB.getLoginStatus(function(response){
      if(response.status != 'connected'){
        // should redirect to index
        console.log('login status not connect');
        showLoginPage();
        return;
      }
      
      window.user = {
        user_facebook_id : FB.getUserID()
      }
      console.log('got loginStatus', response);
      checkLike(FB.getUserID());
    });
    FB.Event.subscribe('auth.statusChange', function(response){
      console.log('auth.statusChange', response);
      if(response.status != 'connected'){
        // should redirect to index
        console.log('auth.statusChange: login status not connect');
        showLoginPage();
        return;
      }else{
        checkLike(FB.getUserID());
      }
    });
  }
});
// Load the SDK Asynchronously
( function(d) {
    var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
    if(d.getElementById(id)) {
      return;
    }
    js = d.createElement('script');
    js.id = id;
    js.async = true;
    js.src = "//connect.facebook.net/en_US/all.js";
    ref.parentNode.insertBefore(js, ref);
  }(document)); 