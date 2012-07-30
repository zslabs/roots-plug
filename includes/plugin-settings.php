<?php

/*-----------------------------------------------------------------------------------*/
/*	THIS IS A PLACEHOLDER PAGE FOR SETTINGS THAT MAY BE ADDED IN THE FUTURE
/*-----------------------------------------------------------------------------------*/

function rplug_options_page() {

	global $rplug_options;

	ob_start(); ?>

	<div class="wrap">
		<h2><?php _e('Roots Plug Options','rplug'); ?></h2>
	
		<form method="post" action="options.php" id="rplug_options">

			<?php settings_fields('rplug_settings_group'); ?>

			<h4><?php _e('Image Sizes','rplug'); ?></h4>
			<p class="description"><?php _e('The options below are used to resize the various images that appear throughout Moulding Profiles, Idea Galleries, Moulding Combinations and Moulding Collections. If any values are left blank, they are assumed to be un-restricted. If you put anything other than a number in those boxes, I\'ll take out the screws in your office chair.','rplug'); ?></p>

			<p>
				<label class="description" for="rplug_settings[example_height]"><?php _e('Example Thumbnail Size','rplug'); ?></label>
				<input id="rplug_settings[example_height]" name="rplug_settings[example_height]" type="text" value="<?php echo (isset($rplug_options['example_height'])) ? $rplug_options['example_height'] : ''; ?>">
				<span class="description"><?php _e('height (pixels)','rplug'); ?></span>
				<input id="rplug_settings[example_width]" name="rplug_settings[example_width]" type="text" value="<?php echo (isset($rplug_options['example_width'])) ? $rplug_options['example_width'] : ''; ?>">
				<span class="description"><?php _e('width (pixels)','rplug'); ?></span>
			</p>

			<?php $item_columns = array('1','2','3','4','5','6','7','8','9','10'); ?>

			<p>
				<label class="description" for="rplug_settings[example_columns]"><?php _e('Example Columns','rplug'); ?></label>
				<?php $example_columns = $item_columns ?>
				<select name="rplug_settings[example_columns]" id="rplug_settings[example_columns]">
					<?php foreach($example_columns as $example_column) { ?>
					<?php if($rplug_options['example_columns'] == $example_column) { $selected = 'selected="selected"'; } else { $selected = ''; } ?>
						<option value="<?php echo $example_column; ?>" <?php echo $selected; ?>><?php echo $example_column; ?></option>
					<?php } ?>
				</select>
			</p>


			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Options','rplug'); ?>">
			</p>

		</form>

	</div>

	<?php
	echo ob_get_clean();
}

function rplug_add_options_link() {
	add_options_page(__('Roots Plug Options','rplug'),__('Roots Plug','rplug'),'manage_options','rplug-options','rplug_options_page');
}
add_action('admin_menu','rplug_add_options_link');

function rplug_register_settings() {
	register_setting('rplug_settings_group','rplug_settings');
}
add_action('admin_init','rplug_register_settings');