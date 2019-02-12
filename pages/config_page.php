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
require_api( 'custom_field_api.php' );
require_api( 'project_api.php' );

access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
layout_page_header( plugin_lang_get( 'title' ) );
layout_page_begin( 'manage_overview_page.php' );
print_manage_menu( 'manage_plugin_page.php' );
?>
<div class="col-md-12 col-xs-12">
    <div class="space-10"></div>
    <div class="form-container">
        <form action="<?php echo plugin_page( 'config' ) . '&plugin_hotfixversionsupport_mode=general' ?>" method="post">
            <fieldset>
                <div class="widget-box widget-color-blue2">
                    <div class="widget-header widget-header-small">
                        <h4 class="widget-title lighter">
                            <i class="ace-icon fa fa-exchange"></i>
                            <?php echo plugin_lang_get( 'general_config' ) ?>
                        </h4>
                    </div>
                    <?php echo form_security_field( 'plugin_hotfixversionsupport_config' ) ?>
                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            <div class="table-responsive">
                                <table class="table table-bordered table-condensed table-striped table-hover">                                    
                                    <tr>
                                        <td class="category">
                                            <?php echo plugin_lang_get('cf_target_hotfix') ?>
                                        </td>
                                        <td class="center">
                                            <label><select name="custom_field_id_target_hotfix">
                                                <?php
                                                $current = plugin_config_get('custom_field_id_target_hotfix');
                                                foreach (custom_field_get_ids() as $cfid) {
                                                echo '<option value="' . $cfid . '" ' . ($cfid == $current ? ' selected="selected"' : '') . '>' . custom_field_get_definition($cfid)['name'] . '</option>';
                                                }
                                                ?>
                                            </select></label>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="category">
                                            <?php echo plugin_lang_get('cf_fixed_in_hotfix') ?>
                                        </td>
                                        <td class="center">
                                            <label><select name="custom_field_id_fixed_in_hotfix">
                                                <?php
                                                $current = plugin_config_get('custom_field_id_fixed_in_hotfix');
                                                foreach (custom_field_get_ids() as $cfid) {
                                                echo '<option value="' . $cfid . '" ' . ($cfid == $current ? ' selected="selected"' : '') . '>' . custom_field_get_definition($cfid)['name'] . '</option>';
                                                }
                                                ?>
                                            </select></label>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'action_update' ) ?>" />
                </div>
                </div>
            </fieldset>        
        </form>
    </div>

    <div class="space-10"></div>
    <div class="form-container">
        <div class="widget-box widget-color-blue2">
            <div class="widget-header widget-header-small">
                <h4 class="widget-title lighter">
                    <i class="ace-icon fa fa-exchange"></i>
                    <?php echo plugin_lang_get( 'project_config' ) ?>
                </h4>
            </div>

        <div class="widget-body">
        <div class="widget-main no-padding">
            <form action="<?php echo plugin_page( 'config' ) . '&plugin_hotfixversionsupport_mode=save_project_config' ?>" method="post">
                <?php echo form_security_field( 'plugin_hotfixversionsupport_config' ) ?>
                <div class="table-responsive">
                   <table class="table table-bordered table-condensed table-striped table-hover">
                    <thead>
                        <tr>
                            <th><?php echo lang_get( 'project_name'); ?></th>
                            <th><?php echo plugin_lang_get( 'hotfixversion_values'); ?></th>
                            <th><?php echo plugin_lang_get( 'project_remove'); ?></th>
                        </tr>
                    </thead>
                <?php
                    // list projects settings                        
                    $enabled_projects = array_filter(explode(',', plugin_config_get( 'projects' )));            
                    foreach($enabled_projects as $pid) {                
                        echo '<tr>';                

                        echo '<td class="category">' . project_get_name($pid) . '</td>';
                        echo '<td> ' .
                             '<input type="text" style="width:100%" name="p' . $pid . '_hotfix_versions" value="' . plugin_config_get('p' . $pid . '_hotfix_versions', '') . '">' .
                              '</td>';
                        echo '<td class="center">' .
                             '<input type="checkbox" name="p' . $pid . '_remove" />' .
                             '</td';
                        echo '</tr>';
                    }                        
                ?>
                   </table>
                </div>

                <div class="widget-toolbox padding-8 clearfix">                                        
                    <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'action_update' ) ?>" />                                
                </div>            
            </form>
        </div>
    </div>

        <div class="widget-toolbox padding-8 clearfix">            
            <form action="<?php echo plugin_page( 'config' ) . '&plugin_hotfixversionsupport_mode=add_project' ?>" method="post">
                <?php echo form_security_field( 'plugin_hotfixversionsupport_config' ) ?>
                <label><?php echo plugin_lang_get( 'project_add'); ?></label>
                <select name="project_to_add">
                    <?php

                    $already_contained_projects = array_filter(explode(',', plugin_config_get( 'projects' )));  

                    foreach(project_cache_all() as $p) {
                        if (!in_array($p['id'], $already_contained_projects)) {
                           echo '<option value="' . $p['id'] . '">' . $p['name'] . '</option>';
                        }
                    }

                    ?>
                </select>
                <input type="submit" class="btn btn-primary btn-white btn-round" value="<?php echo plugin_lang_get( 'action_add' ) ?>" />
            </form>
        </div>    
        </div>
    </div>
</div>
<?php
layout_page_end();