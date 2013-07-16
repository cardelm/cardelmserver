<?php

//这是为了杜绝不小心覆盖文件而专门用于github同步的程序
//实际操作过程中，因为github的pro中的文件夹与本地的wordpress、discuz文件夹名称相同，有几次将文件覆盖错，故以下代码为配合github专用，目的就是修改github中的代码，同时自动更新本地的代码，达到调试的作用
$this_dir = dirname(__FILE__);

$file_update_dir = 'C:/GitHub/cardelmserver/';

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
//$file_update_dir = DISCUZ_ROOT;

function addmokuai_init($mokuaiid){
	global $file_update_dir;
	$mokuaiinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$mokuaiid);
	$mokuai_dir = $file_update_dir.'source/plugin/cardelmserver/design/'.$mokuaiinfo['mokuainame'];
	if(!is_dir($mokuai_dir)){
		dmkdir($mokuai_dir);
	}
	if(DB::result_first("SELECT count(*) FROM ".DB::table('cardelmserver_page')." WHERE mokuaiid=".$mokuaiid." AND pagename = 'setting'")==0){
		DB::insert('cardelmserver_page', array('mokuaiid'=>$mokuaiid,'pagename'=>'setting','pagetype'=>'admin','description'=>lang('plugin/cardelmserver','page_admin_setting_description')));
	}
	if(!file_exists($mokuai_dir.'/admin_setting.inc.php')){
		file_put_contents ($mokuai_dir.'/admin_setting.inc.php',"");
	}
}
//生成后台文件代码
//$pageid为页面编号，$updatetype为是否强制更新
function makeadminpagefile($pageid,$canshu=array(),$updatetype=0){
	global $file_update_dir;
	$pageinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_page')." WHERE pageid=".$pageid);
	$mokuaiinfo = DB::fetch_first("SELECT * FROM ".DB::table('cardelmserver_mokuai')." WHERE mokuaiid=".$pageinfo['mokuaiid']);
	$filename = $pageinfo['pagetype'].'_'.$pageinfo['pagename'].'.inc.php';
	$fullfilename = $file_update_dir.'source/plugin/cardelmserver/design/'.$mokuaiinfo['mokuainame'].'/'.$filename;
	if($updatetype||!file_exists($fullfilename)){
		$canshu['keyname'] = $canshu['keyname'] ? $canshu['keyname'] : $pageinfo['pagename']."id";
		$canshu['tablename'] = $canshu['tablename'] ? $canshu['tablename'] : 'cardelmserver_'.$pageinfo['pagename'];
		$canshu['listtips']['en'] = $canshu['listtips']['en'] ? $canshu['listtips']['en'] : $pageinfo['pagename'].'_list_tips';
		$canshu['list'] = $canshu['list'] ? $canshu['list'] : array($pageinfo['pagename']."name",$pageinfo['pagename']."title",'');
		$file_text = "<?php\n\n";
		$file_tips = "/**\n*\t卡益联盟程序\n*\t文件名：".$filename."  创建时间：".dgmdate(time(),'dt')."  杨文\n*\n*/\n";
		$file_text .= $file_tips."if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {\n\texit('Access Denied');\n}\n";
		$file_text .= "\$this_page =  this_page(".$mokuaiinfo['mokuainame'].",".$pageinfo['pagename'].");\n\n";
		$file_text .= "\$subac = getgpc('subac');\n\$subacs = array('".$pageinfo['pagename']."list','".$pageinfo['pagename']."edit');\n\$subac = in_array(\$subac,\$subacs) ? \$subac : \$subacs[0];\n\n";

		$file_text .= "\$".$canshu['keyname']." = getgpc('".$canshu['keyname']."');\n\$".$pageinfo['pagename']."_info = \$".$canshu['keyname']." ? DB::fetch_first(\"SELECT * FROM \".DB::table('".$canshu['tablename']."').\" WHERE ".$canshu['keyname']."=\".\$".$canshu['keyname'].") : array();\n\n";

		$file_text .= "if(\$subac == '".$pageinfo['pagename']."list') {\n";
		$file_text .= "\tif(!submitcheck('submit')) {\n";
		$file_text .= "\t\tshowtips(lang('plugin/cardelm','".$canshu['listtips']['en']."'));\n";
		$file_text .= "\t\tshowformheader(\$this_page.'&subac=".$pageinfo['pagename']."list');\n";
		$file_text .= "\t\tshowtableheader(lang('plugin/cardelm','".$pageinfo['pagename']."_list'));\n";
		$file_text .= "\t\t\n";
		$file_text .= "\t\t\n";
		$file_text .= "\t\t\n";
		$file_text .= "\t\t\n";
		$file_text .= "\t\t\n";
		$file_text .= "\t\t\n";
		$file_text .= "\t}else{\n";
		$file_text .= "\t}\n";
		$file_text .= "}elseif(\$subac == '".$pageinfo['pagename']."edit') {\n";
		$file_text .= "\tif(!submitcheck('submit')) {\n";
		$file_text .= "\t}else{\n";
		$file_text .= "\t}\n";
		$file_text .= "}\n";
		$file_text .= "?>";
		file_put_contents ($fullfilename,$file_text);
	}
}

function this_page($mokuainame,$pagename){
	return 'plugins&identifier=cardelmserver&pmod=admincp&submod='.$mokuainame.'_'.$pagename;
}
function rmdirs($srcdir) {
	$dir = @opendir($srcdir);
	while($entry = @readdir($dir)) {
		$file = $srcdir.$entry;
		if($entry != '.' && $entry != '..') {
			if(is_dir($file)) {
				rmdirs($file.'/');
			} else {
				@unlink($file);
			}
		}
	}
	closedir($dir);
	rmdir($srcdir);
}

?>