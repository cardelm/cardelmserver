<?php

/**
*	卡益联盟服务端程序
*	文件名：system_site.inc.php  创建时间：2013-7-11 03:16  杨文
*
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$this_page = 'plugins&identifier=cardelmserver&pmod=admincp&submod=system_site';

$subac = getgpc('subac');
$subacs = array('sitelist','siteedit');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$siteid = getgpc('siteid');
$site_info = $siteid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_site')." WHERE siteid=".$siteid) : array();

if($subac == 'sitelist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','site_list_tips'));
		showformheader($this_page.'&subac=sitelist');
		showtableheader(lang('plugin/cardelmserver','site_list'));
		showsubtitle(array('', lang('plugin/cardelmserver','siteurl'),lang('plugin/cardelmserver','shopnum'), lang('plugin/cardelmserver','sitequanxian'), lang('plugin/cardelmserver','status'), ''));
		//$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_site')." order by siteid asc");
		//while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"','class="td23"', 'class="td23"', 'class="td23"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[siteid]\">",
			$row['siteurl'],
			$row['sitename'],
			$row['sitename'],
			"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['siteid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=siteedit&siteid=$row[siteid]\" class=\"act\">".lang('plugin/cardelmserver','edit')."</a>",
			));
		//}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=siteedit" class="addtr">'.lang('plugin/cardelmserver','add_site').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subac == 'siteedit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','site_edit_tips'));
		showformheader($this_page.'&subac=siteedit','enctype');
		showtableheader(lang('plugin/cardelmserver','site_edit'));
		$siteid ? showhiddenfields(array('siteid'=>$siteid)) : '';
		showsetting(lang('plugin/cardelmserver','siteurl'),'site_info[sitename]',$site_info['sitename'],'text','',0,lang('plugin/cardelmserver','siteurl_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		if(!htmlspecialchars(trim($_GET['site_info']['sitename']))) {
			cpmsg(lang('plugin/cardelmserver','sitename_nonull'));
		}
		$datas = $_GET['site_info'];
		foreach ( $datas as $k=>$v) {
			$data[$k] = htmlspecialchars(trim($v));
			if(!DB::result_first("describe ".DB::table('cardelmserver_site')." ".$k)) {
				$sql = "alter table ".DB::table('cardelmserver_site')." add `".$k."` varchar(255) not Null;";
				runquery($sql);
			}
		}
		if($siteid) {
			DB::update('cardelmserver_site',$data,array('siteid'=>$siteid));
		}else{
			DB::insert('cardelmserver_site',$data);
		}
		cpmsg(lang('plugin/cardelmserver', 'site_edit_succeed'), 'action='.$this_page.'&subac=sitelist', 'succeed');
	}
}

?>