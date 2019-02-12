<?php

require_api( 'helper_api.php' );

/**
 * Return the list of currently existing hotfix-versions
 * from the HotfixVersionSupport Plugin
 *
 */
function custom_function_override_enum_hotfixversions() {   
	$t_basename = 'HotfixVersionSupport';
	$project_id = helper_get_current_project();
	$p_option = 'p' . $project_id . '_hotfix_versions';
	$t_full_option = 'plugin_' . $t_basename . '_' . $p_option;

	$p_project = null;
	$p_default = '';
	$p_user = null;
	
	return config_get( $t_full_option, $p_default, $p_user, $p_project );
}