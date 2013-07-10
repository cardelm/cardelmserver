<?php

/**
*	卡益联盟服务端程序
*	文件名：system_setting.inc.php  创建时间：2013-7-10 23:21  杨文
*
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$this_page = 'plugins&identifier=cardelmserver&pmod=admincp&submod=system_setting';

$subac = getgpc('subac');
$subacs = array('settingedit');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$settingid = getgpc('settingid');
$setting_info = $settingid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_setting')." WHERE settingid=".$settingid) : array();

if(!submitcheck('submit')) {
	showtips(lang('plugin/cardelmserver','setting_edit_tips'));
	showformheader($this_page.'&subac=settingedit','enctype');
	showtableheader(lang('plugin/cardelmserver','setting_edit'));
	$settingid ? showhiddenfields(array('settingid'=>$settingid)) : '';
	showsetting(lang('plugin/cardelmserver','settingname'),'setting_info[settingname]',$setting_info['settingname'],'text','',0,lang('plugin/cardelmserver','settingname_comment'),'','',true);
	showsubmit('submit');
	showtablefooter();
	showformfooter();
}else{
	if(!htmlspecialchars(trim($_GET['setting_info']['settingname']))) {
		cpmsg(lang('plugin/cardelmserver','settingname_nonull'));
	}
	$datas = $_GET['setting_info'];
	foreach ( $datas as $k=>$v) {
		$data[$k] = htmlspecialchars(trim($v));
		if(!DB::result_first("describe ".DB::table('cardelmserver_setting')." ".$k)) {
			$sql = "alter table ".DB::table('cardelmserver_setting')." add `".$k."` varchar(255) not Null;";
			runquery($sql);
		}
	}
	if($settingid) {
		DB::update('cardelmserver_setting',$data,array('settingid'=>$settingid));
	}else{
		DB::insert('cardelmserver_setting',$data);
	}
	cpmsg(lang('plugin/cardelmserver', 'setting_edit_succeed'), 'action='.$this_page, 'succeed');
}

?>