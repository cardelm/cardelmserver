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
$subacs = array('settinglist','settingedit');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$settingid = getgpc('settingid');
$setting_info = $settingid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_system_setting')." WHERE settingid=".$settingid) : array();

if($subac == 'settinglist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','setting_list_tips'));
		showformheader($this_page.'&subac=settinglist');
		showtableheader(lang('plugin/cardelmserver','setting_list'));
		showsubtitle(array('', lang('plugin/cardelmserver','settingname'),lang('plugin/cardelmserver','shopnum'), lang('plugin/cardelmserver','settingquanxian'), lang('plugin/cardelmserver','status'), ''));
		//$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_system_setting')." order by settingid asc");
		//while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"','class="td23"', 'class="td23"', 'class="td23"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[settingid]\">",
			$row['settingname'],
			$row['settingname'],
			$row['settingname'],
			"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['settingid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=settingedit&settingid=$row[settingid]\" class=\"act\">".lang('plugin/cardelmserver','edit')."</a>",
			));
		//}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=settingedit" class="addtr">'.lang('plugin/cardelmserver','add_setting').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subac == 'settingedit') {
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
			if(!DB::result_first("describe ".DB::table('cardelmserver_system_setting')." ".$k)) {
				$sql = "alter table ".DB::table('cardelmserver_system_setting')." add `".$k."` varchar(255) not Null;";
				runquery($sql);
			}
		}
		if($settingid) {
			DB::update('cardelmserver_system_setting',$data,array('settingid'=>$settingid));
		}else{
			DB::insert('cardelmserver_system_setting',$data);
		}
		cpmsg(lang('plugin/cardelmserver', 'setting_edit_succeed'), 'action='.$this_page.'&subac=settinglist', 'succeed');
	}
}

?>