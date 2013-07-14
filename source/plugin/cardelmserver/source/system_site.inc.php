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
		showsubtitle(array('', lang('plugin/cardelmserver','siteurl'),lang('plugin/cardelmserver','site_time'), lang('plugin/cardelmserver','sitemokuai'), lang('plugin/cardelmserver','status'), ''));
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_site')." order by siteid asc");
		while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"','class="td28" valign="top"', 'class="td26"', 'class="td23"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[siteid]\">",
			$row['siteurl'].'<br />'.$row['charset'].'&nbsp;&nbsp;'.$row['clientip'].'<br />'.$row['version'],
			lang('plugin/cardelmserver','installtime').':'.dgmdate($row['installtime'],d).'<br />'.lang('plugin/cardelmserver','updatetime').':'.dgmdate($row['updatetime'],d).'<br />'.lang('plugin/cardelmserver','uninstalltime').':'.dgmdate($row['uninstalltime'],d).'<br />'.lang('plugin/cardelmserver','groupexpiry').':'.dgmdate($row['groupexpiry'],d).'<br />',
			$row['sitename'],
			"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['siteid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=siteedit&siteid=$row[siteid]\" class=\"act\">".lang('plugin/cardelmserver','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=siteedit" class="addtr">'.lang('plugin/cardelmserver','add_site').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subac == 'siteedit') {
	if(!submitcheck('submit')) {
		$sitegroup_select = '<select name="site_info[sitegroup]"><option value="">'.$lang['select'].'</option>';
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_sitegroup')." WHERE status =1 order by sitegroupid asc");
		while($row = DB::fetch($query)) {
			$sitegroup_select .= '<option value="'.$row['sitegroupid'].'" '.($site_info['sitegroup'] == $row['sitegroupid'] ? ' selected': '').'>'.$row['sitegroupname'].'</option>';
		}
		$sitegroup_select .= '</select>';
		showtips(lang('plugin/cardelmserver','site_edit_tips'));
		showformheader($this_page.'&subac=siteedit','enctype');
		showtableheader(lang('plugin/cardelmserver','site_edit'));
		$siteid ? showhiddenfields(array('siteid'=>$siteid)) : '';
		echo '<script src="static/js/calendar.js" type="text/javascript"></script>';
		showsetting(lang('plugin/cardelmserver','siteurl'),'site_info[siteurl]',$site_info['siteurl'],'text','',0,lang('plugin/cardelmserver','siteurl_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','charset'),'site_info[charset]',$site_info['charset'],'text','',0,lang('plugin/cardelmserver','charset_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','clientip'),'site_info[clientip]',$site_info['clientip'],'text','',0,lang('plugin/cardelmserver','clientip_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','version'),'site_info[version]',$site_info['version'],'text','',0,lang('plugin/cardelmserver','version_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','installtime'),'site_info[installtime]',dgmdate($site_info['installtime'],'d') ,'calendar','',0,lang('plugin/cardelmserver','installtime_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','updatetime'),'site_info[updatetime]',dgmdate($site_info['updatetime'],'d'),'calendar','',0,lang('plugin/cardelmserver','updatetime_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','uninstalltime'),'site_info[uninstalltime]',dgmdate($site_info['uninstalltime'],'d'),'calendar','',0,lang('plugin/cardelmserver','uninstalltime_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','sitegroup'),'','',$sitegroup_select,'',0,lang('plugin/cardelmserver','site_sitegroup_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','groupexpiry'),'site_info[groupexpiry]',dgmdate($site_info['groupexpiry'],'d'),'calendar','',0,lang('plugin/cardelmserver','groupexpiry_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		if(!htmlspecialchars(trim($_GET['site_info']['siteurl']))) {
			cpmsg(lang('plugin/cardelmserver','sitename_nonull'));
		}
		$datas = $_GET['site_info'];
		foreach ( $datas as $k=>$v) {
			if(in_array($k,array('installtime','updatetime','uninstalltime','groupexpiry'))){
				$data[$k] = strtotime(trim(htmlspecialchars(trim($v))));
			}else{
				$data[$k] = htmlspecialchars(trim($v));
			}
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