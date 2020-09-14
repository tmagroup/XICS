<?php
class LanguageLoader
{
	function initialize() {
		$ci =& get_instance();
		$ci->load->helper('language');
		$siteLang = $ci->session->userdata('site_lang');

		if ($siteLang) {
			$ci->lang->load('common',$siteLang);

		} else {
			$siteLang = get_option('active_language');
			if ($_SERVER['REMOTE_ADDR'] == '123.201.19.165') {
				// $siteLang = 'english';
			}
			$ci->lang->load('common',$siteLang);
		}
	}
}
?>