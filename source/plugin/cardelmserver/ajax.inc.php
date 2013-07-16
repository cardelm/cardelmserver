<?php
require_once DISCUZ_ROOT.'/source/plugin/cardelmserver/function.php';

$action = getgpc('action');
$ajaxtype = getgpc('ajaxtype');

$actions = array('codeprat','discode');
$action = in_array($action,$actions) ? $action:$actions[0] ;

if($action == 'codeprat'){
	$edittype = getgpc('edittype');
	$codeid = getgpc('codeid');
	$pageinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_code')." WHERE codeid='".$codeid."'");

	$navtitle = lang('plugin/cardelmserver','add_code');
	$ajaxout = '<form id="setnav" method="post" autocomplete="off" action="plugin.php?id=cardelmserver:ajax&action=discode&edittype='.$edittype.'" onsubmit="return submitcode(this);">';
	$ajaxout .= '<input type="hidden" name="formhash" value="{FORMHASH}" />';
	$ajaxout .= '<input type="hidden" name="edittype" value="'.$edittype.'" />';
	$ajaxout .= '<input type="hidden" name="handlekey" value="'.$_GET['handlekey'].'" />';
	$ajaxout .= $codeid?('<input type="hidden" name="codeid" value="'.$codeid.'" />'):'';
	$ajaxout .= '<table>';
	$ajaxout .= '<tr><td width="80">'.lang('plugin/cardelmserver','display:').'</td><td><input id="key" type="text" name="key" value="'.$pageinfo['key'].'"></td></tr>';
	$ajaxout .= '<tr><td>'.lang('plugin/cardelmserver','code:').'</td><td><textarea id="value" name="value" rows="8" cols="60">'.$pageinfo['value'].'</textarea></td></tr>';
	$ajaxout .= $codeid?('<tr><td colspan="2"><input class="checkbox" type="checkbox" name="del" value="'.$codeid.'">'.lang('plugin/cardelmserver','del').'</td></tr>'):'';
	$ajaxout .= '<tr><td colspan="2"><input type="submit" class="btn" value="'.lang('plugin/cardelmserver','submit').'" ></td></tr>';
	$ajaxout .= '</table>';
	$ajaxout .= '</form>';
	$ajaxout .= '<script language="javascript">';
	$ajaxout .= 'function submitcode(obj){';
	$ajaxout .= 'ajaxpost(\'setnav\', \'discode\',\'\',\'\',\'\',function(){hideWindow(\''.$_GET['handlekey'].'\');});';
	$ajaxout .= 'return false;';
	$ajaxout .= '}';
	$ajaxout .= '</script>';
}elseif($action == 'discode'){
	$ajaxout = '';
	$edittype = getgpc('edittype');
	$codeid = getgpc('codeid');
	$del = getgpc('del');
	$data['key'] = dhtmlspecialchars(trim(getgpc('key')));
	$data['value'] = dhtmlspecialchars(trim(getgpc('value')));
	$data['type'] = $edittype;
	if($data['key']&&$data['value']) {
		if($codeid) {
			DB::update('cardelmserver_code',$data,array('codeid'=>$codeid));
		}else{
			DB::insert('cardelmserver_code', $data);
		}
	}
	if($del) {
		DB::delete('cardelmserver_code',array('codeid'=>$del));
	}
	$ajaxout = getdzcodes($edittype);
}
include template('common/header_ajax');
include template($ajaxtype=='win'?'ajaxw':'ajax',0,'source/plugin/cardelmserver/template');
include template('common/footer_ajax');

?>