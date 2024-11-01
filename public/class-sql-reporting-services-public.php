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
 *
 * Defines the plugin name, version
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @link       http://modulemasters.com
 * @package    Sql_Reporting_Services
 * @subpackage Sql_Reporting_Services/public
 * @author     Module Masters <service@modulemasters.com>
 */
class Sql_Reporting_Services_Public
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        if ( isset( $_GET['doc'] ) ) {
            ob_start();
        }
        //if
    }
    
    //construct
    /**
     * Register the shortcodes
     *
     * @since    1.0.0
     */
    public function register_shortcodes()
    {
        add_shortcode( 'ssrs', array( $this, 'create_ssrs_shortcode' ) );
    }
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        /**
         * Setup the styles
         */
        wp_register_style(
            'sql_reporting_services_public_css',
            plugin_dir_url( __FILE__ ) . 'css/sql-reporting-services-public.css',
            array(),
            $this->version
        );
        wp_enqueue_style( 'sql_reporting_services_public_css' );
    }
    
    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        /**
         * Register the javascripts
         */
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/sql-reporting-services-public.js',
            array( 'jquery' ),
            $this->version,
            false
        );
    }
    
    /**
     * Summary.
     * Process the shortcode to return the result
     *
     * @since    1.0.0
     * @param array $atts Attributes passed into the shortcode
     * @param string $content The content passed within the shortcode tag, currently not used
     * @param string $tag The shortcode tag that was used
     * @return string The output HTML to render the report from the reporting service
     * This function returns a PDF, EXCEL or WORD document
     */
    public function create_ssrs_shortcode( $atts, $content = null, $tag )
    {
        try {
            //declare variable to be returned
            $result_html = '';
            $output = '';
            $configured = true;
            
            if ( extension_loaded( 'soap' ) ) {
                //get the options
                $UID = get_option( 'ssrs_report_server_username' );
                $PASWD = get_option( 'ssrs_report_server_password' );
                $SERVICE_URL = get_option( 'ssrs_report_server' );
                //make sure they are not empty before continuing
                
                if ( empty($UID) || empty($PASWD) || empty($SERVICE_URL) ) {
                    $configured = false;
                    $output .= '<br/><div class="ssrs_error"><h3>SSRS Short Code Error</h3>It looks like you have not configured the plugin, please visit the SQL Reporting Services configuration from the administration console from "Settings" > "SQL Reporting Services".  From there make sure you have entered your report server (e.g. http://server/reportserver) and an active directory username and password with access to the report server.  The SSRS server must be accessible from the web server as well.</div>';
                }
                
                //make sure we have the attributes within the shortcode
                $atts = array_change_key_case( (array) $atts, CASE_LOWER );
                $atts = shortcode_atts( array(
                    'width'      => '100%',
                    'height'     => '800px',
                    'renderas'   => 'HTML',
                    'reportpath' => '',
                    'parameters' => '',
                ), $atts, $tag );
                //make sure we have a report specified
                $REPORTPATH = esc_html( $atts['reportpath'] );
                //set the other rendering options
                $REPORTWIDTH = esc_html( $atts['width'] );
                $REPORTHEIGHT = esc_html( $atts['height'] );
                $REPORTRENDERAS = strtolower( esc_html( $atts['renderas'] ) );
                $REPORTPARAMETERS = $atts['parameters'];
                
                if ( empty($REPORTPATH) ) {
                    $configured = false;
                    $output .= '<br/><div class="ssrs_error"><h3>SSRS Short Code Error</h3>You have not supplied the attribute specifying the reportpath in the shortcode, please do this, e.g. [ssrs reportpath="/AdventureWorks/Employee_Sales_Summary"]</div>';
                }
                
                
                if ( ssrs()->is_not_paying() && !ssrs()->can_use_premium_code() && (!empty($REPORTPARAMETERS) || strtolower( $REPORTRENDERAS ) != "html") ) {
                    $configured = false;
                    $output .= '<br/><div class="ssrs_error"><h3>Please Upgrade</h3>Looks like you are trying to use some premium features specifying an attribute of "renderas" other than "HTML" ';
                    $output .= 'or attempting to pass the "parameters" attribute.  In order to use these features, please consider upgrading to our premium supported professional version, ';
                    $output .= '<a href="' . ssrs()->get_upgrade_url() . '">click here</a> for more details!  In the meantime, please set the renderas="HTML" and remove the parameters attribute if present.</div>';
                }
                
                
                if ( $configured ) {
                    define( "REPORT", $REPORTPATH );
                    $rs = new SSRSReport( new Credentials( $UID, $PASWD ), $SERVICE_URL );
                    $executionInfo = $rs->LoadReport2( REPORT, NULL );
                    $reportParameters = $rs->GetReportParameters(
                        REPORT,
                        null,
                        true,
                        null,
                        null
                    );
                    switch ( $REPORTRENDERAS ) {
                        case "pdf":
                        case "word":
                        case "excel":
                            //premium check
                            break;
                        case "html":
                            $renderAsHTML = new RenderAsHTML();
                            $renderAsHTML->ReplacementRoot = $this->getPageURL();
                            $renderAsHTML->StreamRoot = plugin_dir_url( __FILE__ ) . 'tmp/';
                            $result_html = $rs->Render2(
                                $renderAsHTML,
                                PageCountModeEnum::$Actual,
                                $Extension,
                                $MimeType,
                                $Encoding,
                                $Warnings,
                                $StreamIds
                            );
                            //render any images in the report so we can display them properly
                            foreach ( $StreamIds as $StreamId ) {
                                $renderAsHTML->StreamRoot = null;
                                $result_png = $rs->RenderStream(
                                    $renderAsHTML,
                                    $StreamId,
                                    $Encoding,
                                    $MimeType
                                );
                                
                                if ( !($handle = fopen( plugin_dir_path( __FILE__ ) . 'tmp/' . $StreamId, 'wb' )) ) {
                                    $output .= '<br/><div class="ssrs_error"><h3>SSRS Rendering Error</h3>Cannot open file for writing output</div>';
                                    exit;
                                }
                                
                                
                                if ( fwrite( $handle, $result_png ) === FALSE ) {
                                    $output .= '<br/><div class="ssrs_error"><h3>SSRS Rendering Error</h3>Cannot write to file</div>';
                                    exit;
                                }
                                
                                fclose( $handle );
                            }
                            //foreach
                            //build the HTML
                            $html = '<html><body><br/><br/>';
                            $html .= '<div align="center">';
                            $html .= '<div >';
                            $html .= $result_html;
                            $html .= '</div>';
                            $html .= '</div>';
                            $html .= '</body></html>';
                            $output .= '<iframe border="0" frameborder="0" width="' . $REPORTWIDTH . '" height="' . $REPORTHEIGHT . '" scrolling="auto" srcdoc="' . str_replace( '"', "'", $html ) . '"></iframe>';
                            break;
                        default:
                            $output .= '<br/><div class="ssrs_error"><h3>SSRS Short Code Error</h3>The "renderas" attribute for your shortcode is not set properly, e.g. [ssrs reportpath="/AdventureWorks 2008R2/Employee_Sales_Summary_2008R2" height="1200px" renderas="HTML"].  <br/><br/>Valid values for the renderas attribute are HTML, PDF, WORD and EXCEL.  Please note that "HTML" is the only valid option for the free version.</div>';
                    }
                    //switch
                }
                
                //if configured
            } else {
                $output .= '<br/><div class="ssrs_error"><h3>SOAP Error</h3>This plugin requires that you have the SOAP extension installed on your PHP server which appears to be missing.  You usually can enable this on the server by editing the php.ini file and making sure the extension=php_soap.dll does not have a semicolon in front of it, but you should consult your web server documentation to enable this.  Please do not hesitate to contact us at service@modulemasters.com if you have any issues enabling this.</div>';
            }
            
            //else check for soap
            return $output;
        } catch ( SSRSReportException $serviceExcprion ) {
            return '<br/><div class="ssrs_error"><h3>SSRS Short Code Error</h3>' . $serviceExcprion->GetErrorMessage() . '</div>';
        }
        //catch
    }
    
    /**
     * This is used to get the URL of the wordpress site and plugin page
     * 
     * @since    1.0.0
     * @return <url>
     * This function returns the url of current page.
     */
    function getPageURL()
    {
        
        if ( isset( $_SERVER["HTTPS"] ) ) {
            $PageUrl = ( $_SERVER["HTTPS"] == "on" ? 'https://' : 'http://' );
        } else {
            $PageUrl = 'http://';
        }
        
        $uri = $_SERVER["REQUEST_URI"];
        $index = strpos( $uri, '?' );
        if ( $index !== false ) {
            $uri = substr( $uri, 0, $index );
        }
        $PageUrl .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $uri;
        return $PageUrl;
    }

}
//class