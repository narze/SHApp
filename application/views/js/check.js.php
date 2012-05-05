<script type="text/javascript">
<?php if(ENVIRONMENT == 'production') : ?>
  window['console']['log'] = function() {};
<?php endif; ?>
var startFallbackCounter = function(){
  console.log('start fallback counter');
  window.fallbackCounter = setTimeout(function(){
    console.log('fallback time out, go to PHP');
    window.location = "<?php echo base_url('home/check');?>";
  }, 10000); // 10 seconds
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


var showLoginPage = function() {
  console.log('showLoginPage');
  clearFallbackCounter();
  document.getElementById('loading').style.display = 'none';
  document.getElementById('login').style.display = 'block';
}

$(function(){
  var checkLike = function(user_id) {
    console.log('user logged in, checking like of user_id', user_id);

    var page_id = "<?php echo $this->config->item('mockuphappen_facebook_page_id');?>";
    var fql_query = "SELECT uid FROM page_fan WHERE page_id = " + page_id + "and uid="+ user_id;
    var the_query = FB.Data.query(fql_query);

    the_query.wait(function(rows) {
      if(rows.length == 1 && rows[0].uid == user_id) {
        showPlayPage();
      } else {
        showForceLikePage();
      }
    });
  }

  window.fbAsyncInit = function() {
    FB.init({
      appId : "<?php echo $this->config->item('facebook_app_id');?>",
      channelUrl : 'channel/fb.php',
      status : true,
      cookie : true,
      xfbml : true
    });

    console.log('facebook is ready');
    
    startFallbackCounter();
    
    FB.Canvas.setAutoResize(7);

    FB.Event.subscribe('auth.statusChange', function(response) {
      console.log('auth.statusChange', response);
      window.fblogin = function () {
        FB.login(function(response) {
          if (response.status === 'connected') {
            window.location = window.location.href;
          }
        }, {scope:'<?php echo $facebook_app_scope;?>'});
      };

      if(response.status === 'connected') {
        checkLike(FB.getUserID());
      } else {
        console.log('auth.statusChange: login status not connect');
        showLoginPage();
      }
    });
  }
});


(function(d){
  var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement('script'); js.id = id; js.async = true;
  js.src = "//connect.facebook.net/en_US/all.js";
  ref.parentNode.insertBefore(js, ref);
}(document));
</script>
