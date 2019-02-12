<?php
/**
 * Lightbox Integration
 * Copyright (C) Karim Ratib (karim@meedan.com)
 *
 * Lightbox Integration is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * Lightbox Integration is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Lightbox Integration; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA
 * or see http://www.gnu.org/licenses/.
 */

form_security_validate( 'plugin_hotfixversionsupport_config' );
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );


$t_redirect_url = plugin_page( 'config_page', true );
layout_page_header( null, $t_redirect_url );
layout_page_begin();

$mode = $_GET['plugin_hotfixversionsupport_mode'];

if ($mode == 'general') {
	// general options
	// plugin_config_set('hotfix_versions',  gpc_get_string('hotfix_versions'));
	plugin_config_set('custom_field_id_target_hotfix',  gpc_get_int( 'custom_field_id_target_hotfix'));
	plugin_config_set('custom_field_id_fixed_in_hotfix',  gpc_get_int( 'custom_field_id_fixed_in_hotfix'));
} else if ($mode == 'save_project_config') {

	$projects = plugin_config_get( 'projects' );
	$pp = array_filter(explode(",", $projects));

	$deleted = false;

	foreach($pp as $pid) {
		$vname = 'p' . $pid . '_hotfix_versions';
		// should the project be removed?
		if (gpc_get_bool('p' . $pid . '_remove', false)) {			
			unset($pp[array_search($pid, $pp)]);
			plugin_config_delete($vname);

			$deleted = true;
		} else {
			$val = gpc_get_string($vname);
			plugin_config_set($vname,  $val);			
		}
	}

	if ($deleted) {
		$pp = implode(",", $pp);
		plugin_config_set('projects', $pp);
	}

} else if ($mode == 'add_project') {
	// add project
	$project_to_add = gpc_get_int('project_to_add');
	$projects = plugin_config_get( 'projects' );
	$pp = array_filter(explode(",", $projects));

	if (!in_array($project_to_add, $pp)) {
		$pp[] = $project_to_add;
		$pp = implode(",", $pp);
		plugin_config_set('projects', $pp);
	}	
}

form_security_purge( 'plugin_hotfixversionsupport_config' );

html_operation_successful( $t_redirect_url );
layout_page_end();
