<?php

/**
  *
  * Copyright (c) 2018, Module Masters
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
  */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       http://modulemasters.com
 * @package    Sql_Reporting_Services
 * @subpackage Sql_Reporting_Services/admin
 * @author     Module Masters <service@modulemasters.com>
 */
class Sql_Reporting_Services_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sql-reporting-services-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/sql-reporting-services-admin.js', array( 'jquery' ), $this->version, false );
	}

	/**
	 * Setup the settings page settings
	 *
	 * @since    1.0.0
	 */
	public function sqlreportingservices_create_settings() { 
		$page_title = 'SQL Reporting Services Settings';
		$menu_title = 'SQL Reporting Services';
		$capability = 'manage_options';
		$slug = 'sqlreportingservices';
		$callback = array($this, 'sqlreportingservices_settings_content');
		add_options_page($page_title, $menu_title, $capability, $slug, $callback);
	}

	/**
	 * Setup the settings page content
	 *
	 * @since    1.0.0
	 */
	public function sqlreportingservices_settings_content() { ?>
		<div class="wrap">
			<h1>SQL Reporting Services Settings</h1>
			<form method="POST" action="options.php">
				<?php
					if ($this->soap_check()) {
						settings_fields( 'sqlreportingservices' );
						do_settings_sections( 'sqlreportingservices' );
						#submit_button('Test Connection','secondary'); //todo: enable test functionality
						echo '<br/><div>For usage instructions, please see the <a href="http://modulemasters.com/Portals/0/docs/ssrs/wordpress/About.html" target="_blank">plugin documentation</a>.</div>';
						submit_button();
					}
				?>
			</form>
		</div> 
		<?php 
	} 


	/**
	 * Register the settings page
	 *
	 * @since    1.0.0
	 */
	public function sqlreportingservices_setup_sections() {
		add_settings_section( 'sqlreportingservices_section', 'Configure the SQL Reporting Services plugin settings.', array(), 'sqlreportingservices' );
	}

	/**
	 * Setup the settings page fields
	 *
	 * @since    1.0.0
	 */	
	public function sqlreportingservices_setup_fields() {
		$fields = array(
			array(
				'label' => 'Report Server URL',
				'id' => 'ssrs_report_server',
				'type' => 'text',
				'section' => 'sqlreportingservices_section',
				'placeholder' => '',
				'desc' => 'This is the report server URL (e.g. http://server/reportserver)',
				'option' => 'size="60"',
			),
			array(
				'label' => 'Report Server Username',
				'id' => 'ssrs_report_server_username',
				'type' => 'text',
				'section' => 'sqlreportingservices_section',
				'placeholder' => '',
				'desc' => 'This is the active directory account used to authenticate to the report server.',
				'option' => '',
			),
			array(
				'label' => 'Report Server Password',
				'id' => 'ssrs_report_server_password',
				'type' => 'password',
				'section' => 'sqlreportingservices_section',
				'placeholder' => '',
				'desc' => 'This is the password for the report server user used to authenticate to the report server.',
				'option' => '',
			),
		);
		foreach( $fields as $field ){
			add_settings_field( $field['id'], $field['label'], array( $this, 'sqlreportingservices_field_callback' ), 'sqlreportingservices', $field['section'], $field );
			register_setting( 'sqlreportingservices', $field['id'] );
		}
	}

	/**
	 * Load the settings
	 *
	 * @since    1.0.0
	 */
	public function sqlreportingservices_field_callback( $field ) {
		$value = get_option( $field['id'] );
		switch ( $field['type'] ) {
			default:
				printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" %5$s />',
					$field['id'],
					$field['type'],
					$field['placeholder'],
					$value,
					$field['option']
				);
		}
		if( $desc = $field['desc'] ) {
			printf( '<p class="description">%s </p>', $desc );
		}
	}


	/**
	 * Check that the soap extension is installed
	 * 
	 * @since	1.0.0
	 */
	public function soap_check() {
		$soap_present = true;
		if ( ! extension_loaded('soap')) {
			echo '<br/><div class="ssrs_error"><h3>SOAP Extension Missing</h3>This plugin requires that you have the SOAP extension installed on your PHP server which appears to be missing.  You usually can enable this on the server by editing the php.ini file and making sure the extension=php_soap.dll does not have a semicolon in front of it, for more information on how to enable this extension, please see the <a href="http://modulemasters.com/portals/0/docs/ssrs/wordpress/Installation.html" target="_blank">plugin documentation</a>.</div>';
			$soap_present = false;
		}
		return $soap_present;
	}


	/**
	 * Uninstall cleanup
	 * 
	 * @since 1.0.0
	 */
	public function sqlreportingservices_uninstall_cleanup() {
		// If uninstall not called from WordPress, then exit.
		if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
			exit;
		}

		delete_option("ssrs_report_server");
		delete_option("ssrs_report_server_username");
		delete_option("ssrs_report_server_password");
	}
}
