<?php

//var_dump($_POST);exit;2017-2-15 21:19
$subscribe='2017-04-19 12:60';

//echo $subscribe;exit;
if(strpos($subscribe,'-')){//有日期
	$a=explode(" ",$subscribe);
	$b=explode(":",$a[1]);
	if($b[1]>=60){
		$b[1]=55;
	}
	$subtime=strtotime($a[0])+$b[0]*60*60+$b[1]*60;
	date("Y-m-d H:i:s",$subtime);
}else{//没有日期的时候默认
	$date_elements=explode(":",$subscribe);
	$time_h=$date_elements[0]*60*60+$date_elements[1]*60;
	echo $time_h;
	echo "<br/>";
	$time_d=strtotime(date("Y-m-d"));
	echo $time_d;
	echo "<br/>";

	echo $time_total=$time_h+$time_d;
	echo date("Y-m-d H:i:s",$time_total);
}
echo $str=<<<STR
<form action="./public/index.php/admin/app_api/uploadpic" enctype="multipart/form-data" method="post">
<input type="text" name="id"/><br>
<input type="text" name="phone"/><br>
<input type="text" name="content"/><br>
<input type="file" name="pic" /> <br>
<input type="submit" value="上传" />
</form>
STR;
exit;
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("Could not create    socket\n"); // 创建一个Socket

$connection = socket_connect($socket, 'nc.galbs.cn', 2048) or die("Could not connet server\n");    //  连接

//init
//$verifycode=Lowermd5('YW*861900039990378*0005*003A*INITmybhkj@ncyely@wyy_20170102150500');
//echo $msg="[YW*861900039990378*0005*003A*INIT,,0,D10_MYHB_V0.1.1345.1520,0000,0000,$verifycode]";

//lk
$verifycode=Lowermd5('YW*861900039990378*0004*0017*LKmybhkj@ncyely@wyy_20170102150500');
$msg="[YW*861900039990378*0004*0017*LK,100,$verifycode]";

//ud
//$verifycode=Lowermd5('YW*861900039990378*0003*0097*UDmybhkj@ncyely@wyy_20170102150500');
//$msg="[YW*861900039990378*0003*0097*UD,310516,162747,V,0.000000,N,0.000000,E,0.00,0.0,0.0,0,87,100,0,0:0,00000000,3,1,460,0,20681,13942,27,20681,59883,17,20681,11752,17,0,$verifycode]";

socket_write($socket, $msg) or die("Write failed\n"); // 数据传送 向服务器发送消息
while ($buff = @socket_read($socket, 1024, PHP_NORMAL_READ)) {
	echo("Response was:" . $buff . "\n");
		
}
socket_close($socket);
//获取小写16为MD5加密
function Lowermd5($str){
	$p_str=md5($str);
	return substr($p_str,8,16);
}
