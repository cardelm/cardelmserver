<?php

$action = getgpc('action');
$ajaxtype = getgpc('ajaxtype');

$actions = array('codeprat','discode');
$action = !in_array($action,$actions) ? $actions[0]: $action;

if($action == 'codeprat'){
	$edittype = getgpc('edittype');
	$codeid = getgpc('codeid');
	$pageinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_code')." WHERE codeid='".$codeid."'");

	$navtitle = lang('plugin/cardelmserver','add_code');
	$ajaxout = '<form id="setnav" method="post" autocomplete="off" action="plugin.php?id=cardelmserver:ajax&action=discode&edittype='.$edittype.'" onsubmit="return submitcode(this);">';
	$ajaxout .= '<input type="hidden" name="formhash" value="{FORMHASH}" />';
	$ajaxout .= '<input type="hidden" name="edittype" value="'.$edittype.'" />';
	//$ajaxout .= '<input type="hidden" name="submit" value="1" />';
	$ajaxout .= '<input type="hidden" name="handlekey" value="$_GET[handlekey]" />';
	$ajaxout .= $codeid?('<input type="hidden" name="codeid" value="'.$codeid.'" />'):'';
	$ajaxout .= '<table>';
	//$ajaxout .= '<tr><td width="80">注释：</td><td><input id="zhushi" type="text" name="zhushi" value="'.$pageinfo['zhushi'].'"></td></tr>';
	$ajaxout .= '<tr><td width="80">'.lang('plugin/cardelmserver','display:').'</td><td><input id="key" type="text" name="key" value="'.$pageinfo['key'].'"></td></tr>';
	$ajaxout .= '<tr><td>'.lang('plugin/cardelmserver','code:').'</td><td><textarea id="value" name="value" rows="8" cols="60">'.$pageinfo['value'].'</textarea></td></tr>';
	$ajaxout .= $codeid?('<tr><td colspan="2"><input type="checkbox" name="del" value="'.$codeid.'">'.lang('plugin/cardelmserver','del').'</td></tr>'):'';
	$ajaxout .= '<tr><td colspan="2"><input type="submit" class="btn" value="'.lang('plugin/cardelmserver','submit').'" ></td></tr>';
	$ajaxout .= '</table>';
	$ajaxout .= '</form>';
	$ajaxout .= '<script language="javascript">';
	$ajaxout .= 'function submitcode(obj){';
	$ajaxout .= 'ajaxpost(\'setnav\', \'discode\',\'discode\',\'\',\'\',function(){hideWindow(\''.$_GET['handlekey'].'\');});';
	$ajaxout .= 'return false;';
	$ajaxout .= '}';
	$ajaxout .= '</script>';
}elseif($action == 'discode'){
	$ajaxout = '';
	$edittype = getgpc('edittype');
	$codeid = getgpc('codeid');
	$del = getgpc('del');
	$data['zhushi'] = dhtmlspecialchars(trim(getgpc('zhushi')));
	$data['key'] = dhtmlspecialchars(trim(getgpc('key')));
	$data['value'] = dhtmlspecialchars(trim(getgpc('value')));
	$data['type'] = $edittype;
	$data['zhushi'] = $data['zhushi'] ? $data['zhushi'] : '注释';
	if($data['zhushi']&&$data['key']&&$data['value']) {
		if($codeid) {
			DB::update('cardelmserver_code',$data,array('codeid'=>$codeid));
		}else{
			DB::insert('cardelmserver_code', $data);
		}
	}
	if($del) {
		DB::delete('cardelmserver_code',array('codeid'=>$del));
	}
	$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_code')." WHERE type = '".$edittype."' order by codeid asc");
	while($row = DB::fetch($query)) {
		if($row['key']) {
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
			$ajaxout .= '<input type="button" class="btn" onclick="showWindow(\'setnav\', \'plugin.php?id=cardelmserver:ajax&ajaxtype=win&action=codeprat&codeid='.$row['codeid'].'&edittype='.$edittype.'\', \'get\', 0);" value="改"><a href="###" onclick="insertunit(\''.$value1.'\')">'.dhtmlspecialchars($row['key']).'</a>||'."\n";
		}
	}
	$ajaxout .= '<input type="button" class="btn" onclick="showWindow(\'setnav\', \'plugin.php?id=cardelmserver:ajax&ajaxtype=win&action=codeprat&edittype='.$edittype.'\', \'get\', 0);" value="新建">';
}
include template('common/header_ajax');
include template($ajaxtype=='win'?'ajaxw':'ajax',0,'source/plugin/cardelmserver/template');
include template('common/footer_ajax');

?>