<?php
/**
 *     卡益联盟服务端程序
 *		与客户端通信用的接口程序
 *      $Id: api.php 2013-07-12 15:27 yangwen $
 */

$indata = addslashes($_GET['indata']);
$sign = addslashes($_GET['sign']);
$apiaction = addslashes($_GET['apiaction']);


$outdata = array();

if($sign != md5(md5($indata))) {
	$outdata[] = lang('plugin/yiqixueba_server','api_sign_error');
}
if(!$apiaction) {
	$outdata[] = lang('plugin/yiqixueba_server','api_apiaction_error');
}


$indata = base64_decode($indata);
$indata = dunserialize($indata);


////




if (count($outdata)==0){
	if ($apiaction == 'install'){
		$sitekey = DB::result_first("SELECT sitekey FROM ".DB::table('yiqixueba_server_site')." WHERE siteurl='".$indata['siteurl']."'");
		if($sitekey) {
			$outdata['sitekey'] = $sitekey;
		}else{
			//$insertdata[''] = ;
			//DB::insert('',$data);
		}

	}elseif($apiaction == 'getmokuailist'){
		$site_info = DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_server_site')." WHERE siteurl='".$indata['siteurl']."'");
		$mokuais = dunserialize($site_info['mokuais']);
		$query = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." WHERE level=2 order by displayorder asc");
		while($row = DB::fetch($query)) {
			$mokuai_info[$row['mokuaiid']] = $row;
			$query1 = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuai')." WHERE upid=".$row['mokuaiid']." order by displayorder asc");
			while($row1 = DB::fetch($query1)) {
				$mokuai_info[$row1['mokuaiid']] = $row1;
				$query2 = DB::query("SELECT * FROM ".DB::table('yiqixueba_server_mokuaiver')." WHERE mokuaiid=".$row1['mokuaiid']." order by displayorder asc");
				while($row2 = DB::fetch($query2)) {
					if(in_array($row2['verid'],$mokuais)) {
						$upmokuaiid = DB::result_first("SELECT upid FROM ".DB::table('yiqixueba_server_mokuai')." WHERE mokuaiid='".$row2['mokuaiid']."'");
						$upverid = DB::result_first("SELECT verid FROM ".DB::table('yiqixueba_server_mokuaiver')." WHERE mokuaiid='".$upmokuaiid."'");
						if(!$outdata[$upverid]) {
							$outdata[$upverid] = DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_server_mokuaiver')." WHERE verid='".$upverid."'");
							$outdata[$upverid]['mokuainame'] = $mokuai_info[$outdata[$upverid]['mokuaiid']]['mokuainame'];
							$outdata[$upverid]['mokuaititle'] = $mokuai_info[$outdata[$upverid]['mokuaiid']]['mokuaititle'];
							$outdata[$upverid]['mokuaiico'] = $mokuai_info[$outdata[$upverid]['mokuaiid']]['mokuaiico'];
							$outdata[$upverid]['upmokuai'] = $mokuai_info[$mokuai_info[$outdata[$upverid]['mokuaiid']]['upid']]['mokuainame'];
						}
						$outdata[$row2['verid']] = $row2;
						$outdata[$row2['verid']]['mokuainame'] = $mokuai_info[$outdata[$row2['verid']]['mokuaiid']]['mokuainame'];
						$outdata[$row2['verid']]['mokuaititle'] = $mokuai_info[$outdata[$row2['verid']]['mokuaiid']]['mokuaititle'];
						$outdata[$row2['verid']]['mokuaiico'] = $mokuai_info[$outdata[$row2['verid']]['mokuaiid']]['mokuaiico'];
						$outdata[$row2['verid']]['upmokuai'] = $mokuai_info[$mokuai_info[$outdata[$row2['verid']]['mokuaiid']]['upid']]['mokuainame'];
					}
				}
			}
		}

	}elseif($apiaction == 'getserverdist'){
		$prov = intval($indata['prov']);
		$city = intval($indata['city']);
		if($city){
			$outdata = '<select name="dist"><option value="0">县区</option>';
			$query = DB::query("SELECT * FROM ".DB::table('common_district')." WHERE upid = ".$city." order by displayorder asc");
			while($row = DB::fetch($query)) {
				$outdata .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
			$outdata .= '</select>';
		}
		if($prov){
			$outdata = '<select name="city" onchange="ajaxget(\'plugin.php?id=yiqixueba&submod=ajax&ajaxtype=getserverdist&city=\'+ this.value, \'dist\');"><option value="0">城市</option>';
			$query = DB::query("SELECT * FROM ".DB::table('common_district')." WHERE upid = ".$prov." order by displayorder asc");
			while($row = DB::fetch($query)) {
				$outdata .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
			$outdata .= '</select><br /><span id="dist" class="xi1"></span>';
		}
		if(!$prov && !$city){
			$outdata = '<select name="prov" onchange="ajaxget(\'plugin.php?id=yiqixueba&submod=ajax&ajaxtype=getserverdist&prov=\'+ this.value, \'city\');"><option value="0">省份</option>';
			$query = DB::query("SELECT * FROM ".DB::table('common_district')." WHERE upid = 0 order by displayorder asc");
			while($row = DB::fetch($query)) {
				$outdata .= '<option value="'.$row['id'].'">'.$row['name'].'</option>';
			}
			$outdata .= '</select><br /><span id="city" class="xi1"></span>';
		}
	}elseif($apiaction == 'getweixinsetting'){
		$site_info = DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_server_site')." WHERE siteurl ='".$indata['siteurl']."' AND sitekey = '".$indata['sitekey']."'");
		if($site_info){
			if(DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_server_wxq123_member')." WHERE membertype='site' AND typeid = ".$site_info['siteid'])==0){
				$insertdata['shibiema'] = random(4,1);
				$insertdata['token'] = random(6);
				$insertdata['membertype'] = 'site';
				$insertdata['typeid'] = $site_info['siteid'];
				DB::insert('yiqixueba_server_wxq123_member', $insertdata);
			}
			$weixin_info = DB::fetch_first("SELECT * FROM ".DB::table('yiqixueba_server_wxq123_member')." WHERE membertype='site' AND typeid = ".$site_info['siteid']);
			$outdata['shibiema'] = $weixin_info['shibiema'];
			$outdata['token'] = $weixin_info['token'];
		}
		//$outdata = $indata;

	//}elseif($apiaction == 'install'){
	//}elseif($apiaction == 'install'){
	//}elseif($apiaction == 'install'){
	//}elseif($apiaction == 'install'){
	//}elseif($apiaction == 'install'){
	//}elseif($apiaction == 'install'){
	//}elseif($apiaction == 'install'){
	}







//	if($indata['sitekey']) {
//	}else{
//		if($indata['siteurl']){
//			if (DB::result_first("SELECT count(*) FROM ".DB::table('yiqixueba_server_site')." WHERE siteurl='".$indata['siteurl']."'")==0){
//				//DB::insert('yiqixueba_server_site',$data);
//			}
//		}else{
//		}
//	}
}

if(is_array($outdata)){
	require_once libfile('class/xml');
	$filename = $apiaction.'.xml';
	$plugin_export = array2xml($outdata, 1);
	ob_end_clean();
	dheader('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
	dheader('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
	dheader('Cache-Control: no-cache, must-revalidate');
	dheader('Pragma: no-cache');
	dheader('Content-Encoding: none');
	dheader('Content-Length: '.strlen($plugin_export));
	dheader('Content-Disposition: attachment; filename='.$filename);
	dheader('Content-Type: text/xml');
	echo $plugin_export;
	define('FOOTERDISABLED' , 1);
	exit();
}else{
	echo $outdata;
}

?>