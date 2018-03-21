<?php 

class Socket{
	
	public function __construct(){
		//连接数据库
		$pdo=new PDO('mysql:host=localhost;dbname=ChildrenGardenLocate','root','');
		
		//确保在连接客户端时不会超时
		set_time_limit(0);
		//设置IP和端口号
		$address = "127.0.0.1";
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
		$result = socket_listen($sock, 8) or die("socket_listen() 失败的原因是:" . socket_strerror(socket_last_error()) . "/n");
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
			
			/**
			 * 初始化init
			 */
			if(end($arr1)=='INIT'){
				if(end($arr)==Lowermd5($arr[0].$password)){
					//连接数据库做一些操作
					$sql="select id from watch where wbid=:wbid";
					$pdoStatment=$pdo->prepare($sql);//通知mysql做准备返回pdostatement的类对象
					$pdoStatment->bindParam(':wbid',$arr1[1]);
					$pdoStatment->execute();//执行准备好的sql语句成功返回true
					//数据传送 向客户端写入返回结果
					$row=$pdoStatment->fetch();
					if($row['id']){
						$msg = $arr[0].",1,".end($arr)."\n";
					}else{
						$msg =$arr[0].",0,".end($arr)."\n";
					}
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
					
				}else{
					//数据传送 向客户端写入返回结果
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 * 链路保持LK
			 */
			if(end($arr1)=='Lk'){
				if(end($arr)==Lowermd5($arr[0].$password)){
					//数据传送 向客户端写入返回结果
					$time=date("Y-m-d,H:i:s",time());
					$msg =$arr[0].",$time,".end($arr)."\n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 * 3.位置数据上报
			 */
			if(end($arr1)=='UD'){
				if(end($arr)==Lowermd5($arr[0].$password)){
					$a=17+$arr[17]*3+3+1;//获取wifi数组的键名
					if($arr[$a]!=0){
						$wifistrong=array();
						for($i=1;$i<=$arr[$a];$i++){
							$wifistrong[$a+$i*3]=$arr[$a+$i*3];
						}
						rsort($wifistrong);//降序排列
						$index=array_keys($arr,$wifistrong[0]);
						//操作数据库
						$sql1="select id from watchusage where watchname=:watchname order by id desc limit 1";
						$pdoStatment=$pdo->prepare($sql1);
						$pdoStatment->bindParam(':watchname',$arr1[1]);
						$pdoStatment->execute();
						$row=$pdoStatment->fetch();
						$watchusage_id=$row['id'];
						//插入腕表路径
						$time=time();
						$sql2="insert into watchlocation (watchusage_id,wifiname,wifimac,wifistrong,time) values (?,?,?,?,?)";
						$pdoStatment=$pdo->prepare($sql2);
						$pdoStatment->bindParam(1,$watchusage_id);
						$pdoStatment->bindParam(2,$arr[$index[0]-2]);
						$pdoStatment->bindParam(3,$arr[$index[0]-1]);
						$pdoStatment->bindParam(4,$arr[$index[0]]);
						$pdoStatment->bindParam(5,$time);
						$pdoStatment->execute();
						//更新腕表的mac记录
						$sql3="update watch set wifiname=:wifiname,wifiMac=:wifiMac,wifistrong=:wifistrong,lasttime=:lasttime where wbid=:wbid";
						$pdoStatment=$pdo->prepare($sql3);
						$pdoStatment->bindParam(':wifiname',$arr[$index[0]-2]);
						$pdoStatment->bindParam(':wifiMac',$arr[$index[0]-1]);
						$pdoStatment->bindParam(':wifistrong',$arr[$index[0]]);
						$pdoStatment->bindParam(':lasttime',$time);
						$pdoStatment->bindParam(':wbid',$arr1[1]);
						$pdoStatment->execute();
					}
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");					
				}
			}
			/**
			 * 5.报警数据上报
			 */
			if(end($arr1)=='AL'){
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
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			/**
			 *
			 */
			if(end($arr1)==''){
				if(end($arr)==Lowermd5($arr[0].$password)){
			
				}else{
					$msg = "password fase! \n";
					socket_write($msgsock, $msg, strlen($msg)) or die("socket_write() failed: reason: " . socket_strerror(socket_last_error()) ."/n");
				}
			}
			//一旦输出被返回到客户端,父/子socket都应通过socket_close($msgsock)函数来终止
			socket_close($msgsock);
		} while (true);
		socket_close($sock);
	} 
	
	
	
}

new Socket();

//获取小写16为MD5加密
function Lowermd5($str){
	$p_str=md5($str);
	return substr($p_str,8,16);
}















