<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
=====================================================================
Plugin Rewrite Links
---------------------------------------------------------------------
Copyright: Lee Hilton
License: Creative Commons Attribution Non-Commercial Share Alike 3.0
License URL: http://creativecommons.org/licenses/by-nc-sa/3.0/
http://leezilla.net/
---------------------------------------------------------------------
This addon is free. If you like it, I would enjoy any
suggestions on how to improve this product.
=====================================================================
File: pi.rewrite_links.php
---------------------------------------------------------------------
Purpose:
	Attempts to rewrite HTML links to set 
	target="_blank" for any link that is outside the
	configured site_url value.
=====================================================================
THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF
ANY KIND. PERIOD. USAGE OF THIS SOFTWARE IS OPTIONAL
AND USAGE IS ENTIRE AT YOUR OWN RISK.
=====================================================================
*/


$plugin_info = array(  'pi_name' => 'Rewrite Links',
	'pi_version' => '1.0.0',
	'pi_author' => 'Lee Hilton',
	'pi_author_url' => 'http://leezilla.net/',
	'pi_description' => 'Rewrites links in your HTML fields to set target="_blank" for links pointing to external domains.',
	'pi_usage' => rewrite_links::usage());

class Rewrite_Links
{
	var $return_data;

	function Rewrite_Links()
	{
		$this->EE =& get_instance();

		// Localize and initialize our sites configured local URL
		$base_site_url = strtolower($this->EE->config->item('site_url'));
		if (substr($base_site_url, strlen($base_site_url) - 1) == '/') {
			$base_site_url = substr($base_site_url, 0, strlen($base_site_url) - 1);
		}
		
		// Load our DOM object with the tag data from the template
		$html = new DOMDocument();
		$html->loadHtml($this->EE->TMPL->tagdata);
		
		// Get to the links from the document
		$xpath = new DOMXPath($html);
		$links = $xpath->query( '//a' );
		
		// The logic is thus: For every link, check to see if the link is fully qualified (http://), and
		// if it IS fully qualified make sure it is not the configured site_url for the EE instance.
		foreach ($links as $link) {
			if(strtolower(substr($link->getAttribute('href'), 0, 7)) == 'http://' && strtolower(substr($link->getAttribute('href'), 0, strlen($base_site_url))) != $base_site_url) {
				$link->setAttribute('target', '_blank');
			}
		}
		
		$this->return_data = $html->saveHTML();
	}


	// ----------------------------------------
	// Plugin Usage
	// ----------------------------------------
	// This function describes how the plugin is used.
	// Make sure and use output buffering
	function usage()
	{
		ob_start();
		?>
Example:
----------------
{exp:rewrite_links}
{some_html_field}
{/exp:rewrite_links}

Result:
----------------
<a href="http://somexternaldomain.com/somepath">Some linked text</a>

Becomes

<a href="http://somexternaldomain.com/somepath" target="_blank">Some linked text</a>
----------------
CHANGELOG:

1.0
* 1st version
		<?php
		$buffer = ob_get_contents();
		ob_end_clean();
		return $buffer;
	}
	  /* END */

}
/* END Class */
/* End of file pi.rewrite_links.php */
/* Location: ./system/expressionengine/third_party/rewrite_links/pi.rewrite_links.php */
