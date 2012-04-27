<script type="text/javascript">
<?php if(ENVIRONMENT == 'production') : ?>
  window['console']['log'] = function() {};
<?php endif; ?>
var startFallbackCounter = function(){
  console.log('start fallback counter');
  window.fallbackCounter = setTimeout(function(){
    console.log('fallback time out, go to PHP');
    window.location = "<?php echo base_url('home/check');?>";
  }, 60000); // one minute
}

var clearFallbackCounter = function(){
  console.log('clear fallback counter');
  clearTimeout(window.fallbackCounter);
}

var showForceLikePage = function() {
  console.log('please like us first');
  clearFallbackCounter();
  window.location = "<?php echo base_url('home/like/');?>";
}
var showPlayPage = function() {
  console.log('let\'s play');
  clearFallbackCounter();
  window.location = "<?php echo base_url('home/play');?>";
}

var checkLike = function(user_id) {
  console.log('user logged in, checking like of user_id', user_id);

  if(user_id != 0 && user_id != '0') {
    $('#auth-loginlink').hide();
  }

  var page_id = "<?php echo $this->config->item('mockuphappen_facebook_page_id');?>";
  //
  var fql_query = "SELECT uid FROM page_fan WHERE page_id = " + page_id + "and uid=" + user_id;
  var the_query = FB.Data.query(fql_query);

  the_query.wait(function(rows) {

    if(rows.length == 1 && rows[0].uid == user_id) {
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
  var showLoginPage = function() {
    console.log('showLoginPage');
    $('#loading').hide();
    $('#login').show();
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId : "<?php echo $this->config->item('facebook_app_id');?>", // App ID
      channelUrl : 'channel/fb.php', // Channel File
      status : true, // check login status
      cookie : true, // enable cookies to allow the server to access the session
      xfbml : true  // parse XFBML
    });

    // Additional initialization code here

    console.log('facebook is ready');
    
    startFallbackCounter();
    
    FB.Canvas.setAutoResize(7);
    FB.getLoginStatus(function(response) {
      window.fblogin = function () {
        FB.login(function(response) {
          if (response.status === 'connected') {
            window.location = window.location.href;
          }
        }, {scope:'<?php echo $facebook_app_scope;?>'});
      };
      
      if(response.status != 'connected') {
        // should redirect to index
        console.log('login status not connect');
        showLoginPage();
        return;
      }
//       
      // window.user = {
        // user_facebook_id : FB.getUserID()
      // }
      // console.log('got loginStatus', response);
      // checkLike(FB.getUserID());
    });
    FB.Event.subscribe('auth.statusChange', function(response) {
      console.log('auth.statusChange', response);
      if(response.status != 'connected') {
        // should redirect to index
        console.log('auth.statusChange: login status not connect');
        showLoginPage();
        return;
      } else {
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
</script>
