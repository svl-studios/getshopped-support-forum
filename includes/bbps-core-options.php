<?php
//add the option on the activation of this plugin
add_action( 'bbps-activation',   'bbps_add_options'  );

/*
function bbps_add_options
Creates the default options
simply extend the array to add more options.

Note: These options only get added on 
activation so if your adding more options
you will need to reactivate your plugin
*/
function bbps_add_options() {

	// Default options
	$options = array (
	//user counts and titles
		// The default display for topic status we used not resolved as default
		'_bbps_default_status'  => '1',
		//enable user post count display
		'_bbps_enable_post_count'   => '1',
		//defaults for who can change the topic status
		'_bbps_status_permissions'   => '',
		// the reply counts / boundaries for the custom forum poster titles this has no default as the user must set these
		'_bbps_reply_count'    => '',
		//the status people want to show on their topics.
		'_bbps_used_status'        => '',
		//do a color change for resolved topics
		//'_bbps_status_color_change' => '1',
	);
	// Add default options
	foreach ( $options as $key => $value )
		add_option( $key, $value );

}

function bbps_is_post_count_enabled(){
	return get_option( '_bbps_enable_post_count' );
}

function bbps_is_resolved_enabled(){
	$options = get_option( '_bbps_used_status' );
	return $options['res'];

}

/*
function bbps_is_color_change_enabled(){
	return get_option( '_bbps_status_color_change' );
}
*/

function bbps_is_not_resolved_enabled(){
	$options = get_option( '_bbps_used_status' );
	return $options['notres'];
}

function bbps_is_not_support_enabled(){
	$options = get_option( '_bbps_used_status' );
	return $options['notsup'];
}

function bbps_is_moderator_enabled(){
	$options = get_option( '_bbps_status_permissions' );
	return $options['mod'];	
}

function bbps_is_admin_enabled(){
	$options = get_option( '_bbps_status_permissions' );
	return $options['admin'];	
}

function bbps_is_user_enabled(){
	$options = get_option( '_bbps_status_permissions' );
	return $options['user'];	
}



?>