<?php

/**
*	卡益联盟服务端程序
*	文件名：system_mokuai.inc.php  创建时间：2013-7-10 23:23  杨文
*
*/

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$this_page = 'plugins&identifier=cardelmserver&pmod=admincp&submod=system_mokuai';

$subac = getgpc('subac');
$subacs = array('mokuailist','mokuaiedit');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$mokuaiid = getgpc('mokuaiid');
$mokuai_info = $mokuaiid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$mokuaiid) : array();

if($subac == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','mokuai_list_tips'));
		showformheader($this_page.'&subac=mokuailist');
		showtableheader(lang('plugin/cardelmserver','mokuai_list'));
		showsubtitle(array('', lang('plugin/cardelmserver','mokuainame'),lang('plugin/cardelmserver','mokuaititle'), lang('plugin/cardelmserver','mokuaiquanxian'), lang('plugin/cardelmserver','status'), ''));
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_mokuai')." order by mokuaiid asc");
		while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"','class="td23"', 'class="td23"', 'class="td23"','class="td25"',''), array(
				"<input class=\"checkbox\" type=\"checkbox\" name=\"delete[]\" value=\"$row[mokuaiid]\">",
			$row['mokuainame'],
			$row['mokuaititle'],
			$row['mokuainame'],
			"<input class=\"checkbox\" type=\"checkbox\" name=\"statusnew[".$row['mokuaiid']."]\" value=\"1\" ".($row['status'] > 0 ? 'checked' : '').">",
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=mokuaiedit&mokuaiid=$row[mokuaiid]\" class=\"act\">".lang('plugin/cardelmserver','edit')."</a>",
			));
		}
		echo '<tr><td></td><td colspan="6"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=mokuaiedit" class="addtr">'.lang('plugin/cardelmserver','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subac == 'mokuaiedit') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','mokuai_edit_tips'));
		showformheader($this_page.'&subac=mokuaiedit','enctype');
		showtableheader(lang('plugin/cardelmserver','mokuai_edit'));
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		showsetting(lang('plugin/cardelmserver','mokuainame'),'mokuai_info[mokuainame]',$mokuai_info['mokuainame'],'text','',0,lang('plugin/cardelmserver','mokuainame_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','mokuaititle'),'mokuai_info[mokuaititle]',$mokuai_info['mokuaititle'],'text','',0,lang('plugin/cardelmserver','mokuaititle_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','mokuaiico'),'mokuaiico',$mokuai_info['mokuaiico'],'filetext','',0,lang('plugin/cardelmserver','mokuaiico_comment'),'','',true);
		showsubmit('submit');
		showtablefooter();
		showformfooter();
	}else{
		if(!htmlspecialchars(trim($_GET['mokuai_info']['mokuainame']))) {
			cpmsg(lang('plugin/cardelmserver','mokuainame_nonull'));
		}
		$mokuaiico = addslashes($_POST['mokuaiico']);
		if($_FILES['mokuaiico']) {
			$upload = new discuz_upload();
			if($upload->init($_FILES['mokuaiico'], 'common') && $upload->save()) {
				$mokuaiico = $upload->attach['attachment'];
			}
		}
		$datas = $_GET['mokuai_info'];
		foreach ( $datas as $k=>$v) {
			$data[$k] = htmlspecialchars(trim($v));
			if(!DB::result_first("describe ".DB::table('cardelmserver_mokuai')." ".$k)) {
				$sql = "alter table ".DB::table('cardelmserver_mokuai')." add `".$k."` varchar(255) not Null;";
				runquery($sql);
			}
		}
		if($mokuaiid) {
			DB::update('cardelmserver_mokuai',$data,array('mokuaiid'=>$mokuaiid));
		}else{
			DB::insert('cardelmserver_mokuai',$data);
		}
		cpmsg(lang('plugin/cardelmserver', 'mokuai_edit_succeed'), 'action='.$this_page.'&subac=mokuailist', 'succeed');
	}
}

?>