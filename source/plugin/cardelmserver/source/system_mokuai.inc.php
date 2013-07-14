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
$subacs = array('mokuailist','mokuaiedit','mokuaidesign');
$subac = in_array($subac,$subacs) ? $subac : $subacs[0];

$mokuaiid = getgpc('mokuaiid');
$mokuai_info = $mokuaiid ? DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$mokuaiid) : array();

if($subac == 'mokuailist') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','mokuai_list_tips'));
		showformheader($this_page.'&subac=mokuailist');
		showtableheader(lang('plugin/cardelmserver','mokuai_list'));
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_mokuai')." order by mokuaiid asc");
		while($row = DB::fetch($query)) {
			$mokuaiico = '';
			if($row['mokuaiico']!='') {
				$mokuaiico = str_replace('{STATICURL}', STATICURL, $row['mokuaiico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $mokuaiico) && !(($valueparse = parse_url($mokuaiico)) && isset($valueparse['host']))) {
					$mokuaiico = $_G['setting']['attachurl'].'common/'.$row['mokuaiico'].'?'.random(6);
				}
			}

			showtablerow('', array('style="width:45px"', 'valign="top" style="width:260px"', 'valign="top"', 'align="right" valign="bottom" style="width:160px"'), array(
				'<img src="'.$mokuaiico.'" width="40" height="40" align="left" style="margin-right:5px" />',
				'<span class="bold">'.$row['mokuaititle'].$row['mokuaiver'].($filemtime > TIMESTAMP - 86400 ? ' <font color="red">New!</font>' : '').'</span>  <span class="sml">('.$row['mokuainame'].')</span>',
				$row['description'],
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=mokuaidesign&mokuaiid=$row[mokuaiid]\" >".lang('plugin/cardelmserver','design')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=mokuaiedit&mokuaiid=$row[mokuaiid]\" >".$lang['edit']."</a>",
			));
		}
		echo '<tr><td></td><td colspan="3"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=mokuaiedit" class="addtr">'.lang('plugin/cardelmserver','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}elseif($subac == 'mokuaiedit') {
	if(!submitcheck('submit')) {
		$mokuaiico = '';
		if($mokuai_info['mokuaiico']!='') {
			$mokuaiico = str_replace('{STATICURL}', STATICURL, $mokuai_info['mokuaiico']);
			if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $mokuaiico) && !(($valueparse = parse_url($mokuaiico)) && isset($valueparse['host']))) {
				$mokuaiico = $_G['setting']['attachurl'].'common/'.$mokuai_info['mokuaiico'].'?'.random(6);
			}
		}
		$mokuaiicohtml = '<br /><label><input type="checkbox" class="checkbox" name="delete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$mokuaiico.'" width="40" height="40"/>';

		showtips(lang('plugin/cardelmserver','mokuai_edit_tips'));
		showformheader($this_page.'&subac=mokuaiedit','enctype');
		showtableheader(lang('plugin/cardelmserver','mokuai_edit'));
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		showsetting(lang('plugin/cardelmserver','mokuaiico'),'mokuaiico',$mokuai_info['mokuaiico'],'filetext','',0,lang('plugin/cardelmserver','mokuaiico_comment').$mokuaiicohtml,'','',true);
		showsetting(lang('plugin/cardelmserver','mokuainame'),'mokuai_info[mokuainame]',$mokuai_info['mokuainame'],'text','',0,lang('plugin/cardelmserver','mokuainame_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','mokuaititle'),'mokuai_info[mokuaititle]',$mokuai_info['mokuaititle'],'text','',0,lang('plugin/cardelmserver','mokuaititle_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','description'),'mokuai_info[description]',$mokuai_info['description'],'textarea','',0,lang('plugin/cardelmserver','description_comment'),'','',true);
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
		if($_POST['delete'] && addslashes($_POST['mokuaiico'])) {
			$valueparse = parse_url(addslashes($_POST['mokuaiico']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['mokuaiico']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'temp/'.addslashes($_POST['mokuaiico']));
			}
			$mokuaiico = '';
		}
		$datas = $_GET['mokuai_info'];
		$datas['mokuaiico'] = $mokuaiico;
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
}elseif($subac == 'mokuaidesign') {
	if(!submitcheck('submit')) {
		showtips(lang('plugin/cardelmserver','mokuai_design_tips'));
		showformheader($this_page.'&subac=mokuaidesign');
		showtableheader(lang('plugin/cardelmserver','mokuai_design'));
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_mokuai')." order by mokuaiid asc");
		while($row = DB::fetch($query)) {
			$mokuaiico = '';
			if($row['mokuaiico']!='') {
				$mokuaiico = str_replace('{STATICURL}', STATICURL, $row['mokuaiico']);
				if(!preg_match("/^".preg_quote(STATICURL, '/')."/i", $mokuaiico) && !(($valueparse = parse_url($mokuaiico)) && isset($valueparse['host']))) {
					$mokuaiico = $_G['setting']['attachurl'].'common/'.$row['mokuaiico'].'?'.random(6);
				}
			}

			showtablerow('', array('style="width:45px"', 'valign="top" style="width:260px"', 'valign="top"', 'align="right" valign="bottom" style="width:160px"'), array(
				'<img src="'.$mokuaiico.'" width="40" height="40" align="left" style="margin-right:5px" />',
				'<span class="bold">'.$row['mokuaititle'].$row['mokuaiver'].($filemtime > TIMESTAMP - 86400 ? ' <font color="red">New!</font>' : '').'</span>  <span class="sml">('.$row['mokuainame'].')</span>',
				$row['description'],
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=mokuaidesign&mokuaiid=$row[mokuaiid]\" >".lang('plugin/cardelmserver','design')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=mokuaiedit&mokuaiid=$row[mokuaiid]\" >".$lang['edit']."</a>",
			));
		}
		echo '<tr><td></td><td colspan="3"><div><a href="'.ADMINSCRIPT.'?action='.$this_page.'&subac=mokuaiedit" class="addtr">'.lang('plugin/cardelmserver','add_mokuai').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
	}else{
	}
}

?>