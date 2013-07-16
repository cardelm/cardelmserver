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
$subacs = array('mokuailist','mokuaiedit','pagelist','pageedit');
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
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=pagelist&mokuaiid=$row[mokuaiid]\" >".lang('plugin/cardelmserver','design')."</a>&nbsp;&nbsp;<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=mokuaiedit&mokuaiid=$row[mokuaiid]\" >".$lang['edit']."</a>",
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
		$mokuaiicohtml = '<br /><label><input type="checkbox" class="checkbox" name="icodelete" value="yes" /> '.$lang['del'].'</label><br /><img src="'.$mokuaiico.'" width="40" height="40"/>';

		showtips(lang('plugin/cardelmserver','mokuai_edit_tips'));
		showformheader($this_page.'&subac=mokuaiedit','enctype');
		showtableheader(lang('plugin/cardelmserver','mokuai_edit'));
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		showsetting(lang('plugin/cardelmserver','mokuaiico'),'mokuaiico',$mokuai_info['mokuaiico'],'filetext','',0,lang('plugin/cardelmserver','mokuaiico_comment').$mokuaiicohtml,'','',true);
		showsetting(lang('plugin/cardelmserver','mokuainame'),'mokuai_info[mokuainame]',$mokuai_info['mokuainame'],'text','',0,lang('plugin/cardelmserver','mokuainame_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','mokuaititle'),'mokuai_info[mokuaititle]',$mokuai_info['mokuaititle'],'text','',0,lang('plugin/cardelmserver','mokuaititle_comment'),'','',true);
		showsetting(lang('plugin/cardelmserver','description'),'mokuai_info[description]',$mokuai_info['description'],'textarea','',0,lang('plugin/cardelmserver','description_comment'),'','',true);
		//版本设置，计划以后再设计中实现，先暂时将代码保留在这里
//		showtablefooter();
//		showtableheader(lang('plugin/cardelmserver','mokuaiver'));
//		showsubtitle(array('',lang('plugin/cardelmserver','mokuaivername'),lang('plugin/cardelmserver','mokuaiverdescription')));
//		if(!$mokuaiid){
//			showtablerow('', array('class="td25"','class="td23"','class="td28"'),array('<input class="checkbox" type="checkbox" name="delete[]" value="" disabled="disabled">','<input name="newtitle[]" type="text" class="txt">','<textarea name="newdescription[]" rows="3" cols="40"></textarea>'));
//		}else{
//		}
//		echo '<tr><td></td><td colspan="3"><div><a href="###" onclick="addrow(this, 0);" class="addtr">'.lang('plugin/cardelmserver','add_mokuaiver').'</a></div></td></tr>';
		showsubmit('submit');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1,'<input class="checkbox" type="checkbox" name="delete[]">','td25'],[1, '<input name="newname[]" type="text" class="txt" value="V1.0">','td23'],[1, '<textarea name="newdescription[]" rows="3" cols="40">asdasdas</textarea>','td28']],
	];
	</script>
EOT;
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
		if($_POST['icodelete'] && addslashes($_POST['mokuaiico'])) {
			$valueparse = parse_url(addslashes($_POST['mokuaiico']));
			if(!isset($valueparse['host']) && !strexists(addslashes($_POST['mokuaiico']), '{STATICURL}')) {
				@unlink($_G['setting']['attachurl'].'temp/'.addslashes($_POST['mokuaiico']));
			}
			$mokuaiico = '';
		}
		if(getgpc('newname')){
			foreach(getgpc('newname') as $k=>$v ){
				dump($v);
			}
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
}elseif($subac == 'pagelist') {
	if(!submitcheck('submit')) {
		addmokuai_init($mokuaiid);
		$apgetype_array = array('admin','index','ajax','api','hook');
		$pagetype_select = '<select name="newpagetype[]">';
		foreach($apgetype_array as $k=>$v ){
			$pagetype_select .= '<option value="'.$v.'">'.lang('plugin/cardelmserver','pagetype_'.$v).'</option>';
		}
		$pagetype_select .= '</select>';
		showtips(lang('plugin/cardelmserver','mokuai').$mokuai_info['mokuainame'].lang('plugin/cardelmserver','mokuai_design_tips'));
		showformheader($this_page.'&subac=pagelist&mokuaiid='.$mokuaiid);
		showtableheader(lang('plugin/cardelmserver','page_list'));
		showsubtitle(array('',lang('plugin/cardelmserver','pagename'),lang('plugin/cardelmserver','pagetitle'),lang('plugin/cardelmserver','pagetype'),lang('plugin/cardelmserver','pagedescription')));
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_page')." WHERE mokuaiid=".$mokuaiid." order by mokuaiid asc");
		while($row = DB::fetch($query)) {
			showtablerow('', array('class="td25"', 'class="td23"','class="td23"', 'class="td23"', 'class="td28"',''), array(
				'<input class="checkbox" type="checkbox" name="delete[]" value="'.$row['pageid'].'">',
				$row['pagename'],
				$row['pagetitle'],
				lang('plugin/cardelmserver','pagetype_'.$row['pagetype']),
				$row['description'],
				"<a href=\"".ADMINSCRIPT."?action=".$this_page."&subac=pageedit&pageid=$row[pageid]\" >".$lang['edit']."</a>",
			));
		}
		echo '<tr><td></td><td colspan="3"><div><a href="###" onclick="addrow(this, 0);" class="addtr">'.lang('plugin/cardelmserver','add_page').'</a></div></td></tr>';
		showsubmit('submit','submit','del');
		showtablefooter();
		showformfooter();
		echo <<<EOT
<script type="text/JavaScript">
	var rowtypedata = [
		[[1,'','td25'],[1, '<input name="newpagename[]" type="text" class="txt">','td23'],[1, '<input name="newpagetitle[]" type="text" class="txt">','td23'],[1,'$pagetype_select','td23'],[1, '<textarea name="newpagedescription[]" rows="2" cols="40"></textarea>','td28']],
	];
	</script>
EOT;
	}else{
		if($ids = $_GET['delete']) {
			$ids = dintval($ids, is_array($ids) ? true : false);
			if($ids) {
				foreach ( $ids as $k=>$v) {
					$pageinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_page')." WHERE pageid=".$v);
					$mokuaiinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$pageinfo['mokuaiid']);
					@unlink($file_update_dir.'source/plugin/cardelmserver/design/'.$mokuaiinfo['mokuainame'].'/'.$pageinfo['pagetype'].'_'.$pageinfo['pagename'].'.inc.php');

				}
				DB::delete('cardelmserver_page', DB::field('pageid', $ids));
			}
		}
		if(getgpc('newpagename')){
			$mokuaiinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$mokuaiid);
			$get_pagetitle =getgpc('newpagetitle');
			$get_pagetype =getgpc('newpagetype');
			$get_description =getgpc('newpagedescription');
			foreach(getgpc('newpagename') as $k=>$v ){
				$data = array();
				$data['mokuaiid'] =  $mokuaiid;
				$data['pagename'] =  htmlspecialchars(trim($v));
				$data['pagetitle'] =  htmlspecialchars(trim($get_pagetitle[$k]));
				$data['pagetype'] =  htmlspecialchars(trim($get_pagetype[$k]));
				$data['description'] =  htmlspecialchars(trim($get_description[$k]));
				DB::insert('cardelmserver_page', $data);
				$page_file = $file_update_dir.'source/plugin/cardelmserver/design/'.$mokuaiinfo['mokuainame'].'/'.$data['pagetype'].'_'.$data['pagename'].'.inc.php';
				if(!file_exists($page_file)){
					file_put_contents ($page_file,"");
				}
			}
		}
		cpmsg(lang('plugin/cardelmserver', 'mokuaipage_edit_succeed'), 'action='.$this_page.'&subac=pagelist&mokuaiid='.$mokuaiid, 'succeed');
	}
}elseif($subac == 'pageedit') {
	$pageid = getgpc('pageid');
	$pageinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_page')." WHERE pageid=".$pageid);
	$mokuaiid = $pageinfo['mokuaiid'];
	$mokuaiinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$pageinfo['mokuaiid']);
	$page_file = $file_update_dir.'source/plugin/cardelmserver/design/'.$mokuaiinfo['mokuainame'].'/'.$pageinfo['pagetype'].'_'.$pageinfo['pagename'].'.inc.php';
	if(!file_exists($page_file)){
		file_put_contents ($page_file,"");
	}
	if(!submitcheck('submit')) {
		showtips($mokuaiinfo['mokuainame'].'--'.$pageinfo['pagename'].lang('plugin/cardelmserver','page_edit_tips'));
		showformheader($this_page.'&subac=pageedit','enctype');
		showtableheader(lang('plugin/cardelmserver','page_edit'));
		$pageid ? showhiddenfields(array('pageid'=>$pageid)) : '';
		$mokuaiid ? showhiddenfields(array('mokuaiid'=>$mokuaiid)) : '';
		echo '<tr class="border"><td class="vtop rowform">';
		echo '</td><td class="vtop tips2" s="1">';
			jsinsertunit();
			showtableheader($pageinfo['pagetype'].'_title');
			showtablefooter();
			//if($pagetype=='pageedit') {
				$template = '';
				$template .= '<div id="discode">';
				$template .= getdzcodes($pageinfo['pagetype']);
				$template .= '</div>';
				$template .= '</div>';
				$template .= '<textarea cols="80" rows="15" id="jstemplate" name="pagecode" style="width: 95%;" onkeyup="textareasize(this)">'.$pageinfo['pagecode'].'</textarea>';
				$template .= '<input type="hidden" name="preview" value="0" /><input type="hidden" name="submit" value="1" />';
				$template .= '</div>';
				echo '<div class="colorbox">';
				echo '<div class="extcredits">';
				echo $template;
				echo '<div id="editcodepart"></div>';
				echo '</div>';
			//}
		echo '</td></tr>';
		echo "<script>disallowfloat = '{$_G[setting][disallowfloat]}';</script>";
		echo '<tr><td colspan="2"><input type="submit" class="btn" value="'.$lang['submit'].'"></td></tr>';
		showtablefooter();
		showformfooter();
	}else{
		makeadminpagefile($pageid,array(),1);
		cpmsg(lang('plugin/cardelmserver', 'mokuai_edit_succeed'), 'action='.$this_page.'&subac=pagelist&mokuaiid='.$mokuaiid, 'succeed');
	}

}
//得到代码助手
function getdzcodes($edittype){
	$return = "";
	if($edittype == 'old'){
		foreach($theclass['fields'] as $key=>$value) {
			if($value['name']) {
				$return .= $value['name']. ': <a href="###" onclick="insertunit(\'{'.$key.'}\')">{'.$key.'}</a>';
			}
		}
		$return .= '<br />';
		$return .= cplang('blockstyle_add_loop').': <a href="###" onclick="insertunit(\'[loop]\n\n[/loop]\')">[loop]...[/loop]</a>';
		$return .= cplang('blockstyle_add_order').': <a href="###" onclick="insertunit(\'[order=N]\n\n[/order]\')">[order=N]...[/order]</a>';
		$return .= cplang('blockstyle_add_index').': <a href="###" onclick="insertunit(\'[index=N]\n\n[/index]\')">[index=N]...[/index]</a>';
		$return .= cplang('blockstyle_add_urltitle').': <a href="###" onclick=\'insertunit("<a href=\"{url}\"{target}>{title}</a>")\'>&lt;a href=...</a>';
		$return .= cplang('blockstyle_add_picthumb').': <a href="###" onclick=\'insertunit("<img src=\"{pic}\" width=\"{picwidth}\" height=\"{picheight}\" />")\'>&lt;img src=...&gt;</a>';
		if(in_array($_GET['blockclass'], array('forum_thread', 'portal_article', 'group_thread'), true)) {
			$return .= cplang('blockstyle_add_moreurl').': <a href="###" onclick="insertunit(\'{moreurl}\')">{moreurl}</a>';
		}
		$return .= cplang('blockstyle_add_currentorder').': <a href="###" onclick="insertunit(\'{currentorder}\')">{currentorder}</a>';
		$return .= cplang('blockstyle_add_parity').': <a href="###" onclick="insertunit(\'{parity}\')">{parity}</a>';
		$return .= '</div><br />';
	}else{
		$skey = str_replace("mokuai","",$edittype);
		$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_code')." WHERE type = '".$edittype."' order by codeid asc");
		while($row = DB::fetch($query)) {
			//if($row['zhushi']) {
				$value1 = str_replace("'","‘",$row['value']);
				$value1 = str_replace("\r","\\r",$value1);
				$value1 = str_replace("\n","\\n",$value1);
				//$value1 = str_replace("\t","\\t",$value1);
				$value1 = str_replace("\b","\\b",$value1);
				$value1 = str_replace("\f","\\f",$value1);
				$value1 = str_replace("\\","\\\\",$value1);
				$value1 = str_replace("\$","\\$",$value1);
				$value1 = str_replace("\'","\\'",$value1);
				$value1 = str_replace("\"","\\\"",$value1);
				$return .= '<input type="button" class="btn" onclick="showWindow(\'setnav\', \'plugin.php?id=cardelmserver:ajax&ajaxtype=win&action=codeprat&codeid='.$row['codeid'].'&edittype='.$edittype.'\', \'get\', 0);" value="'.lang('plugin/cardelmserver','gai').'"><a href="###" onclick="insertunit(\''.($value1).'\')">'.dhtmlspecialchars($row['key']).'</a>||'."\n";
			//}
		}
	}
	$return .= '<input type="button" class="btn" onclick="showWindow(\'setnav\', \'plugin.php?id=cardelmserver:ajax&ajaxtype=win&action=codeprat&edittype='.$edittype.'\', \'get\', 0);" value="'.lang('plugin/cardelmserver','new').'">';
	return $return;

}//end func
//代码助手插入代码
function jsinsertunit() {
?>
<script type="text/JavaScript">
	function isUndefined(variable) {
		return typeof variable == 'undefined' ? true : false;
	}

	function insertunit(text, obj) {
		if(!obj) {
			obj = 'jstemplate';
		}
		$(obj).focus();
		if(!isUndefined($(obj).selectionStart)) {
			var opn = $(obj).selectionStart + 0;
			$(obj).value = $(obj).value.substr(0, $(obj).selectionStart) + text + $(obj).value.substr($(obj).selectionEnd);
			$(obj).selectionStart = opn + strlen(text);
			$(obj).selectionEnd = opn + strlen(text);
		} else if(document.selection && document.selection.createRange) {
			var sel = document.selection.createRange();
			sel.text = text.replace(/\\r?\\n/g, '\r\n');
			sel.moveStart('character', -strlen(text));
		} else {
			$(obj).value += text;
		}
	}
</script>
<?php
}

?>