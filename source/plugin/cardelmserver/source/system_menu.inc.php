<?php

/**
*	卡益联盟服务端程序
*	文件名：system_menu.inc.php  创建时间：2013-7-10 23:19  杨文
*
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$this_page = 'plugins&identifier=cardelmserver&pmod=admincp&submod=system_menu';

$subac = getgpc('subac');
$subacs = array('menulist','menuedit');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$menuid = getgpc('menuid');
$menu_info = $menuid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_system_menu')." WHERE menuid=".$menuid) : array();

if($subac == 'menulist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','menu_list_tips'));
		showformheader($this_page.'&subac=menulist');
		showtableheader(lang('plugin/cardelmserver','menu_list'));
		showsubtitle(array('', lang('plugin/cardelmserver','menuname'),lang('plugin/cardelmserver','shopnum'), lang('plugin/cardelmserver','menuquanxian'), lang('plugin/cardelmserver','status'), ''));
		//$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_system_menu')." order by menuid asc");
		//while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"','class="td23"', 'class="td23"', 'class="td23"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[menuid]\">",
			$row['menuname'],
			$row['menuname'],
			$row['menuname'],
			"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['menuid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=menuedit&menuid=$row[menuid]\" class=\"act\">".lang('plugin/cardelmserver','edit')."</a>",
			));
		//}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=menuedit" class="addtr">'.lang('plugin/cardelmserver','add_menu').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subac == 'menuedit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','menu_edit_tips'));
		showformheader($this_page.'&subac=menuedit','enctype');
		showtableheader(lang('plugin/cardelmserver','menu_edit'));
		$menuid ? showhiddenfields(array('menuid'=>$menuid)) : '';
		showsetting(lang('plugin/cardelmserver','menuname'),'menu_info[menuname]',$menu_info['menuname'],'text','',0,lang('plugin/cardelmserver','menuname_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		if(!htmlspecialchars(trim($_GET['menu_info']['menuname']))) {
			cpmsg(lang('plugin/cardelmserver','menuname_nonull'));
		}
		$datas = $_GET['menu_info'];
		foreach ( $datas as $k=>$v) {
			$data[$k] = htmlspecialchars(trim($v));
			if(!DB::result_first("describe ".DB::table('cardelmserver_system_menu')." ".$k)) {
				$sql = "alter table ".DB::table('cardelmserver_system_menu')." add `".$k."` varchar(255) not Null;";
				runquery($sql);
			}
		}
		if($menuid) {
			DB::update('cardelmserver_system_menu',$data,array('menuid'=>$menuid));
		}else{
			DB::insert('cardelmserver_system_menu',$data);
		}
		cpmsg(lang('plugin/cardelmserver', 'menu_edit_succeed'), 'action='.$this_page.'&subac=menulist', 'succeed');
	}
}

?>