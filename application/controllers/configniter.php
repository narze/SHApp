<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Configniter extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('file','url','form'));
	}
	
	function index(){
		//index
		//config.php
		//database.php
		//socialhappen.php
		//socialhappen_actions.php
	}

	// function edit(){
	// 	$filepath = $this->input->get('filepath');
	// 	if(!$file_content = read_file($filepath)){
	// 		echo 'File is not readable';
	// 	} else {
	// 		echo '<html><body><form method="post" action="'.base_url().'configniter/edit_submit" >';
	// 		echo form_textarea(array(
	// 					  'name'        => 'file_content',
	// 					  'id'          => 'file_content',
	// 					  'value'       => $file_content,
	// 			));
	// 		echo '<input type="submit" />';
	// 		echo '</form></body></html>';
	// 	}
	// }

	// function edit_submit(){
	// 	$filepath = $this->input->post('filepath');
	// 	echo nl2br(htmlspecialchars($file_content = $this->input->post('file_content')));
	// 	if ( ! write_file('./test.txt', $file_content))
	// 	{
	// 	     echo 'Unable to write the file';
	// 	}
	// 	else
	// 	{
	// 	     echo 'File written!';
	// 	}

	// }

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
			include(APPPATH.'config/'.$environment.$config_name.'.php');
			//Strip multiline comments
			$file_content = preg_replace('!/\*.*?\*/!s', '', $file_content);
			//Strip singleline comments if it leads $config
			$file_content = preg_replace('!//.*(?=\$config).*\n!', '', $file_content);
			//Remove blank lines
			$file_content = preg_replace('/\n\s*\n/', "\n", $file_content);
			//find $config
			$config_pattern = '/(\$config\[[\\\'\"](.*)[\\\'\"]\]\s*=)(\s*[\\\'\"]?(.*)[\\\'\"]?);/Us';
			if(preg_match_all($config_pattern, $file_content, $matches) !== FALSE){
				// echo '<pre>';
				// var_dump($matches);
				// echo '</pre>';
			} else {
				// echo 'not found';
			}
		}
		echo '<form method="post" action="'.
		base_url().'configniter/edit_config_file_submit/'.$config_name.'/'.$environment.'">';
		include(APPPATH.'config/'.$environment.$config_name.'.php');
		foreach($config as $config_key => $config_value) { 
			$config_type = gettype($config_value);?>
			<div id="<?php echo $config_value;?>">
				<span>$config['<?php echo $config_key;?>'] (<?php echo $config_type;?>)</span>
				<input type="hidden" name="config[<?php echo $config_key;?>][type]" value="<?php echo $config_type;?>" />
				<?php if(strpos($export_value = var_export($config_value, TRUE), "\n") !== FALSE) : ?>
					<span><textarea name="config[<?php echo $config_key;?>][value]" rows="<?php echo substr_count($export_value, "\n")+1;?>">
						<?php echo $export_value;?>
					</textarea></span>
				<?php else : ?>
					<span><input type="text" name="config[<?php echo $config_key;?>][value]" 
						value="<?php echo trim($export_value, '"\''); ?>"</span>
				<?php endif; ?>
				Update this field <input type="checkbox" name="config[<?php echo $config_key;?>][edit]" value="1" />
				
			</div>
		<?php
		}
		echo '<input type="submit" />';
		echo '</form>';
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
			include(APPPATH.'config/'.$environment.$config_name.'.php');
			$config_array = $this->input->post();
			$config_array = $config_array['config'];
			foreach($config_array as $name => $config_value){
				if(!empty($config_value['edit'])){
					
					$type = $config_value['type'];
					$value = $config_value['value'];
					if(isset($config[$name]) && $value !== $config[$name]) { //If config exists and have update
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

						//Replace old value with new value
						//1. find $config[{$name}] = ___;
						$config_pattern = '/(\$config\[[\\\'\"]'.$name.'[\\\'\"]\]\s*=)(\s*[\\\'\"]?(.*)[\\\'\"]?);/Us';
						//2. replace ___ with new value (var_export)
						$file_content = preg_replace($config_pattern, '${1} '.$value.';', $file_content);
						echo '<div>Edited $config[\''.$name.'\'] ('.$config[$name].' -> '.$value.')</div>';

					}
				}
			}

			echo '<form method="post" action="'.base_url().'configniter/edit_config_file_write/'.$config_name.'/'.$environment.'" >';
			echo form_textarea(array(
						  'name'        => 'new_file_content',
						  'id'          => 'new_file_content',
						  'value'       => $file_content,
				));
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