<?php
/*
** --------------------------------------------------------------
** MantisBT HotfixView Plugin
** --------------------------------------------------------------
** v1.0
**
** Copyright (c) 2019, René Bergelt - www.renebergelt.de
*/

class HotfixVersionSupportPlugin extends MantisPlugin {

    public function register() {
        $this->name = plugin_lang_get( 'title' );
        $this->description = plugin_lang_get( 'description' );

        $this->version = '1.0';
        $this->requires = array(
        'MantisCore' => '2.0.0'
        );
        $this->page = 'config_page';

        $this->author = 'René Bergelt';
        $this->contact = 'berre@cs.tu-chemnitz.de';
        $this->url = 'www.renebergelt.de';        
    }

	function config() {
        return array(            
            'projects' => '',
            'custom_field_id_target_hotfix' => 0,
            'custom_field_id_fixed_in_hotfix' => 0
        );
    }

    public function hooks() {
		$t_hooks = array(			
			'EVENT_MENU_MAIN' => 'hotfix_menu',
		);
		return $t_hooks;
    }    
	
	function hotfix_menu( $p_event, $p_params ) {		
        // only add the option if the current project supports it
        $project_id = helper_get_current_project();
        $p_option = 'p' . $project_id . '_hotfix_versions';
        if (plugin_config_get($p_option, '') != '') {

    		// check if we are the currently selected menu item
    		if ($_SERVER["REQUEST_URI"] == plugin_page( 'hotfix_overview.php')) {
    			$current_page = plugin_page( 'hotfix_overview.php');
    		} else {
    			$current_page = '';
    		}
    	
    		layout_sidebar_menu( plugin_page( 'hotfix_overview.php'), plugin_lang_get( 'sidebar_title' ), 'fa-fire-extinguisher', $current_page );				
        }
	}
}