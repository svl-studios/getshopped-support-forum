<?php

// hook into the forum atributes meta box

add_action( 'bbp_forum_metabox', 'bbps_extend_forum_attributes_mb' );

/*
 the support forum checkbox will add resolved / not resolved status to all forums */
/* The premium forum will create a support forum that can only be viewed by that user and admin users */
function bbps_extend_forum_attributes_mb( $forum_id ) {

	// get out the forum meta
	$premium_forum = bbps_is_premium_forum( $forum_id );
	if ( $premium_forum ) {
		$checked = 'checked';
	} else {
		$checked = '';
	}

	$support_forum = bbps_is_support_forum( $forum_id );
	if ( $support_forum ) {
		$checked1 = 'checked';
	} else {
		$checked1 = '';
	}

	?>	
	<hr />
	<!-- premium forum needs more work so leaving it out for now - think we should go down a new track of capabilities and hidden forums
<p>
		<strong> _e( 'Premium Forum:', 'bbps' ); </strong>
		<input type="checkbox" name="bbps-premium-forum" value="1"  echo $checked; />
		<br />
		<small>Click here for more information about creating a premium forum.</small>
	</p>
-->
	
	<p>
		<strong><?php _e( 'Support Forum:', 'bbps' ); ?></strong>
		<input type="checkbox" name="bbps-support-forum" value="1" <?php echo $checked1; ?>/>
		<br />
		<!-- <small>Click here To learn more about the support forum setting.</small> -->
	</p>

	<?php
}

// hook into the forum save hook.

add_action( 'bbp_forum_attributes_metabox_save', 'bbps_forum_attributes_mb_save' );

function bbps_forum_attributes_mb_save( $forum_id ) {

	// get out the forum meta
	$premium_forum = get_post_meta( $forum_id, '_bbps_is_premium' );
	$support_forum = get_post_meta( $forum_id, '_bbps_is_support' );

	// if we have a value then save it
	if ( ! empty( $_POST['bbps-premium-forum'] ) ) {
		update_post_meta( $forum_id, '_bbps_is_premium', $_POST['bbps-premium-forum'] );
	}

	// the forum used to be premium now its not
	if ( ! empty( $premium_forum ) && empty( $_POST['bbps-premium-forum'] ) ) {
		update_post_meta( $forum_id, '_bbps_is_premium', 0 );
	}

	// support options
	if ( ! empty( $_POST['bbps-support-forum'] ) ) {
		update_post_meta( $forum_id, '_bbps_is_support', $_POST['bbps-support-forum'] );
	}

	// the forum used to be premium now its not
	if ( ! empty( $premium_forum ) && empty( $_POST['bbps-support-forum'] ) ) {
		update_post_meta( $forum_id, '_bbps_is_support', 0 );
	}

	return $forum_id;

}


// register the settings
function bbps_register_admin_settings() {

	// Add getshopped forum section
	add_settings_section( 'bbps-forum-setting', __( 'User ranking system', 'bbps-forum' ), 'bbps_admin_setting_callback_getshopped_section', 'bbpress' );

	register_setting( 'bbpress', '_bbps_reply_count', 'bbps_validate_options' );
	// user title setting start - this is the start number of the post
	/*
	add_settings_field( '_bbps_reply_count_start', __( 'Replies Between', 'bbps-forum' ), 'bbps_admin_setting_callback_reply_count_start', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_end', __( 'and', 'bbps-forum' ), 'bbps_admin_setting_callback_reply_count_end', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_title', __( 'Custom title', 'bbps-forum' ), 'bbps_admin_setting_callback_reply_count_title',      'bbpress', 'bbps-forum-setting' );
	*/

	// worst code ever starting now
	add_settings_field( '_bbps_reply_count_title1', 'User ranking level 1', 'bbps_admin_setting_callback_reply_count_title1', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_start1', '', 'bbps_admin_setting_callback_reply_count_start1', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_end1', '', 'bbps_admin_setting_callback_reply_count_end1', 'bbpress', 'bbps-forum-setting' );

	add_settings_field( '_bbps_reply_count_title2', 'User ranking level 2', 'bbps_admin_setting_callback_reply_count_title2', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_start2', '', 'bbps_admin_setting_callback_reply_count_start2', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_end2', '', 'bbps_admin_setting_callback_reply_count_end2', 'bbpress', 'bbps-forum-setting' );

	add_settings_field( '_bbps_reply_count_title3', 'User ranking level 3', 'bbps_admin_setting_callback_reply_count_title3', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_start3', '', 'bbps_admin_setting_callback_reply_count_start3', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_end3', '', 'bbps_admin_setting_callback_reply_count_end3', 'bbpress', 'bbps-forum-setting' );

	add_settings_field( '_bbps_reply_count_title4', 'User ranking level 4', 'bbps_admin_setting_callback_reply_count_title4', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_start4', '', 'bbps_admin_setting_callback_reply_count_start4', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_end4', '', 'bbps_admin_setting_callback_reply_count_end4', 'bbpress', 'bbps-forum-setting' );

	add_settings_field( '_bbps_reply_count_title5', 'User ranking level 5', 'bbps_admin_setting_callback_reply_count_title5', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_start5', '', 'bbps_admin_setting_callback_reply_count_start5', 'bbpress', 'bbps-forum-setting' );
	add_settings_field( '_bbps_reply_count_end5', '', 'bbps_admin_setting_callback_reply_count_end5', 'bbpress', 'bbps-forum-setting' );

	// worst code ever ends now

	// show post count
	add_settings_field( '_bbps_enable_post_count', __( 'Show forum post count', 'bbps-forum' ), 'bbps_admin_setting_callback_post_count', 'bbpress', 'bbps-forum-setting' );
	register_setting( 'bbpress', '_bbps_enable_post_count', 'intval' );

	// default topic option

	// Add the forum status section
	add_settings_section( 'bbps-status-setting', __( 'Topic Status Settings', 'bbps-forum' ), 'bbps_admin_setting_callback_status_section', 'bbpress' );

	register_setting( 'bbpress', '_bbps_default_status', 'intval' );
	add_settings_field( '_bbps_default_status', __( 'Default Status:', 'bbps-forum' ), 'bbps_admin_setting_callback_default_status', 'bbpress', 'bbps-status-setting' );

	// default topic option
	register_setting( 'bbpress', '_bbps_used_status', 'bbps_validate_status_selection' );
	// each drop down option for selection
	add_settings_field( '_bbps_used_status_1', __( 'Display Status:', 'bbps-forum' ), 'bbps_admin_setting_callback_displayed_status_res', 'bbpress', 'bbps-status-setting' );
	add_settings_field( '_bbps_used_status_2', __( 'Display Status:', 'bbps-forum' ), 'bbps_admin_setting_callback_displayed_status_notres', 'bbpress', 'bbps-status-setting' );
	add_settings_field( '_bbps_used_status_3', __( 'Display Status:', 'bbps-forum' ), 'bbps_admin_setting_callback_displayed_status_notsup', 'bbpress', 'bbps-status-setting' );

	// who can update the status
	register_setting( 'bbpress', '_bbps_status_permissions', 'intval' );
	// each drop down option for selection
	add_settings_field( '_bbps_status_permissions_admin', __( 'Admin', 'bbps-forum' ), 'bbps_admin_setting_callback_permission_admin', 'bbpress', 'bbps-status-setting' );
	add_settings_field( '_bbps_status_permissions_user', __( 'Forum Moderator', 'bbps-forum' ), 'bbps_admin_setting_callback_permission_user', 'bbpress', 'bbps-status-setting' );
	add_settings_field( '_bbps_status_permissions_moderator', __( 'Topic Creator', 'bbps-forum' ), 'bbps_admin_setting_callback_permission_moderator', 'bbpress', 'bbps-status-setting' );

	/*
	register_setting  ( 'bbpress', '_bbps_status_color_change', 'bbps_validate_status_permissions' );
	add_settings_field( '_bbps_status_color_change', __( 'Change colour of resolved topics', 'bbps-forum' ), 'bbps_admin_setting_callback_color_change', 'bbpress', 'bbps-status-setting' );
	*/

}
add_action( 'bbp_register_admin_settings', 'bbps_register_admin_settings' );



function bbps_validate_status_selection( $input ) {
	$options = get_option( '_bbps_used_status' );
	// update only the needed options
	foreach ( $input as $key => $value ) {
		$newoptions[ $key ] = $value;
	}
	// return all options
	return $newoptions;
}


function bbps_validate_status_permissions( $input ) {
	$options = get_option( '_bbps_status_permissions' );
	// exit('<pre> options'.print_r($options,1).'</pre>'.'<pre>input'.print_r($input,1).'</pre>');
	// update only the needed options
	foreach ( $input as $key => $value ) {
		$newoptions[ $key ] = $value;
	}
	// exit('<pre> options'.print_r($newoptions,1).'</pre>');
	// return all options
	return $newoptions;
}

function bbps_validate_options( $input ) {

	$options = get_option( '_bbps_reply_count' );

	$i = 1;
	foreach ( $input as $array ) {
		foreach ( $array as $key => $value ) {
			  $options[ $i ][ $key ] = $value;

		}
			$i++;
	}
	return $options;
}


?>
