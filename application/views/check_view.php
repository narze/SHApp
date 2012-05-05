<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="https://www.facebook.com/2008/fbml">
  <head>
    <title>Loading</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php if($static_server_enable) { ?>
    <link rel="stylesheet" href="<?php echo $static_server_path.'css/bootstrap.css';?>" />
    <?php } else { ?>
    <link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.css');?>" />
    <?php } ?>
  </head>
  <body>
    <div id='fb-root'></div>

    <div id="content">
      <center>
        <div id="loading" style="height:101px;width:403px;margin:0 auto;">
          <p>Loading...</p>
          <div class="progress progress-striped progress-info active">
            <div class="bar" style="width: 100%;"></div>
          </div>
        </div>
      </center>
      <div id="login" style="display:none;">
        <center>
          <a id="fblogin" onclick="fblogin();" style="cursor:pointer;"><img src="<?php echo $static_server_enable ? $static_server_path.'images/start.jpg' : base_url('assets/images/start.jpg'); ?>" /></a>
          <div><a target="_blank" href="<?php echo base_url('privacy_policy');?>">Privacy Policy</a></div>
          <div><a target="_blank" href="<?php echo base_url('terms_of_service');?>">Terms of Service</a></div>
        </center>
      </div>
    </div>

    <script src="<?php echo base_url('assets/js/jquery-1.7.1.min.js');?>"></script>
    <?php $this->load->view('js/check.js.php'); ?>

  </body>
</html>
