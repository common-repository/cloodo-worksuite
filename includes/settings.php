<?php 
if ( !function_exists( 'add_action' ) ) {
    echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
    exit;
}

// check if the current user has the ability to manage options in WordPress
if ( ! current_user_can( 'manage_options' ) ) {
    return;
}

if (!empty(get_option('cloodo_token'))) {
    if ( admin_url('users.php') ) {
        // registers a settings error to be displayed to the user
        if ( isset( $_GET['settings-updated'] ) ) {
            add_settings_error( 
                'setting_clws_messages', //Slug title of the setting to which this error applies
                'setting_clws_message', //Slug-name to identify the error - id(html)
                __( 'Settings Saved', 'setting_clws' ), //message text to display to the user - <div> <p>
                'updated' //message type - include 'error', 'success', 'warning', 'info'
            );
        }
    }
}

wp_enqueue_style('bootstrap.css', plugins_url('../admin/css/bootstrap.min.css', __FILE__ ));
wp_enqueue_style('fontawesome.css', plugins_url('../admin/css/fontawesome.min.css', __FILE__ ));
wp_enqueue_script('boostrap.js', plugins_url('../admin/js/bootstrap.min.js',__FILE__));
wp_enqueue_style('core.css', plugins_url('../admin/css/core.css', __FILE__ ));
wp_enqueue_style('theme-default.css', plugins_url('../admin/css/theme-default.css', __FILE__ ));
wp_enqueue_style('tabler-icons.css', plugins_url('../admin/css/tabler-icons.css', __FILE__ ));
wp_enqueue_style('animate.css', plugins_url('../admin/css/animate.css', __FILE__ ));
wp_add_inline_style('overflow_auto.css', '#wpcontent{overflow: auto;}');
wp_enqueue_style('style.css', plugins_url('../admin/css/style.css',__FILE__));
?>

<!-- Welcome setting -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="card-content">
        <div class="card-body mb-2 pt-5 text-primary" style="text-align: left; font-size: 40px; ">
            <?php echo esc_html("Setting service for Worksuite");?> 
        </div>
        <div class="card-body" style="font-size: 20px;">
            <?php echo esc_html("Please choose service setting");?>  
        </div>
    </div>
</div>
<!--/ Welcome setting -->

<?php 
// show error/update messages
settings_errors( 'setting_clws_messages' );
?>

<!-- show font-end setting -->
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-5">
        <!-- Group setting menu -->
        <div class="card-header py-2">
            <ul class="nav nav-pills card-header-pills" role="tablist" style="font-weight:600">
                <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link nav-link-primary active"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-tab-sync-setting"
                        aria-controls="navs-pills-tab-sync-setting"
                        aria-selected="true"
                    >
                        <?php echo esc_html("Sync settings");?>   
                    </button>
                </li>
                <li class="nav-item">
                    <button
                        type="button"
                        class="nav-link nav-link-primary"
                        role="tab"
                        data-bs-toggle="tab"
                        data-bs-target="#navs-pills-tab-other-setting"
                        aria-controls="navs-pills-tab-other-setting"
                        aria-selected="false"
                    >
                    <?php echo esc_html("Other settings");?> 
                    </button>
                </li>
            </ul>
        </div>
        <!--/ Group setting menu -->

        <!-- Sync settings -->
        <div class="tab-content p-0">
            <div class="tab-pane fade show active" id="navs-pills-tab-sync-setting" role="tabpanel"> 
                    <!-- setting execution form -->
                    <form action="options.php" method="post" id="sync_setting_form">
                        <div class="setting-box">
                        <?php
                        settings_fields( 'setting_clws' );
                        // do_settings_sections( 'setting_clws_section_integrated_synchronized_developers' );
                        do_settings_fields( 
                            'setting_clws', 
                            'setting_clws_section_integrated_synchronized_developers' 
                        ); 
                        ?>
                        </div>
                        <?php submit_button( 'Save Settings' ); ?>
                    </form>
                    <!--/ setting execution form -->  
            </div>         
            <!--/ Sync settings -->	

            <!-- Other settings -->
            <div class="tab-content p-0">
                <div class="tab-pane fade show" id="navs-pills-tab-other-setting" role="tabpanel">
                    <label class="mt-4"><?php echo esc_html("Coming soon")?></label>
                </div>	
            </div>
            <!--/ Other settings -->
        </div>
    </div>
</div>

<!--/ show font-end setting -->
 <!-- Sync wp-user to clws-employees -->
 <?php function setting_clws_field_sync_employees_checkbox_cb($args) {?> 
    <div class="sync-employees">
        <div class="card-title-elements">
            <h5 class="m-0 me-2">
                <?php echo esc_html("Sync employees");?>  
                <label class="switch switch-primary switch-sm me-0">
                    <?php
                        $setting_clws_options = get_option( 'setting_clws_options' );
                        if (is_array($setting_clws_options) && $setting_clws_options == null) {
                            $setting_clws_options['setting_clws_field_sync_employees_checkbox'] = '1';
                        }
                    ?>
                    <input type="checkbox"
                        class="switch-input" 
                        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
                        name="setting_clws_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
                        value="1"
                        <?php 
                            isset( $setting_clws_options[ $args['label_for'] ] ) 
                            ? checked( '1', $setting_clws_options[ $args['label_for'] ] ) 
                            : (''); 
                        ?> 
                    />
                    <span class="switch-toggle-slider">
                        <span class="switch-on"></span>
                        <span class="switch-off"></span>
                    </span>
                </label> 
            </h5> 
        </div> 
        <div class="txt-setting"><?php echo esc_html("Synchronize users to WorkSuite. By default, synchronization is automatic. Please customize the feature on/off as desired.");?>  </div> 

        <?php function setting_clws_field_sync_employees_role_user_cb($arg_role_users) { 
            $setting_clws_options = get_option('setting_clws_options');
            $selected_role = 
                isset($setting_clws_options[$arg_role_users['label_for']]) 
                ? $setting_clws_options[$arg_role_users['label_for']] 
                : $arg_role_users['default_role'];
            ?>
            <div class="checked" style="padding-left: 50px; ">
                <h6 class="card-text">
                    <?php echo esc_html("Choose a user role");?>   
                </h6>
                <div class="card-title-elements ms-auto">
                    <select 
                        class="form-select form-select-sm w-auto"
                        id="<?php echo esc_attr( $arg_role_users['label_for'] ); ?>"
                        name="setting_clws_options[<?php echo esc_attr( $arg_role_users['label_for'] ); ?>]"
                    >
                        <?php wp_dropdown_roles( $selected_role ); ?>
                    </select>
                </div>
                <div class="row">
                    <p class="card-text mt-3 col-md-8">
                        <?php echo esc_html("Synchronize users to WorkSuite.");?>  
                    </p>
                    <div class="col-md-4"></div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } 
// Sync wp-user to clws-employees 

//  Sync wp-user to clws-clients 
function setting_clws_field_sync_clients_checkbox_cb($args) { ?>
    <div class="sync-clients">
        <h5 class="m-0 me-2">
            <?php echo esc_html("Sync clients");?> 
            <label class="switch switch-primary switch-sm me-0">
                <?php
                    $setting_clws_options = get_option( 'setting_clws_options' );
                    if (is_array($setting_clws_options) && $setting_clws_options == null) {
                        $setting_clws_options['setting_clws_field_sync_clients_checkbox'] = '1';
                    }
                ?>
                <input type="checkbox"
                    class="switch-input" 
                    id="<?php echo esc_attr( $args['label_for'] ); ?>" 
                    name="setting_clws_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
                    value="1" 
                    <?php 
                        isset( $setting_clws_options[ $args['label_for'] ] ) 
                        ? checked( '1', $setting_clws_options[ $args['label_for'] ] ) 
                        : (''); 
                    ?> 
                />
                <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                </span>
            </label>
        </h5>
        <div class="txt-setting">
            <?php echo esc_html("Synchronize customers who have placed orders in WooCommerce with Worksuite clients. By default, synchronization is automatic. Please customize the feature on/off as desired.")?>
        </div>   
    </div> 
<?php } ?> 