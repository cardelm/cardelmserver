<?php


if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
//这是为了杜绝不小心覆盖文件而专门用于github同步的程序
//实际操作过程中，因为github的pro中的文件夹与本地的wordpress、discuz文件夹名称相同，有几次将文件覆盖错，故以下代码为配合github专用，目的就是修改github中的代码，同时自动更新本地的代码，达到调试的作用
$this_dir = dirname(__FILE__);
//var_dump($this_dir);
if ($this_dir == 'C:\wamp\www\discuzdemo\dz3utf8\source\plugin\cardelmserver'){
	$source_dir = 'C:\GitHub\cardelmserver';
	check_dz_update();
}elseif($this_dir == 'D:\web\wamp\www\demo\dz3utf8\source\plugin\cardelmserver'){
	check_homedz_update();
}
//采用递归方式，自动更新discuz文件
function check_dz_update($path=''){
	clearstatcache();
	if($path=='')
		$path = 'C:\GitHub\cardelmserver';//本地的GitHub的discuz文件夹

	$out_path = 'C:\wamp\www\discuzdemo\dz3utf8'.str_replace("C:\GitHub\cardelmserver","",$path);//本地的wamp的discuz文件夹

	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {

			if ($file != "." && $file != "..") {
				if (is_dir($path."/".$file)) {
					if (!is_dir($out_path."/".$file)){
						dmkdir($out_path."/".$file);
					}
					check_dz_update($path."/".$file);
				}else{
					if (filemtime($path."/".$file)  > filemtime($out_path."/".$file)){//GitHub文件修改时间大于wamp时
						file_put_contents ($out_path."/".$file,$file =='cardelmserver.lang.php' ? file_get_contents($path."/".$file) : diconv(file_get_contents($path."/".$file),"UTF-8", "GBK//IGNORE"));
					}
				}
			}
		}
	}
}//func end
//采用递归方式，自动更新discuz文件
function check_homedz_update($path=''){
	clearstatcache();
	if($path=='')
		$path = 'C:\GitHub\cardelmserver';//本地的GitHub的discuz文件夹

	$out_path = 'D:\web\wamp\www\demo\dz3utf8'.str_replace("C:\GitHub\cardelmserver","",$path);//本地的wamp的discuz文件夹

	if ($handle = opendir($path)) {
		while (false !== ($file = readdir($handle))) {

			if ($file != "." && $file != "..") {
				if (is_dir($path."/".$file)) {
					if (!is_dir($out_path."/".$file)){
						dmkdir($out_path."/".$file);
					}
					check_homedz_update($path."/".$file);
				}else{
					if (filemtime($path."/".$file)  > filemtime($out_path."/".$file)){//GitHub文件修改时间大于wamp时
						file_put_contents ($out_path."/".$file,$file =='cardelmserver.lang.php' ? file_get_contents($path."/".$file) : diconv(file_get_contents($path."/".$file),"UTF-8", "GBK//IGNORE"));
					}
				}
			}
		}
	}
}//func end
/////////以上部分在正式的文件中，必须删除，仅在进行GitHub调试时使用///////////////



$submod = getgpc('submod');
$admin_menu = array();
$query = DB::query("SELECT * FROM ".DB::table('cardelmserver_menu')." WHERE upid = 0 order by displayorder asc");
$k = 0;
while($row = DB::fetch($query)) {

	$next_num = DB::result_first("SELECT count(*) FROM ".DB::table('cardelmserver_menu')." WHERE upid=".$row['menuid']);
	if ($next_num){
		$submods = $submenus = array();
		$kk = 0;
		$query1 = DB::query("SELECT * FROM ".DB::table('cardelmserver_menu')." WHERE upid=".$row['menuid']." order by displayorder asc");
		$current_menu = '';
		while($row1 = DB::fetch($query1)) {
			if ( $k == 0 && $kk == 0 && empty($submod) ){
				$submod = $row['menuname'].'_'.$row1['menuname'];
			}
			if ($submod == $row['menuname'].'_'.$row1['menuname']){
				$current_menu = $row1['menutitle'];
				$current_group = $row['menuname'];
			}
			$submods[] = $current_group.'_'.$row1['menuname'];
			$submenus[] = array($row1['menutitle'],'plugins&identifier=cardelmserver&pmod=admincp&submod='.$row['menuname'].'_'.$row1['menuname'],$submod == $current_group.'_'.$row1['menuname']);
			$kk++;
		}
		$admin_menu[] = array(array('menu'=>$current_menu  ? $current_menu  : $row['menutitle'],'submenu'=>$submenus),$current_group == $row['menuname']);
	}else{
		if ($k == 0 && empty($submod)){
			$submod = $row['menuname'];
		}
		$admin_menu[] = array($row['menutitle'],'plugins&identifier=cardelmserver&pmod=admincp&submod='.$row['menuname'],$submod == $row['menuname']);
	}
	$k++;
}
echo '<style>.floattopempty { height: 15px !important; height: auto; } </style>';
showsubmenu($plugin['name'].' '.$plugin['version'],$admin_menu);

//$submod_file = DISCUZ_ROOT.'source/plugin/cardelmserver/source/'.$submod.'.inc.php';
$submod_file = 'C:\GitHub\cardelmserver/source/plugin/cardelmserver/source/'.$submod.'.inc.php';
if (!file_exists($submod_file)){
	$mysubmod = str_replace($current_group."_","",$submod);
	file_put_contents($submod_file, "<?php\n\n/**\n*\t卡益联盟服务端程序\n*\t文件名：".$submod.".inc.php  创建时间：".dgmdate(time(),'dt')."  杨文\n*\n*/\n\nif(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {\n\texit('Access Denied');\n}\n\$this_page = 'plugins&identifier=cardelmserver&pmod=admincp&submod=".$submod."';\n\n".
	"\$subac = getgpc('subac');\n".
	"\$subacs = array('".$mysubmod."list','".$mysubmod."edit');\n".
	"\$subac = in_array(\$subac,\$subacs) ? \$subac : \$subacs[0];\n\n".
	"\$".$mysubmod."id = getgpc('".$mysubmod."id');\n".
	"\$".$mysubmod."_info = \$".$mysubmod."id ? DB::fetch_first(\"SELECT * FROM \".DB::table('cardelmserver_".$submod."').\" WHERE ".$mysubmod."id=\".\$".$mysubmod."id) : array();\n\n".
	"if(\$subac == '".$mysubmod."list') {\n".
	"\tif(!submitcheck('submit')) {\n".
	"\t\tshowtips(lang('plugin/cardelmserver','".$mysubmod."_list_tips'));\n".
	"\t\tshowformheader(\$this_page.'&subac=".$mysubmod."list');\n".
	"\t\tshowtableheader(lang('plugin/cardelmserver','".$mysubmod."_list'));\n".
	"\t\tshowsubtitle(array('', lang('plugin/cardelmserver','".$mysubmod."name'),lang('plugin/cardelmserver','shopnum'), lang('plugin/cardelmserver','".$mysubmod."quanxian'), lang('plugin/cardelmserver','status'), ''));\n".
	"\t\t//\$query = DB::query(\"SELECT * FROM \".DB::table('cardelmserver_".$submod."').\" order by ".$mysubmod."id asc\");\n".
	"\t\t//while(\$row = DB::fetch(\$query)) {\n".
	"\t\t\tshowtablerow('', array('class=\"td25\"','class=\"td23\"', 'class=\"td23\"', 'class=\"td23\"','class=\"td25\"',''), array(\n".
	"\t\t\t\t\"<input class=\\\"checkbox\\\" type=\\\"checkbox\\\" name=\\\"delete[]\\\" value=\\\"\$row[".$mysubmod."id]\\\">\",\n".
	"\t\t\t\$row['".$mysubmod."name'],\n".
	"\t\t\t\$row['".$mysubmod."name'],\n".
	"\t\t\t\$row['".$mysubmod."name'],\n".
	"\t\t\t\"<input class=\\\"checkbox\\\" type=\\\"checkbox\\\" name=\\\"statusnew[\".\$row['".$mysubmod."id'].\"]\\\" value=\\\"1\\\" \".(\$row['status'] > 0 ? 'checked' : '').\">\",\n".
	"\t\t\t\t\"<a href=\\\"\".ADMINSCRIPT.\"?action=\".\$this_page.\"&subac=".$mysubmod."edit&".$mysubmod."id=\$row[".$mysubmod."id]\\\" class=\\\"act\\\">\".lang('plugin/cardelmserver','edit').\"</a>\",\n".
	"\t\t\t));\n".
	"\t\t//}\n".
	"\t\techo '<tr><td></td><td colspan=\"6\"><div><a href=\"'.ADMINSCRIPT.'?action='.\$this_page.'&subac=".$mysubmod."edit\" class=\"addtr\">'.lang('plugin/cardelmserver','add_".$mysubmod."').'</a></div></td></tr>';\n".
	"\t\tshowsubmit('submit','submit','del');\n".
	"\t\tshowtablefooter();\n".
	"\t\tshowformfooter();\n".
	"\t}else{\n".
	"\t}\n".
	"}elseif(\$subac == '".$mysubmod."edit') {\n".
	"\tif(!submitcheck('submit')) {\n".
	"\t\tshowtips(lang('plugin/cardelmserver','".$mysubmod."_edit_tips'));\n".
	"\t\tshowformheader(\$this_page.'&subac=".$mysubmod."edit','enctype');\n".
	"\t\tshowtableheader(lang('plugin/cardelmserver','".$mysubmod."_edit'));\n".
	"\t\t\$".$mysubmod."id ? showhiddenfields(array('".$mysubmod."id'=>\$".$mysubmod."id)) : '';\n".
	"\t\tshowsetting(lang('plugin/cardelmserver','".$mysubmod."name'),'".$mysubmod."_info[".$mysubmod."name]',\$".$mysubmod."_info['".$mysubmod."name'],'text','',0,lang('plugin/cardelmserver','".$mysubmod."name_comment'),'','',true);\n".
	"\t\tshowsubmit('submit');\n".
	"\t\tshowtablefooter();\n".
	"\t\tshowformfooter();\n".
	"\t}else{\n".
	"\t\tif(!htmlspecialchars(trim(\$_GET['".$mysubmod."_info']['".$mysubmod."name']))) {\n".
	"\t\t\tcpmsg(lang('plugin/cardelmserver','".$mysubmod."name_nonull'));\n".
	"\t\t}\n".
	"\t\t\$datas = \$_GET['".$mysubmod."_info'];\n".
	"\t\tforeach ( \$datas as \$k=>\$v) {\n".
	"\t\t\t\$data[\$k] = htmlspecialchars(trim(\$v));\n".
	"\t\t\tif(!DB::result_first(\"describe \".DB::table('cardelmserver_".$submod."').\" \".\$k)) {\n".
	"\t\t\t\t\$sql = \"alter table \".DB::table('cardelmserver_".$submod."').\" add `\".\$k.\"` varchar(255) not Null;\";\n".
	"\t\t\t\trunquery(\$sql);\n".
	"\t\t\t}\n".
	"\t\t}\n".
	"\t\tif(\$".$mysubmod."id) {\n".
	"\t\t\tDB::update('cardelmserver_".$submod."',\$data,array('".$mysubmod."id'=>\$".$mysubmod."id));\n".
	"\t\t}else{\n".
	"\t\t\tDB::insert('cardelmserver_".$submod."',\$data);\n".
	"\t\t}\n".
	"\t\tcpmsg(lang('plugin/cardelmserver', '".$mysubmod."_edit_succeed'), 'action='.\$this_page.'&subac=".$mysubmod."list', 'succeed');\n".
	"\t}\n".
	"}\n".
	"\n".
	"?>");
}
require_once DISCUZ_ROOT.'source/plugin/cardelmserver/source/'.$submod.'.inc.php';

// 浏览器友好的变量输出
function dump($var, $echo=true,$label=null, $strict=true){
    $label = ($label===null) ? '' : rtrim($label) . ' ';
    if(!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
        } else {
            $output = $label . " : " . print_r($var, true);
        }
    }else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if(!extension_loaded('xdebug')) {
            $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);
            $output = '<pre>'. $label. htmlspecialchars($output, ENT_QUOTES). '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
}
?>