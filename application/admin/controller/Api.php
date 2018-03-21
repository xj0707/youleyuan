<?php
echo 1;exit;
set_time_limit(0);
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("Could not create    socket\n"); // 创建一个Socket
$connection = socket_connect($socket, '10.104.224.237', 2048) or die("Could not connet server\n");    //  连接
//$str=$this->vendorId.'*';
$verifycode=Lowermd5('YW*861900039990378*0001*0087*UDmybhkj@ncyely@wyy_20170102150500');
$msg="YW*861900039990378*0001*0087*UD,280916,014811,V,0.000000,N,0.000000,E,0.00,0.0,0.0,0,100,75,0,0:0,00000000,6,1,460,0,10138,4172,23,10138,3891,22,10138,3591,19,10138,3762,18,10168,4161,17,10138,3592,16,5,wifi_1,fc:d7:33:56:f9:06,-45,wifi_2,54:2a:a2:ce:63:f8,-56,wifi_3,06:69:68:d7:93:bc,-67,wifi_4,24:69:68:d7:93:bc,-73,wifi_5,ee:26:ca:c0:ec:92,-74,$verifycode";
echo $msg;
function Lowermd5($str){
	$p_str=md5($str);
	return substr($p_str,8,16);
}
exit;
socket_write($socket, $msg) or die("Write failed\n"); // 数据传送 向服务器发送消息
while ($buff = @socket_read($socket, 1024, PHP_NORMAL_READ)) {
	echo("Response was:" . $buff . "\n");
		
}
socket_close($socket);
//获取小写16为MD5加密

