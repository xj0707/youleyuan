<?php 

class Socket{
	
	public function __construct(){
		//连接数据库
		$pdo=new PDO('mysql:host=localhost;dbname=ChildrenGardenLocate','root','root');
		
		//确保在连接客户端时不会超时
		set_time_limit(0);
		//设置IP和端口号
		$address = "127.0.0.1";//域名nc.galbs.cn
		//$address1='';//ip
		$port = 2048; //调试的时候，可以多换端口来测试程序！
		/**
		 * 创建一个SOCKET
		 * AF_INET=是ipv4 如果用ipv6，则参数为 AF_INET6
		 * SOCK_STREAM为socket的tcp类型，如果是UDP则使用SOCK_DGRAM
		 */
		$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP) or die("socket_create() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
		//阻塞模式
		socket_set_block($sock) or die("socket_set_block() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
		
		//绑定到socket端口
		$result = socket_bind($sock, $address, $port) or die("socket_bind() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
		//开始监听
		$result = socket_listen($sock) or die("socket_listen() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
		echo "OK\nBinding the socket on $address:$port ... ";
		echo "OK\nNow ready to accept connections.\nListening on the socket ... \n";
		
		
		do { // never stop the daemon
			//它接收连接请求并调用一个子连接Socket来处理客户端和服务器间的信息
			$msgsock = socket_accept($sock) or  die("socket_accept() failed: reason: " . socket_strerror(socket_last_error()) . "/n");
			
			//读取客户端数据
			echo "Read client data \n";
			//socket_read函数会一直读取客户端数据,直到遇见\n,\t或者\0字符.PHP脚本把这写字符看做是输入的结束符.
			$buf = socket_read($msgsock, 8192);
			echo "Received msg: $buf   \n";
			
			$arr=explode(',',$buf);
			$arr1=explode('*',$arr[0]);
			$password='mybhkj@ncyely@wyy_20170102150500';
			$cs=substr($arr1[0],1);//厂商
			$shebei=$arr1[1];//设备
			$lsh=$arr1[2];//流水号
			$xyml=$arr1[4];//协议命令

			$substr=substr($arr[0], 1);//去掉【的协议命令
			$endsubstr=rtrim(end($arr),']');//发过来的密码
			//超级管理员的电话
			$type=1;
			$sql30="select username from admin where type=:type";
			$pdoStatment=$pdo->prepare($sql30);
			$pdoStatment->bindParam(':type',$type);
			$pdoStatment->execute();
			$admin=$pdoStatment->fetch();
			$admin_phone=$admin['username'];
			
			//获取所有工作的保安的电话
			$sql88="select b_phone from security where type=:type";
			$pdoStatment=$pdo->prepare($sql88);
			$pdoStatment->bindParam(':type',$type);
			$pdoStatment->execute();
			$phones=$pdoStatment->fetchAll();
			$sec_phone='';//保安的电话
			if(!empty($phones)){
				foreach($phones as $phone){
					$sec_phone.=','.$phone['b_phone'];
				}
			}
			
			/**
			 * 初始化init
			 */
			if($xyml=='INIT'){
				if($endsubstr==Lowermd5($substr.$password)){
					//连接数据库做一些操作
					$sql="select id from watch where wbid=:wbid";
					$pdoStatment=$pdo->prepare($sql);//通知mysql做准备返回pdostatement的类对象
					$pdoStatment->bindParam(':wbid',$shebei);
					$pdoStatment->execute();//执行准备好的sql语句成功返回true
					//数据传送 向客户端写入返回结果
					$row=$pdoStatment->fetch();
					$len=sprintf("%04d", dechex(23));//转换成四位十六进制的数
					$verify=Lowermd5($cs.'*'.$shebei.'*'.$lsh.'*'.$len.'*'.$xyml.$password);//我传过去的验证码
					if($row['id']){
					    
						$msg = "[".$cs.'*'.$shebei.'*'.$lsh.'*'.$len.'*'.$xyml.",1,".$verify."]\n";
					}else{
						
						$msg = "[".$cs.'*'.$shebei.'*'.$lsh.'*'.$len.'*'.$xyml.",0,".$verify."]\n";
					}
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
					
				}else{
					//数据传送 向客户端写入返回结果
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 * 链路保持LK（心跳包）
			 */
			if($xyml=='LK'){
				if($endsubstr==Lowermd5($substr.$password)){
					//数据传送 向客户端写入返回结果
				    $len=sprintf("%04d", dechex(39));//转换成四位十六进制的数
					$time=date("Y-m-d,H:i:s",time());
					$verify=Lowermd5($cs.'*'.$shebei.'*'.$lsh.'*'.$len.'*'.$xyml.$password);//我传过去的验证码
								
					$msg ="[".$cs.'*'.$shebei.'*'.$lsh.'*'.$len.'*'.$xyml.",$time,".$verify."]\n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 * 3.位置数据上报
			 */
			if($xyml=='UD'){
				if($endsubstr==Lowermd5($substr.$password)){
					$a=17+$arr[17]*3+3+1;//获取wifi数组的键名
					if($arr[$a]!=0){
						$wifistrong=array();
						for($i=1;$i<=$arr[$a];$i++){
							$wifistrong[$a+$i*3]=$arr[$a+$i*3];
						}
						rsort($wifistrong);//降序排列
						$index=array_keys($arr,$wifistrong[0]);
						//操作数据库
						$state=0;
						$sql1="select id,w_phone from watchusage where state=:state and watchname=:watchname order by id desc limit 1";
						$pdoStatment=$pdo->prepare($sql1);
						$pdoStatment->bindParam(':watchname',$shebei);
						$pdoStatment->bindParam(':state',$state);
						$pdoStatment->execute();
						$row=$pdoStatment->fetch();
						$watchusage_id=$row['id'];
						$phone=$row['w_phone'];//家长的电话
						//插入腕表路径
						$time=time();
						$sql2="insert into watchlocation (watchusage_id,wifiname,wifimac,wifistrong,time) values (?,?,?,?,?)";
						$pdoStatment=$pdo->prepare($sql2);
						$pdoStatment->bindParam(1,$watchusage_id);
						$pdoStatment->bindParam(2,$arr[$index[0]-2]);
						$pdoStatment->bindParam(3,$arr[$index[0]-1]);
						$pdoStatment->bindParam(4,$arr[$index[0]]);
						$pdoStatment->bindParam(5,$time);
						$c=$pdoStatment->execute();
						//报警发送短信
						$sql4="select iswarning from wifi where wifimac=:wifimac";
						$pdoStatment=$pdo->prepare($sql4);
						$pdoStatment->bindParam(':wifimac',$arr[$index[0]-1]);
						$pdoStatment->execute();
						$res=$pdoStatment->fetch();
						$beginwarning=0;
						if($res['iswarning']==1){//说明是报警wifi
						    $sql20="select lastwifiname watch where wbid=:wbid";
						    $pdoStatment=$pdo->prepare($sql20);
						    $pdoStatment->bindParam(':wbid',$shebei);
						    $pdoStatment->execute();
						    $res1=$pdoStatment->fetch();
						    
						    if($res1['lastwifiname']==$arr[$index[0]-2]){
						        $sql21="update watch set wifiname=:wifiname,wifiMac=:wifiMac,wifistrong=:wifistrong,lasttime=:lasttime,lastwifiname=:lastwifiname,beginwarning=:beginwarning where wbid=:wbid";
						        $pdoStatment=$pdo->prepare($sql21);
						        $pdoStatment->bindParam(':wifiname',$arr[$index[0]-2]);
						        $pdoStatment->bindParam(':wifiMac',$arr[$index[0]-1]);
						        $pdoStatment->bindParam(':wifistrong',$arr[$index[0]]);
						        $pdoStatment->bindParam(':lasttime',$time);
						        $pdoStatment->bindParam(':lastwifiname',$arr[$index[0]-2]);
						        $pdoStatment->bindParam(':wbid',$shebei);
						        $pdoStatment->bindParam(':beginwarning',$beginwarning);
						        $pdoStatment->execute();
						    }else{
						        /**
						         * 短信发送位置
						         */
						    	$smsapi="http://dx110.ipyy.net/sms.aspx";
						    	$userid="123";//企业ID
						    	$account="GYKJ002";//账号
						    	$password=strtoupper(md5('GYKJ00236'));//密码
						    	$mobile=$phone.$sec_phone.','.$admin_phone;//绑定人的电话，安保的电话，超级管理员的电话
						    	$content="腕表:".$shebei."处于警报区域！！,请速速查看！！【游乐园】";//内容
						       	$sendurl=$smsapi."?action=send&userid=".$userid."&account=".$account."&password=".$password."&mobile=".$mobile."&content=".$content."&sendTime=&extno=";
						       	$result =file_get_contents($sendurl) ;//获得xml数据
						       	$xml_arr=simplexml_load_string($result);//转换成数组
							    if($xml_arr->returnstatus=='Success'){
									echo "send $mobile success!";
								}else{
									echo "send $mobile Faild!";
								}
						        //更新腕表
						        $sql21="update watch set wifiname=:wifiname,wifiMac=:wifiMac,wifistrong=:wifistrong,lasttime=:lasttime,lastwifiname=:lastwifiname,beginwarning=:beginwarning where wbid=:wbid";
						        $pdoStatment=$pdo->prepare($sql21);
						        $pdoStatment->bindParam(':wifiname',$arr[$index[0]-2]);
						        $pdoStatment->bindParam(':wifiMac',$arr[$index[0]-1]);
						        $pdoStatment->bindParam(':wifistrong',$arr[$index[0]]);
						        $pdoStatment->bindParam(':lasttime',$time);
						        $pdoStatment->bindParam(':lastwifiname',$arr[$index[0]-2]);
						        $pdoStatment->bindParam(':wbid',$shebei);
						        $pdoStatment->bindParam(':beginwarning',$beginwarning);
						        $pdoStatment->execute();
						    }
						    		
						}else{
						    //更新腕表的mac记录
						    $sql3="update watch set wifiname=:wifiname,wifiMac=:wifiMac,wifistrong=:wifistrong,lasttime=:lasttime,lastwifiname=:lastwifiname,beginwarning=:beginwarning  where wbid=:wbid";
						    $pdoStatment=$pdo->prepare($sql3);
						    $pdoStatment->bindParam(':wifiname',$arr[$index[0]-2]);
						    $pdoStatment->bindParam(':wifiMac',$arr[$index[0]-1]);
						    $pdoStatment->bindParam(':wifistrong',$arr[$index[0]]);
						    $pdoStatment->bindParam(':lasttime',$time);
						    $pdoStatment->bindParam(':lastwifiname',$arr[$index[0]-2]);
						    $pdoStatment->bindParam(':wbid',$shebei);
						    $pdoStatment->bindParam(':beginwarning',$beginwarning);
						    $pdoStatment->execute();
						}
						
						
						
						
						
						//报警的记录并发送短信
// 						$sql4="select iswarning from wifi where wifiname=:wifiname";
// 						$pdoStatment=$pdo->prepare($sql4);
// 						$pdoStatment->bindParam(':wifiname',$arr[$index[0]-2]);
// 						$pdoStatment->execute();
// 						$res=$pdoStatment->fetch();
// 						if($res['iswarning']==1){
// 							$sql6="select * from warning where wifiname=:wifiname";
// 							$pdoStatment=$pdo->prepare($sql6);
// 							$pdoStatment->bindParam(':wifiname',$arr[$index[0]-2]);
// 							$bool=$pdoStatment->execute();
// 							$info=$pdoStatment->fetch();
// 							if($info['id']){
// 								$time=time();
// 								$sql7="update warning set w_phone=:w_phone,wbid=:wbid,wifimac=:wifimac,wifistrong=:wifistrong,lasttime=:lasttime where id=:id";
// 								$pdoStatment=$pdo->prepare($sql7);
// 								$pdoStatment->bindParam(':w_phone',$phone);
// 								$pdoStatment->bindParam(':wbid',$arr1[1]);
// 								$pdoStatment->bindParam(':wifimac',$arr[$index[0]-1]);
// 								$pdoStatment->bindParam(':wifistrong',$arr[$index[0]]);
// 								$pdoStatment->bindParam(':lasttime',$time);
// 								$pdoStatment->bindParam(':id',$info['id']);
// 								$pdoStatment->execute();
// 								//发送短信
// 								//代码
// 							}else{
// 								$sql5="insert into warning (wbid,wifiname,wifimac,wifistrong,lasttime,type,w_phone) values (?,?,?,?,?,?,?)";
// 								$type=1;
// 								$pdoStatment=$pdo->prepare($sql5);
// 								$pdoStatment->bindParam(1,$arr1[1]);
// 								$pdoStatment->bindParam(2,$arr[$index[0]-2]);
// 								$pdoStatment->bindParam(3,$arr[$index[0]-1]);
// 								$pdoStatment->bindParam(4,$arr[$index[0]]);
// 								$pdoStatment->bindParam(5,$time);
// 								$pdoStatment->bindParam(6,$type);//1为普通预警比如到达未开放区域
// 								$pdoStatment->bindParam(7,$phone);
// 								$a=$pdoStatment->execute();
// 								//发送短信
// 								//代码
								
// 							}
// 						}
					}
					
					
// 					// 严重预警（1分钟都没有上传位置）
// 					$sql8="select wbid from watch where status=:status";
// 					$status=1;
// 					$pdoStatment=$pdo->prepare($sql8);
// 					$pdoStatment->bindParam(':status',$status);
// 					$pdoStatment->execute();
// 					$infos=$pdoStatment->fetchAll();
// 					foreach($infos as $wb){
// 						$sql9="select id,w_phone from watchusage where watchname=:watchname order by id desc limit 1";
// 						$pdoStatment=$pdo->prepare($sql9);
// 						$pdoStatment->bindParam(':watchname',$wb['wbid']);
// 						$pdoStatment->execute();
// 						$watch=$pdoStatment->fetch();
// 						$watch_id=$watch['id'];
// 						$phone1=$watch['w_phone'];//家长电话
// 						$sql10="select time from watchlocation where watchusage_id=:watchusage_id order by id desc limit 1";
// 						$pdoStatment=$pdo->prepare($sql10);
// 						$pdoStatment->bindParam(':watchusage_id',$watch_id);
// 						$pdoStatment->execute();
// 						$lasttime=$pdoStatment->fetch();
// 						if($lasttime['time']+60<time()){//一分钟都没有数据
// 							$sql12="select * from warning where wbid=:wbid and w_phone=:w_phone order by id desc limit 1";
// 							$pdoStatment=$pdo->prepare($sql12);
// 							$pdoStatment->bindParam(':wbid',$wb['wbid']);
// 							$pdoStatment->bindParam(':w_phone',$phone1);
// 							$pdoStatment->execute();
// 							$warning=$pdoStatment->fetch();
// 							$warning_id=$warning['id'];
// 							if($warning_id){//更新
// 								if($warning['lasttime']+30<time()){//30秒以内不更新同一条数据
// 									$time=time();
// 									$sql13="update warning set lasttime=:lasttime where id=:id";
// 									$pdoStatment=$pdo->prepare($sql13);
// 									$pdoStatment->bindParam(':lasttime',$time);
// 									$pdoStatment->bindParam(':id',$warning_id);
// 									$pdoStatment->execute();
// 									//发送短信
// 									//代码
// 								}
								
								
// 							}else{//添加
// 								$sql11="insert into warning (wbid,type,lasttime,w_phone) values (?,?,?,?)";
// 								$type=2;
// 								$time=time();
// 								$pdoStatment=$pdo->prepare($sql11);
// 								$pdoStatment->bindParam(1,$wb['wbid']);
// 								$pdoStatment->bindParam(2,$type);//严重预警
// 								$pdoStatment->bindParam(3,$time);
// 								$pdoStatment->bindParam(4,$phone1);
// 								$pdoStatment->execute();
// 								//发送短信
// 								//代码
// 							}
							
// 						}
// 					}
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");					
				}
			}
			/**
			 * 5.获取服务器连接IP
			 */
// 			if($xyml=='IPREQ'){
// 				if(end($arr)==Lowermd5($arr[0].$password)){
// 					$msg =$arr[0].',1,'.$address1.','.$port.','.end($arr)."\n";
// 					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
// 				}else{
// 					$msg = "password fase! \n";
// 					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
// 				}
// 			}
			
			/**
			 * 5.报警数据上报
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
					$msg =$arr[0].end($arr)."\n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");					
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *6.短信回复
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if($xyml==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
				
			//一旦输出被返回到客户端,父/子socket都应通过socket_close($msgsock)函数来终止
			//socket_close($msgsock);
		} while (true);
		//socket_close($sock);
	} 
	
	
	
}

new Socket();

//获取小写16为MD5加密
function Lowermd5($str){
	$p_str=md5($str);
	return substr($p_str,8,16);
}















