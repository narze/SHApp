<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="https://www.facebook.com/2008/fbml">
  <head>
    <title>Loading</title>
  </head>
  <body>
    <div id='fb-root'></div>

    <div id="content">
      <div id="loading">Loading ...</div>
      <div id="login" style="display:none;">
        <center>
          <a id="fblogin" onclick="fblogin();" style="cursor:pointer;"><img src="<?php echo base_url('assets/images/start.jpg');?>" /></a>
          <div><a target="_blank" href="<?php echo base_url('privacy_policy');?>">Privacy Policy</a></div>
          <div><a target="_blank" href="<?php echo base_url('terms_of_service');?>">Terms of Service</a></div>
        </center>
      </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery-1.7.1.min.js');?>"></script>
    <?php $this->load->view('js/check.js.php'); ?>

  </body>
</html>
