<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Display List of Hotfixes and Tickets
 *
 * @package MantisBT
 * @copyright Copyright 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright 2002  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @link http://www.mantisbt.org
 *
 * @uses core.php
 * @uses access_api.php
 * @uses authentication_api.php
 * @uses bug_api.php
 * @uses category_api.php
 * @uses config_api.php
 * @uses constant_inc.php
 * @uses database_api.php
 * @uses error_api.php
 * @uses filter_api.php
 * @uses filter_constants_inc.php
 * @uses gpc_api.php
 * @uses helper_api.php
 * @uses html_api.php
 * @uses lang_api.php
 * @uses print_api.php
 * @uses project_api.php
 * @uses string_api.php
 * @uses user_api.php
 * @uses utility_api.php
 * @uses version_api.php
 */

require_once( 'core.php' );
require_api( 'access_api.php' );
require_api( 'authentication_api.php' );
require_api( 'bug_api.php' );
require_api( 'category_api.php' );
require_api( 'config_api.php' );
require_api( 'constant_inc.php' );
require_api( 'database_api.php' );
require_api( 'error_api.php' );
require_api( 'filter_api.php' );
require_api( 'filter_constants_inc.php' );
require_api( 'gpc_api.php' );
require_api( 'helper_api.php' );
require_api( 'html_api.php' );
require_api( 'lang_api.php' );
require_api( 'print_api.php' );
require_api( 'project_api.php' );
require_api( 'string_api.php' );
require_api( 'user_api.php' );
require_api( 'utility_api.php' );
require_api( 'version_api.php' );
require_api( 'custom_field_api.php' );

/**
 * Print header for the specified project version.
 * @param array $p_version_row Array containing project version data.
 * @return void
 */
function print_version_header( $t_project_id, $t_version_name, $custom_field_id ) {
	$t_project_name = project_get_field( $t_project_id, 'name' );

	$t_release_title = '<a class="white" href="roadmap_page.php?project_id=' . $t_project_id . '">' . string_display_line( $t_project_name ) . '</a>';
	$t_release_title .= ' - <a class="white" href="roadmap_page.php?version_id=' . $t_version_name . '">' . string_display_line( $t_version_name ) . '</a>';

	$t_block_id = 'roadmap_' . $t_version_id;
	$t_collapse_block = is_collapsed( $t_block_id );
	$t_block_css = $t_collapse_block ? 'collapsed' : '';
	$t_block_icon = $t_collapse_block ? 'fa-chevron-down' : 'fa-chevron-up';

	echo '<div id="' . $t_block_id . '" class="widget-box widget-color-blue2 ' . $t_block_css . '">';
	echo '<div class="widget-header widget-header-small">';
	echo '<h4 class="widget-title lighter">';
	echo '<i class="ace-icon fa fa-road"></i>';
	echo $t_release_title, lang_get( 'word_separator' );
	echo '</h4>';
	echo '<div class="widget-toolbar">';
	echo '<a data-action="collapse" href="#">';
	echo '<i class="1 ace-icon fa ' . $t_block_icon . ' bigger-125"></i>';
	echo '</a>';
	echo '</div>';
	echo '</div>';

	echo '<div class="widget-body">';
	echo '<div class="widget-toolbox padding-8 clearfix">';
	if( $t_scheduled_release_date ) {
		echo '<div class="pull-left"><i class="fa fa-calendar-o fa-lg"> </i> ' . $t_scheduled_release_date . '</div>';
	}
	echo '<div class="btn-toolbar pull-right">';
	echo '<a class="btn btn-xs btn-primary btn-white btn-round" ';
	echo 'href="view_all_set.php?type=1&temporary=y&' . FILTER_PROPERTY_PROJECT_ID . '=' . $t_project_id .
		 '&' . filter_encode_field_and_value( 'custom_field_' . $custom_field_id , $t_version_name ) .
		 '&' . FILTER_PROPERTY_HIDE_STATUS . '=' . META_FILTER_NONE . '">';
	echo lang_get( 'view_bugs_link' );
	//echo '<a class="btn btn-xs btn-primary btn-white btn-round" href="roadmap_page.php?version_id=' . $t_version_id . '">' . string_display_line( $t_version_name ) . '</a>';
	echo '<a class="btn btn-xs btn-primary btn-white btn-round" href="roadmap_page.php?project_id=' . $t_project_id . '">' . string_display_line( $t_project_name ) . '</a>';
	echo '</a>';
	echo '</div>';

	echo '</div>';
	echo '<div class="widget-main">';
}

/**
 * Print footer for the specified project version.
 * @param array $p_version_row array contain project version data
 * @param int $p_issues_resolved number of issues in resolved state
 * @param int $p_issues_planned number of issues planned for this version
 * @param int $p_progress percentage progress
 * @return void
 */
function print_version_footer( $t_project_id, $t_version_name, $p_issues_resolved, $p_issues_planned, $p_progress ) {	
	echo '</div>';

	if( $p_issues_planned > 0 ) {
		echo '<div class="widget-toolbox padding-8 clearfix">';
		echo sprintf( lang_get( 'resolved_progress' ), $p_issues_resolved, $p_issues_planned, $p_progress );
		echo ' <a class="btn btn-xs btn-primary btn-white btn-round" ';
		echo 'href="view_all_set.php?type=1&temporary=y&' . FILTER_PROPERTY_PROJECT_ID . '=' . $t_project_id .
			 '&' . filter_encode_field_and_value( FILTER_PROPERTY_TARGET_VERSION, $t_version_name ) .
			 '&' . FILTER_PROPERTY_HIDE_STATUS . '=' . META_FILTER_NONE . '">';
		echo lang_get( 'view_bugs_link' );
		echo '</a>';
		echo '</div>';
	}

	echo '</div></div>';
	echo '<div class="space-10"></div>';
}

/**
 * print project header
 * @param string $p_project_name Project name.
 * @return void
 */
function print_project_header_hotfixes( $p_project_name ) {
	echo '<div class="page-header">';
	echo '<h1><strong>' . string_display_line( $p_project_name ), '</strong> - ', plugin_lang_get( 'page_title' ) . '</h1>';
	echo '</div>';
}

function hotfixview_print_issue( $p_issue_id, $is_fixed_in_hotfix, $p_issue_level = 0 ) {
	static $s_status;

	$t_bug = bug_get( $p_issue_id );
	$t_current_user = auth_get_current_user_id();

	if( $is_fixed_in_hotfix ) {
		$t_strike_start = '<s>';
		$t_strike_end = '</s>';
	} else {
		$t_strike_start = $t_strike_end = '';
	}

	if( $t_bug->category_id ) {
		$t_category_name = category_get_name( $t_bug->category_id );
	} else {
		$t_category_name = '';
	}

	$t_category = is_blank( $t_category_name ) ? '' : '<strong>[' . string_display_line( $t_category_name ) . ']</strong> ';

	if( !isset( $s_status[$t_bug->status] ) ) {
		$s_status[$t_bug->status] = get_enum_element( 'status', $t_bug->status, $t_current_user, $t_bug->project_id );
	}

	# choose color based on status
	$status_label = html_get_status_css_class( $t_bug->status, $t_current_user, $t_bug->project_id );
	$t_status_title = string_attribute( get_enum_element( 'status', bug_get_field( $t_bug->id, 'status' ), $t_bug->project_id ) );;

	echo utf8_str_pad( '', $p_issue_level * 36, '&#160;' );
	# since the status is not related to the hotfix, do not show it
	#echo '<i class="fa fa-square fa-status-box ' . $status_label . '" title="' . $t_status_title . '"></i> ';
	echo string_get_bug_view_link( $p_issue_id, false );
	echo ': <span class="label label-light">', $t_category, '</span> ', $t_strike_start, string_display_line_links( $t_bug->summary ), $t_strike_end;
	if( $t_bug->handler_id > 0
			&& ON == config_get( 'show_assigned_names', null, $t_current_user, $t_bug->project_id )
			&& access_can_see_handler_for_bug( $t_bug ) ) {
		echo ' (', prepare_user_name( $t_bug->handler_id ), ')';
	}
	echo '<div class="space-2"></div>';
}


$t_issues_found = false;

$custom_field_id_target_hotfix = plugin_config_get('custom_field_id_target_hotfix');
$custom_field_id_fixed_in_hotfix = plugin_config_get('custom_field_id_fixed_in_hotfix');

$t_user_id = auth_get_current_user_id();

$f_project = gpc_get_string( 'project', '' );
if( is_blank( $f_project ) ) {
	$f_project_id = gpc_get_int( 'project_id', -1 );
} else {
	$f_project_id = project_get_id_by_name( $f_project );

	if( $f_project_id === 0 ) {
		error_parameters( $f_project );
		trigger_error( ERROR_PROJECT_NOT_FOUND, ERROR );
	}
}

$f_version = gpc_get_string( 'version', '' );

if( is_blank( $f_version ) ) {
	$f_version_id = gpc_get_int( 'version_id', -1 );

	# If both version_id and project_id parameters are supplied, then version_id take precedence.
	if( $f_version_id == -1 ) {
		if( $f_project_id == -1 ) {
			$t_project_id = helper_get_current_project();
		} else {
			$t_project_id = $f_project_id;
		}
	} else {
		$t_project_id = version_get_field( $f_version_id, 'project_id' );
	}
} else {
	if( $f_project_id == -1 ) {
		$t_project_id = helper_get_current_project();
	} else {
		$t_project_id = $f_project_id;
	}

	$f_version_id = version_get_id( $f_version, $t_project_id );

	if( $f_version_id === false ) {
		error_parameters( $f_version );
		trigger_error( ERROR_VERSION_NOT_FOUND, ERROR );
	}
}

if( ALL_PROJECTS == $t_project_id ) {
	$t_project_ids_to_check = user_get_all_accessible_projects( $t_user_id, ALL_PROJECTS );
	$t_project_ids = array();

	foreach ( $t_project_ids_to_check as $t_project_id ) {
		$t_roadmap_view_access_level = config_get( 'roadmap_view_threshold', null, null, $t_project_id );
		if( access_has_project_level( $t_roadmap_view_access_level, $t_project_id ) ) {
			$t_project_ids[] = $t_project_id;
		}
	}
} else {
	access_ensure_project_level( config_get( 'roadmap_view_threshold' ), $t_project_id );
	$t_project_ids = user_get_all_accessible_subprojects( $t_user_id, $t_project_id );
	array_unshift( $t_project_ids, $t_project_id );
}

$t_project_id_for_access_check = $t_project_id;
$t_project_name = project_get_field( $t_project_id, 'name' );

layout_page_header( plugin_lang_get('page_title') );

layout_page_begin(__FILE__);

print_project_header_hotfixes( $t_project_name );

echo '<div class="col-md-12 col-xs-12">';

if (!custom_field_is_linked($custom_field_id_target_hotfix, $t_project_id) && !custom_field_is_linked($custom_field_id_fixed_in_hotfix, $t_project_id)) {
	echo '<p class="lead">' . plugin_lang_get( 'project_unsupported') . '</p>';
} else {
	
	// get all hotfix values for the current project	
	$cf_target_hotfix_def = custom_field_get_definition($custom_field_id_target_hotfix);	
	$hotfixes = array_filter(custom_field_distinct_values($cf_target_hotfix_def, $t_project_id));
	
	natsort($hotfixes);
	$hotfixes = array_reverse($hotfixes);
	
	foreach($hotfixes as $hotfix_version) {		
		print_version_header($t_project_id, $hotfix_version, $custom_field_id_target_hotfix);
		
		// get all bug ids from the project associated with the given hotfix version (target)
		$t_filter = array(
			FILTER_PROPERTY_HIDE_STATUS => array( META_FILTER_NONE ),
			FILTER_PROPERTY_PROJECT_ID => $t_project_id,
			'_view_type' => FILTER_VIEW_TYPE_ADVANCED,
			'custom_fields' => array ( $custom_field_id_target_hotfix => $hotfix_version )
		);
		$t_filter = filter_ensure_valid_filter( $t_filter );
		$fq = new BugFilterQuery( $t_filter, BugFilterQuery::QUERY_TYPE_IDS );
		$bug_ids_target = $fq->execute();
		
		// get all bug ids from the project already fixed in the hotfix version
		$t_filter = array(
			FILTER_PROPERTY_HIDE_STATUS => array( META_FILTER_NONE ),
			FILTER_PROPERTY_PROJECT_ID => $t_project_id,
			'_view_type' => FILTER_VIEW_TYPE_ADVANCED,
			'custom_fields' => array ( $custom_field_id_fixed_in_hotfix => $hotfix_version )
		);
		$t_filter = filter_ensure_valid_filter( $t_filter );
		$fq = new BugFilterQuery( $t_filter, BugFilterQuery::QUERY_TYPE_IDS );
		$bug_ids_fixed = $fq->execute();	

		$all_bug_ids = array();				
		$all_fixed_ids = array();	
		
		foreach($bug_ids_target as $tid) {
			$all_bug_ids[] = $tid['id'];
		}
		foreach($bug_ids_fixed as $tid) {
			if (!in_array($tid['id'], $all_bug_ids)) {
				$all_bug_ids[] = $tid['id'];
			}
			
			$all_fixed_ids[] = $tid['id'];
		}
		
		// progress
		if (count($all_bug_ids) == 0) {
			$t_progress = 0;
		} else {
			$t_progress = floor(count($all_fixed_ids) * 100 / count($all_bug_ids));
		}		
		
		echo '<div class="space-4"></div>';
		echo '<div class="col-md-7 col-xs-12 no-padding">';
		echo '<div class="progress progress-striped" data-percent="' . $t_progress . '%" >';
		echo '<div style="width:' . $t_progress . '%;" class="progress-bar progress-bar-success"></div>';
		echo '</div></div>';
		echo '<div class="clearfix"></div>';
		
		// print bugs
		
		foreach ($all_bug_ids as $bug_id) {			
			hotfixview_print_issue($bug_id, in_array($bug_id, $all_fixed_ids));
		}
		
		print_version_footer( $t_project_id, $hotfix_version, $p_issues_resolved, $p_issues_planned, $p_progress );
	}
	
	if( count($hotfixes) == 0 ) {
		if( access_has_project_level( config_get( 'manage_project_threshold' ), $t_project_id_for_access_check ) ) {
			$t_string = 'no_hotfixes_available_manager';
		} else {
			$t_string = 'no_hotfixes_available';
		}

		echo '<br />';
		echo '<p class="lead">' . plugin_lang_get( $t_string ) . '</p>';
	}
}

echo '</div>';
layout_page_end();
