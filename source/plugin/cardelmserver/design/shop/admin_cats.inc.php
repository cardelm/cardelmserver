<?php

/**
*	�������˳���
*	�ļ�����admin_cats.inc.php  ����ʱ�䣺2013-7-16 16:43  ����
*
*/
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$this_page =  this_page(shop,cats);

$subac = getgpc('subac');
$subacs = array('catslist','catsedit');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$catsid = getgpc('catsid');
$cats_info = $catsid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_cats')." WHERE catsid=".$catsid) : array();

if($subac == 'catslist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelm','cats_list_tips'));
		showformheader($this_page.'&subac=catslist');
		showtableheader(lang('plugin/cardelm','cats_list'));
		
	}else{
	}
}elseif($subac == 'catsedit') {
	if(!submitcheck('submit')) {
	}else{
	}
}
?>