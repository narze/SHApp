<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configniter extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('file','url','form'));
	}
	
	function index(){
		echo '<h1>Please select config file</h1>';
		$config_dir = get_dir_file_info('application/config/');
		$development_dir = get_dir_file_info('application/config/development/');
		$testing_dir = get_dir_file_info('application/config/testing/');
		$production_dir = get_dir_file_info('application/config/production/');
		
		echo '<h2>/config/</h2>';
		if(!$config_dir){
			echo '<p>No config file found.</p>';
		} else {
			foreach($config_dir as $filename => $info){
				if(($basename = strstr($filename, '.php', TRUE)) !== FALSE){ //is not folder
					echo anchor('configniter/edit_config_file/'.$basename, $filename).'<br />';
				}
			}
		}
		
		echo '<h2>/config/development/</h2>';
		if(!$development_dir){
			echo '<p>No config file found.</p>';
		} else {
			foreach($development_dir as $filename => $info){
				if(($basename = strstr($filename, '.php', TRUE)) !== FALSE){ //is not folder
					echo anchor('configniter/edit_config_file/'.$basename.'/development', $filename).'<br />';
				}
			}
		}
		
		echo '<h2>/config/testing/</h2>';
		if(!$testing_dir){
			echo '<p>No config file found.</p>';
		} else {
			foreach($testing_dir as $filename => $info){
				if(($basename = strstr($filename, '.php', TRUE)) !== FALSE){ //is not folder
					echo anchor('configniter/edit_config_file/'.$basename.'/testing', $filename).'<br />';
				}
			}
		}

		echo '<h2>/config/production/</h2>';
		if(!$production_dir){
			echo '<p>No config file found.</p>';
		} else {
			foreach($production_dir as $filename => $info){
				if(($basename = strstr($filename, '.php', TRUE)) !== FALSE){ //is not folder
					echo anchor('configniter/edit_config_file/'.$basename.'/production', $filename).'<br />';
				}
			}
		}
		echo 'Or input config file name (without .php) ';
		echo form_open('configniter/redirect');
		$data = array(
              'name'        => 'config_name',
              'id'          => 'config_name',
              'value'       => 'config',
              'maxlength'   => '50',
              'size'        => '50',
              'style'       => 'width:50%',
            );
		echo form_input($data);
		echo form_submit('submit', 'Submit');
		echo form_close();
	}

	function redirect(){
		$config_name = $this->input->post('config_name');
		redirect('configniter/edit_config_file/'.$config_name);
	}

	function edit_config_file($config_name, $environment = ''){
		if($environment) { //development, testing, production
			$environment .= '/';
		} else {
			$environment = '';
		}
		$filepath = 'application/config/'.$environment.$config_name.'.php';
		echo 'File : '.$filepath;
		if(!$file_content = read_file($filepath)){
			echo 'File is not readable';
		} else {

			$vars_before_include = get_defined_vars();
			include(APPPATH.'config/'.$environment.$config_name.'.php');
			$vars_after_include = get_defined_vars();
			foreach($vars_before_include as $key => $value){
				unset($vars_after_include[$key]);
			} unset($vars_after_include['vars_before_include']);
			echo '<form method="post" action="'.
			base_url().'configniter/edit_config_file_submit/'.$config_name.'/'.$environment.'">';
			foreach($vars_after_include as $config_var_name => $config_var_value) {
				if(is_array($config_var_value)){
					foreach($config_var_value as $config_key => $config_value) { 
						$config_type = gettype($config_value);?>
						<div id="<?php echo $config_value;?>">
							<span>$<?php echo $config_var_name;?>['<?php echo $config_key;?>'] (<?php echo $config_type;?>)</span>
							<input type="hidden" name="<?php echo $config_var_name;?>[<?php echo $config_key;?>][type]" value="<?php echo $config_type;?>" />
							<?php if(strpos($export_value = var_export($config_value, TRUE), "\n") !== FALSE) : ?>
								<span>
									<textarea style="width: 400px"
									 name="<?php echo $config_var_name;?>[<?php echo $config_key;?>][value]" 
									 rows="<?php echo substr_count($export_value, "\n")+1;?>"><?php echo $export_value;?></textarea>
								</span>
							<?php else : ?>
								<span><input type="text" name="<?php echo $config_var_name;?>[<?php echo $config_key;?>][value]" 
									value="<?php echo trim($export_value, '"\''); ?>"</span>
							<?php endif; ?>
							Update this field <input type="checkbox" name="<?php echo $config_var_name;?>[<?php echo $config_key;?>][edit]" value="1" />
							
						</div>
					<?php
					}
				} else { 
					$config_type = gettype($config_var_value);?>
					<div id="<?php echo $config_var_value;?>">
					<span>$<?php echo $config_var_name;?> (<?php echo $config_type;?>)</span>
					<input type="hidden" name="<?php echo $config_var_name;?>[non_array][type]" value="<?php echo $config_type;?>" />
					<?php if(strpos($export_value = var_export($config_var_value, TRUE), "\n") !== FALSE) : ?>
						<span>
							<textarea style="width
							: 400px" name="<?php echo $config_var_name;?>[non_array][value]" 
							rows="<?php echo substr_count($export_value, "\n")+1;?>"><?php echo $export_value;?></textarea>
						</span>
					<?php else : ?>
						<span><input type="text" name="<?php echo $config_var_name;?>[non_array][value]" 
							value="<?php echo trim($export_value, '"\''); ?>"</span>
					<?php endif; ?>
					Update this field <input type="checkbox" name="<?php echo $config_var_name;?>[non_array][edit]" value="1" />
					<?php
				}
			}
			echo '<input type="submit" />';
			echo '</form>';
		}
	}

	function edit_config_file_submit($config_name, $environment = ''){
		if($environment) { //development, testing, production
			$environment .= '/';
		} else {
			$environment = '';
		}
		$filepath = 'application/config/'.$environment.$config_name.'.php';
		if(!$file_content = read_file($filepath)){
			echo 'File is not readable';
		} else {
			$vars_before_include = get_defined_vars();
			include(APPPATH.'config/'.$environment.$config_name.'.php');
			$vars_after_include = get_defined_vars();
			foreach($vars_before_include as $key => $value){
				unset($vars_after_include[$key]);
			} unset($vars_after_include['vars_before_include']);
			$config_array = $this->input->post();
			foreach($config_array as $config_var_name => $config_var_value){
				foreach($config_var_value as $name => $config_value){
					if(!empty($config_value['edit'])){
						
						$type = $config_value['type'];
						$value = $config_value['value'];
						if(isset($vars_after_include[$config_var_name])){
							if(is_array($vars_after_include[$config_var_name]) && isset($vars_after_include[$config_var_name][$name])){
								$old_value = $vars_after_include[$config_var_name][$name];
							} else {
								$old_value = $vars_after_include[$config_var_name];
							}
						}
						if($value !== $old_value) { //If config exists and have update
							if($type === 'array'){
								eval('$value = '.$value.';');
								$value = var_export($value, TRUE);
							} else if($type === 'boolean'){
								if(strtolower($value)==='true'){
									$value = 'TRUE';
								} else if(strtolower($value)==='false') { //false
									$value = 'FALSE';
								} else {
									continue;
								}
							} else if($type === 'string'){
								$value = "'".str_replace("'", "\'", $value)."'";
							}
							if($name === 'non_array'){
								$config_pattern = '/(\$'.$config_var_name.'\s*=)(\s*[\\\'\"]?(.*)[\\\'\"]?);/Us';
								echo '<div>Edited $'.$config_var_name.' ('.var_export($vars_after_include[$config_var_name], TRUE).' -> '.$value.')</div>';
							} else {
								//Replace old value with new value
								//1. find $config[{$name}] = ___;
								$config_pattern = '/(\$'.$config_var_name.'\[[\\\'\"]'.$name.'[\\\'\"]\]\s*=)(\s*[\\\'\"]?(.*)[\\\'\"]?);/Us';
								//2. replace ___ with new value (var_export)
								echo '<div>Edited $'.$config_var_name.'[\''.$name.'\'] ('.var_export($vars_after_include[$config_var_name][$name], TRUE).' -> '.$value.')</div>';
							}
							$file_content = preg_replace($config_pattern, '${1} '.$value.';', $file_content);

							
						}
					}
				}
			}

			echo '<form method="post" action="'.base_url().'configniter/edit_config_file_write/'.$config_name.'/'.$environment.'" >';
			echo form_textarea(array(
			  'name'        => 'new_file_content',
			  'id'          => 'new_file_content',
			  'value'       => $file_content,
			  'style'		=> 'width:100%; height:80%;'
			));
			echo '<p>Please recheck the input, if the config is not set, please edit it manually here.</p>';
			echo '<input type="submit" value="Write to file" />';
			echo '</form>';
		}
		
	}

	function edit_config_file_write($config_name, $environment = ''){
		if($environment) { //development, testing, production
			$environment .= '/';
		} else {
			$environment = '';
		}
		$filepath = APPPATH.'config/'.$environment.$config_name.'.php';
		if(!read_file($filepath)){
			echo 'File is not readable';
		} else {
			if(!$new_file_content = $this->input->post('new_file_content')){
				echo 'No file content to write';
			} else if(!is_really_writable($filepath)){
				echo 'File is not writable, please make that file or config directory writable';
			} else if(!write_file($filepath, $new_file_content)){
				echo 'File writing failed';
			} else {
				echo 'Config file updated';
			}
		}
	}
}

/* End of file configniter.php */
/* Location: ./application/controllers/configniter.php */