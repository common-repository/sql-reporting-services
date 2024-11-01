<?php

/**
 * SQL Reporting Services for WordPress
 *
 * Plugin Name:       SQL Reporting Services - SSRS Plugin for WordPress
 * Plugin URI:        http://modulemasters.com/SQL-Reporting-Services-for-WordPress
 * Description:       This plugin allows you to embed Microsoft SQL Reporting Services (SSRS) reports right into your WordPress site.
 * Version:           1.0.3
 * Author:            Module Masters
 * Author URI:        http://modulemasters.com
 * License:           GPL2
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sql-reporting-services
 * Domain Path:       /languages
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO,
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS;
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE,
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 * 
 * @link              http://modulemasters.com
 * @since             1.0.0
 * @package           Sql_Reporting_Services
 * @copyright         Copyright (c) 2018, Module Masters
 * @author            Module Masters <service@modulemasters.com>
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'ssrs' ) ) {
    ssrs()->set_basename( false, __FILE__ );
    return;
}


if ( !function_exists( 'ssrs' ) ) {
    // Create a helper function for easy SDK access.
    function ssrs()
    {
        global  $ssrs ;
        
        if ( !isset( $ssrs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/freemius/start.php';
            $ssrs = fs_dynamic_init( array(
                'id'             => '2947',
                'slug'           => 'sql-reporting-services',
                'type'           => 'plugin',
                'public_key'     => 'pk_d23c5a30e4779c20cb0353a601d1f',
                'is_premium'     => false,
                'premium_suffix' => '(Professional)',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'menu'           => array(
                'slug'   => 'sqlreportingservices',
                'parent' => array(
                'slug' => 'options-general.php',
            ),
            ),
                'is_live'        => true,
            ) );
        }
        
        return $ssrs;
    }
    
    // Init Freemius.
    ssrs();
    // Signal that SDK was initiated.
    do_action( 'ssrs_loaded' );
}

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Current plugin version.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.3' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sql-reporting-services-activator.php
 */
function activate_sql_reporting_services()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sql-reporting-services-activator.php';
    Sql_Reporting_Services_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sql-reporting-services-deactivator.php
 */
function deactivate_sql_reporting_services()
{
    require_once plugin_dir_path( __FILE__ ) . 'includes/class-sql-reporting-services-deactivator.php';
    Sql_Reporting_Services_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sql_reporting_services' );
register_deactivation_hook( __FILE__, 'deactivate_sql_reporting_services' );
/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-sql-reporting-services.php';
/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_sql_reporting_services()
{
    $plugin = new Sql_Reporting_Services();
    $plugin->run();
}

run_sql_reporting_services();