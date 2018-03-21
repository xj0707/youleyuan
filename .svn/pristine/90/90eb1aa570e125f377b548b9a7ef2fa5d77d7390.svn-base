<?php

//连接数据库
$pdo=new PDO('mysql:host=localhost;dbname=ChildrenGardenLocate','root','123321');
do{
	//严重预警
	$allowtime=time()-240;//设置的四分钟
	$status=1;
	$sql22="select wbid,p_phone,beginwarning,wifiMac from watch where status=:status and lasttime < :lasttime";
	$pdoStatment=$pdo->prepare($sql22);
	$pdoStatment->bindParam(':status',$status);
	$pdoStatment->bindParam(':lasttime',$allowtime);
	$pdoStatment->execute();
	$infos=$pdoStatment->fetchAll();
	$beginwarning1=1;
	//获取超级管理员的电话
	$type=1;
	$sql23="select username from admin where type=:type";
	$pdoStatment=$pdo->prepare($sql23);
	$pdoStatment->bindParam(':type',$type);
	$pdoStatment->execute();
	$admin=$pdoStatment->fetch();
	$admin_phone=$admin['username'];
	//获取所有工作的保安的电话
	$sql24="select b_phone from security where type=:type";
	$pdoStatment=$pdo->prepare($sql24);
	$pdoStatment->bindParam(':type',$type);
	$pdoStatment->execute();
	$phones=$pdoStatment->fetchAll();
	$sec_phone='';//保安的电话
	if(!empty($phones)){
		foreach($phones as $phone){
			$sec_phone.=','.$phone['b_phone'];
		}
	}
	if(!empty($infos)){
		foreach($infos as $info){
			if($info['beginwarning']==0){
				$wifimac=$info['wifiMac'];
				$sql_mac="select wifi_wz from wifi where wifimac=:wifimac";
				$pdoStatment=$pdo->prepare($sql_mac);
				$pdoStatment->bindParam(':wifimac',$wifimac);
				$pdoStatment->execute();
				$mac=$pdoStatment->fetch();
				$wifi_wz=$mac['wifi_wz'];//wifiw位置名称
				//发送短消息
				$smsapi="http://dx110.ipyy.net/sms.aspx";
		    	$userid="";//企业ID
		    	$account="GYKJ002";//账号
		    	$password=strtoupper(md5('GYKJ00236'));//密码
		    	$mobile=$info['p_phone'].$sec_phone.','.$admin_phone;//家长电话和保安电话以及、超级管理的电话
		    	$content="腕表：".$info['wbid']."位置丢失！！上次位置：".$wifi_wz.";请速速查看！！【游乐园】";//内容
		       	$sendurl=$smsapi."?action=send&userid=".$userid."&account=".$account."&password=".$password."&mobile=".$mobile."&content=".$content."&sendTime=&extno=";
		       	$result =file_get_contents($sendurl) ;
		       	$arr=simplexml_load_string($result);
				$send_time=date('Y-m-d H:i:s');
		       	if($arr->returnstatus=='Success'){
		       		echo  $send_time."send $mobile success! \n";
		       	}else{
		       		echo $send_time."send $mobile Faild! \n";
		       	}
				//更新beginwarning
				$sql23="update watch set beginwarning=:beginwarning where wbid=:wbid";
				$pdoStatment=$pdo->prepare($sql23);
				$pdoStatment->bindParam(':beginwarning',$beginwarning1);
				$pdoStatment->bindParam(':wbid',$info['wbid']);
				$pdoStatment->execute();
					
			}
				
		}
	}
	//严重预警代码结束
	sleep(10);//休息十秒再次进行轮询
}while (true);