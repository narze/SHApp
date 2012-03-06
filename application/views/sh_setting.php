<?php

$attributes = array('class' => '', 'id' => '');
echo form_open('sh/setting/'.$app_install_id.'/'.$user_id.'/'.$app_install_secret_key, $attributes); ?>
<h1>Setting</h1>
<p>
        <label for="example_field">Example Field</label>
        <?php echo form_error('example_field'); ?>
        <br />
                                                        
        <?php echo form_textarea(array( 
                'name' => 'example_field', 
                'rows' => '5', 
                'cols' => '80', 
                'value' => set_value(
                        'example_field',
                        isset($data['example_field']) ?
                                 ($data['example_field']) : '') 
                )
        );?>
</p>
<p>
        <label for="admin_list">Admin list (comma separated)</label>
	<?php echo form_error('admin_list'); ?>
	<br />
							
	<?php echo form_input(array( 
                'name' => 'admin_list', 
                'value' => set_value(
                        'admin_list',
                        isset($admin_list) ?
                                 ($admin_list) : '') 
                )
        );?>
</p>
<p>
        <?php echo form_submit( 'submit', 'Submit'); ?>
</p>

<?php echo form_close(); ?>
