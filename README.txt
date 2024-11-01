=== SQL Reporting Services - SSRS Plugin for WordPress ===
Contributors: modulemasters, freemius
Donate link: http://modulemasters.com
Tags: SSRS, SQL Reporting Services, SQL Server Reporting Services, Microsoft Reporting Services, Microsoft, Microsoft SQL Server Reporting Services, Reporting, SQL Server Reporting Services, RDL, Report Definition Language, Report Service, Report, Reports
Requires at least: 3.7.1
Tested up to: 5.7
Requires PHP: 5.3
Stable tag: 1.0.3
License: GPL2
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

A FREE WordPress plugin that allows integrating and displaying reports from SSRS (Microsoft SQL Server Reporting Services).

== Description ==
Finally, an SSRS Module for WordPress!  

If you have ever wanted to be able to display SQL Server Reporting Services reports within your WordPress site, look no further.  This plugin seamlessly bridges the gap between your SSRS reporting server and your WordPress site.  

Your SSRS RDL reports will be able to be rendered from your report server directly embedded within your site via use of a simple shortcode.

The free version allows you to display a report that does not have parameters or has parameter defaults set and will render to HTML output.  We offer a paid version which offers a number of other features such as more options for parameter interaction, rendering options, priority support and more!  To upgrade, after installation simply browse to the "Settings" -> "SQL Reporting Services" -> "Upgrade" page for more information.

== Installation ==
Please be sure to review the plugin requirements are met before installing the plugin.

= Requirements =
1. This plugins provides access to reporting services so you must have a Microsoft SQL Server Reporting Services server running in order to connect the plugin to it, versions 2008 - 2017 are currently supported
2. The SOAP extension must be installed and enabled within PHP, if it is not already, see below for more information
3. Your Microsoft SQL Server Reporting Services server must support basic authentication in the rsreportserver.config file, [see this article](https://docs.microsoft.com/en-us/sql/reporting-services/security/configure-basic-authentication-on-the-report-server?view=sql-server-2017) for more information how to enable this if it is not already
4. Minimum version of WordPress 3.7.1
5. Minimum version of PHP 5.3

= AUTOMATED Installation =
From within WordPress dashboard:
1. Go to "Plugins" -> "Add New"
2. Search for "SQL Reporting Services" by Module Masters
3. Click "Install"
4. Click "Activate"
5. Opt-In, this is optional but recommended as it verifies your installation
6. Go to "Settings"  -> "SQL Reporting Services", register your report server (e.g. http://server/reportserver) and enter your active directory credentials to access the server
7. From a blog entry or page, add the ssrs shortcode (e.g. `[ssrs reportpath="/TestReports/ParmTest" height="1200px" width="100%"]`)

= MANUAL Installation =
1. Instead of the quick installation above, you can download and unzip the plugin to your computer
2. Upload the sql-reporting-services folder to the /wp-content/plugins directory of your WordPress site
3. Activate the plugin through the "Plugins" page
4. Opt-In, this is optional but recommended as it verifies your installation
5. Go to "Settings"  -> "SQL Reporting Services", register your report server (e.g. http://server/reportserver) and enter your active directory credentials to access the server
6. From a blog entry or page within your WordPress site, add the ssrs shortcode (e.g. `[ssrs reportpath="/TestReports/ParmTest" height="1200px" width="100%"]`)

= PHP SOAP Extension =
This plugin makes calls to the SSRS server using the reporting services API which leverages SOAP web service calls.  In order for these calls to be able to be made from the PHP engine which powers WordPress, this extension must be installed.  The plugin will throw an error message if it detects that this is missing so if you aren't sure, simply do the installation and you'll see a notification if you need to enable this. If so, don't fret, it is easy!

**For Windows**
1. Find extension=php_soap.dll in php.ini and remove the semicolon(;)
2. Restart your Server

**For Linux (Ubuntu)**
*PHP7.x*
sudo apt-get install php7.0-soap 
sudo systemctl restart apache2

*For nginx*
sudo apt-get install php7.0-soap
sudo systemctl restart nginx

*For PHP5*
apt-get install php-soap

= Need More Help?!?! =
We stand behind our work and are happy to help you.  We offer premium support offerings from our paid version along with more features, but regardless, just let us know from the WordPress "Settings" menu, browse to the "SQL Reporting Services" -> "Contact Us" page or "Support Forum" page if you have questions, feedback or new feature requests!

Please see the documentation available from our [website](http://modulemasters.com/Portals/0/docs/ssrs/wordpress/about.html) for more information!

== Frequently Asked Questions ==

= What versions of SQL Server Reporting Services can be accessed by this plugin? = 
The plugin currently supports and has been tested with Microsoft SQL Server Reporting Services 2008 through 2017.

= I see a SOAP error, what is this? =
This plugin makes calls to the reporting services server via the SOAP interface running on the report server.  This module requires the SOAP extension be installed and configured within your PHP server to make these calls.  See the installation section for more information how to enable this extension if it is not already!

= I see an error Failed to connect to Reporting Service? =
There are several situations where this may occur:
1. You have an incorrect report server URL entered, it must be accessible from the web server (e.g. not behind any firewall that prohibits communication between the report and web server hosting the WP site)
2. You have an incorrect username entered or the user you supplied does not have permissions on the report server
3. You have an incorrect password entered
4. You do not have basic authentication enabled within your report server, please see the article referenced in the installation instructions for more information

= Are there any SSRS features that do not work within the plugin? =
While we are continually refining the plugins capabilities there are a few items that do not work in the current version. For a complete list of these, please see the detailed [documentation](http://modulemasters.com/Portals/0/docs/ssrs/wordpress/about.html) from our site.  Here's a few of them:
- Drilling, Sorting and Hyperlinks within reports
- Images within reports will display in their native size (e.g. auto-size), so plan accordingly
- Credentials need to be stored within data sources, prompting or pass through credentials are not supported

= Why would I upgrade? =
Upgrading comes with priority support and a direct channel to the developers.  We also offer additional features for parameter control, additional rendering support such as PDF and Excel and many other great features we are currently developing.  See the Paid Version section for more information!

= Do you offer refunds? =
Yes, you can get a full refund within 14 days of purchase, but please contact us prior so we can make every effort to retain your business and fix any issues you may be having!

= Who are the Module Masters? = 
Started in 2006, the Module Masters were founded by Ben Becker, an tech evangelist and coder.  Since then he has brought in several other consultants who have contributed to various coding projects related to content management systems under the Module Masters brand.  The SQL Reporting Services module for the DotNetNuke platform was released in 2006 and has been a huge hit on this platform so it was decided to release a version of it as a WordPress plugin.

= I need help with SQL Reporting Services =
Please consider upgrading so you can get priority support.  You can also find out more information in our [product documentation from our site.](http://modulemasters.com/SQL-Reporting-Services-for-WordPress)  We also are experts with SQL Reporting Services and are happy to offer consulting services to assist with any special reporting and configuration needs you may have, so please don't hesitate to reach out to us at service@modulemasters.com if you have any other inquiries.

== Screenshots ==

1. An example of an SSRS report rendering within a WordPress page.
2. The settings page where the report server is setup. 
3. The syntax used within the shortcode that can be used on any WordPress page for ultimate flexibility!  Note: the paid version supports additional functionality not shown!
4. The options pages visible from the "Settings" menu within the WordPress administration pages.

== Changelog ==

= 1.0.3 =
* Released 30-Nov-2020
* Upgraded SDK, tested new version WP 5.5.3

= 1.0.2 =
* Released 11-Jul-2019
* Upgraded SDK, tested new version WP 5.2.2

= 1.0.1 =
* Released 03-Mar-2019
* Security fix

= 1.0.0 =
* Released 27-Dec-2018
* Initial Release

== Upgrade Notice ==
= 1.0.3 =
* Security fix

= 1.0.1 =
* Security fix

= 1.0.0 =
* Initial release of the SSRS plugin!

== Paid Version ==

The paid version of the module opens up many exciting features and is well worth considering if SSRS is an integral part of your data availability strategy. The following benefits are exposed in the paid version:

* Export reports to PDF
* Export reports to Microsoft WordPress
* Export reports to Microsoft Excel
* Set parameters via the shortcode
* Set parameters via the querystring
* Pass report to render via the querystring
* Priority support access

We offer monthly, yearly, lifetime and multi-site options, please checkout our site [ModuleMasters.com](http://modulemasters.com) for more information or visit the "Upgrade" page from the "Settings" -> "SQL Reporting Services" section within the Admin section of the portal!

We have a large road map of features and releases we are porting over to our WordPress version of the plugin and will be releasing frequent updates so stay tuned and don't hesitate to drop us a line if you have any feature requests!

Sincerely, 
The Module Masters!