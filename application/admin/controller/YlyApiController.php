<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
class YlyApiController extends Controller{
	
	  protected $beforeActionList = [
			'first'=>  ['except'=>'login','outlogin'],
	];
	
	//前置方法
	protected function first(){
		if(!session('user_id')){
			return json(['code'=>-10,'message'=>'未登录访问']);
		}
	} 
	 
	//静态页面访问的判断
	public function verify(){
		if(!session('user_id')){
			return json(['code'=>-10,'message'=>'未登录访问']);
		}else{
			return json(['code'=>1,'message'=>'合法访问']);
		}
	}
	
	//普通管理员登录
	public function login(){
		$username=input('post.username');
		$password=input('post.password');
		if(!$username || !$password){
			return json(['code'=>-1,'message'=>'用户名或者密码为空']);
		}
		$info=Db::name('admin')->where('username',$username)->where('status',1)->find();
		if(!$info){
			return json(['code'=>-2,'message'=>'用户名或者密码错误']);
		}
		if(md5($password)!=$info['password']){
			return json(['code'=>-2,'message'=>'用户名或者密码错误']);
		}else{
			if($info['type']==1){
				session('user_name', $info['username']);
				session('user_id', $info['id']);
				return json(['code'=>1,'message'=>'登录成功！','type'=>1]);//1位超级管理员
			}elseif($info['type']==2){
				session('user_name', $info['username']);
				session('user_id', $info['id']);
				return json(['code'=>1,'message'=>'登录成功！','type'=>2]);//2为普通管理员
			}
			
		}
	}
	
	//管理员退出
	public function outlogin(){
		session('user_name', null);
		session('user_id', null);
		return json(['code'=>1,'message'=>'退出成功']);
	}
	
	public	function	TestWB(){
		$wbid=input('post.wbid');
		$info=Db::name('watch')->where('wbid',$wbid)->find();
		if(!$info){
			return json(['code'=>-2,'message'=>'该腕表不存在！']);
		}
		return json(['code'=>0,'message'=>'该腕表存在！']);
	}
	
	
	//腕表绑定
	public function wbbing(){
		
		$wbid=input('post.wbid');
		$p_phone=input('post.p_phone');
		$p_picurl=input('post.p_picurl');
		if(!$wbid || !$p_phone || !$p_picurl){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$info=Db::name('watch')->where('wbid',$wbid)->find();
		if(!$info){
			return json(['code'=>-2,'message'=>'该腕表不存在！']);
		}
		if($info['status']==1){
			return json(['code'=>-3,'message'=>'该腕表已经被绑定了！']);
		}
		$time=time();
		$res1=Db::name('watch')->where('wbid',$wbid)->update([
				'p_phone'=>$p_phone,
				'p_picurl'=>$p_picurl,
				'status'=>1,
		          'lasttime'=>$time,
		]);
		
		$data=[
				'watchname'=>$wbid,
				'w_phone'=>$p_phone,
				'w_pic'=>$p_picurl,
				'start_time'=>$time,
				'state'=>0
		];
		$res2=Db::name('watchusage')->insert($data);
		if($res1&&$res2){
			return json(['code'=>1,'message'=>'绑定成功！']);
		}else{
			return json(['code'=>-4,'message'=>'绑定失败！']);
		}
	}
	
	//指定的腕表ID查询
	public function wbsearch(){
		$wbid=input('post.wbid');
		if(!$wbid){
			return json(['code'=>-1,'message'=>'数据不完整！']);
		}
		$wb=Db::name('watch')->where('wbid',$wbid)->find();
		if(!$wb){
			return json(['code'=>-2,'message'=>'没有找到该腕表！']);
		}
		if($wb['status']==0){
			return json(['code'=>1,'message'=>'该腕表未绑定任何数据！']);
		}else{
			$data=Db::name('watch')->alias('a')->field('a.id,a.wbid,a.p_phone,a.p_picurl,a.wifiname,a.wifiMac,a.wifistrong,w.wifi_x,w.wifi_y,w.wifi_wz')->join('wifi w','a.wifiMac=w.wifimac')->where('a.wbid',$wbid)->find();
// 			$data=[
// 					'id'=>$wb['id'],
// 					'wbid'=>$wb['wbid'],
// 					'p_phone'=>$wb['p_phone'],
// 					'p_picurl'=>$wb['p_picurl'],
// 					'wifiname'=>$wb['wifiname'],
// 					'wifiMac'=>$wb['wifiMac'],
// 					'wifistrong'=>$wb['wifistrong']
// 			];
			return json(['data'=>$data,'code'=>2,'message'=>'操作成功']);
		}
	}
	
	//用腕表系统id查询
	public function wbidsearch(){
		$id=input('post.id');
		if(!$id){
			return json(['code'=>-1,'message'=>'数据不完整！']);
		}
		$wb=Db::name('watch')->where('id',$id)->find();
		if(!$wb){
			return json(['code'=>-2,'message'=>'没有找到该腕表！']);
		}
		if($wb['status']==0){
			$data=['wbid'=>$wb['id']];
			return json(['code'=>1,'message'=>'该腕表未绑定任何数据！','data'=>$data]);
		}else{
			$data=Db::name('watch')->alias('a')->field('a.id,a.wbid,a.p_phone,a.wifiname,a.wifiMac,a.wifistrong,w.wifi_x,w.wifi_y,w.wifi_wz')->join('wifi w','a.wifiMac=w.wifimac')->where('a.id',$id)->find();
				
// 			$data=[
// 					'id'=>$wb['id'],
// 					'wbid'=>$wb['wbid'],
// 					'p_phone'=>$wb['p_phone'],
// 					'wifiname'=>$wb['wifiname'],
// 					'wifiMac'=>$wb['wifiMac'],
// 			];
			return json(['data'=>$data,'code'=>2,'message'=>'操作成功']);
		}
	}
	
	//查询所有绑定腕表和没有绑定的腕表
	public function wbstatesearch(){
		$res1=Db::name('watch')->alias('a')->field('a.id,a.wbid,a.p_phone,a.wifiname,a.wifiMac,a.wifistrong,w.wifi_x,w.wifi_y')->join('wifi w','a.wifiMac=w.wifimac')->where('a.status',1)->select();//绑定的
		$res2=Db::name('watch')->field('id,wbid')->where('status',0)->select();//没有绑定
		return json(['code'=>1,'message'=>'操作成功','data1'=>$res1,'data2'=>$res2]);
	}
	
	//腕表解绑
	public function wbunwrap(){
		$wbid=input('post.wbid');
		if(!$wbid){
			return json(['code'=>-1,'message'=>'数据不完整！']);
		}
		$wb=Db::name('watch')->where('wbid',$wbid)->find();
		if(!$wb){
			return json(['code'=>-2,'message'=>'没有找到该腕表！']);
		}
		if($wb['status']==0){
			return json(['code'=>-3,'message'=>'该腕表未绑定任何数据！']);
		}else{
			$res1=Db::name('watch')->where('wbid',$wbid)->update([
					'p_phone'=>'',
					'p_picurl'=>'',
					'status'=>0,
					'wifiname'=>'',
					'wifiMac'=>'',
					'wifistrong'=>'',
					'lasttime'=>'',
					'lastwifimac'=>'',
					'beginwarning'=>0
			]);
			$time=time();
			$res2=Db::name('watchusage')->where('watchname',$wbid)->update([
					'end_time'=>$time,
					'state'=>1
			]);
			if($res1&&$res2){
				return json(['code'=>1,'message'=>'解绑成功！']);
			}else{
				return json(['code'=>-4,'message'=>'解绑失败！']);
			}
		}
	}
	
	/**
	 * 统计总数
	 */
	//腕表总数
	public function wbtotal(){
		$count=Db::name('watch')->count();
		$data=[
				'total'=>$count
		];
		return json(['data'=>$data,'code'=>1,'message'=>'操作成功']);
	}
	
	//绑定的腕表总数
	public function wbbingtotal(){
		$count=Db::name('watch')->where('status',1)->count();
		$data=[
				'total'=>$count
		];
		return json(['data'=>$data,'code'=>1,'message'=>'操作成功']);
	}
	
	//wifi总数
	public function wifi(){
		$count=Db::name('wifi')->count();
		$data=[
				'total'=>$count
		];
		return json(['data'=>$data,'code'=>1,'message'=>'操作成功']);
	}
	
	//游客总数
	public function tourist(){
		$count=Db::name('watchusage')->count();
		$data=[
				'total'=>$count
		];
		return json(['data'=>$data,'code'=>1,'message'=>'操作成功']);
	}
	
	/**
	 * 超级管理员
	 */
	//增加普通管理员账号
	public function addadmin(){
		$username=input('post.username');
		$password=input('post.password');
		if(!$username || !$password){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$info=Db::name('admin')->where('username',$username)->find();
		if($info){
			return json(['code'=>-5,'message'=>'该账号已经存在了']);
		}
		$data=[
				'username'=>$username,
				'password'=>md5($password)
		];
		$res=Db::name('admin')->insert($data);
		if($res){
			return json(['code'=>1,'message'=>'添加成功']);
		}else{
			return json(['code'=>-2,'message'=>'添加失败']);
		}
	}
	
	//查询所有管理员
	public function adminsearch(){
		$res=Db::name('admin')->field('id,username')->where('status',1)->select();
		if($res){
			return json(['code'=>1,'message'=>'操作成功','data'=>$res]);
		}else{
			return json(['code'=>-1,'message'=>'操作失败']);
		}
	}
	
	//删除管理员
	public function deladmin(){
		$id=input('post.id');
		if(!$id){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$res=Db::name('admin')->where('id',$id)->update(['status'=>0]);
		if($res){
			return json(['code'=>1,'message'=>'删除成功']);
		}else{
			return json(['code'=>-2,'message'=>'删除失败']);
		}
	}
	
	//wifi增加
	public function addwifi(){
		$wifiname=input('post.wifiname');
		$wifiMac=input('post.wifiMac');
		$wifi_x=input('post.wifi_x');
		$wifi_y=input('post.wifi_y');
		$wifi_type=input('post.type');
		$wifi_wz=input('post.wifi_wz');
		if(!$wifiname || !$wifiMac || !$wifi_x || !$wifi_y || !$wifi_wz){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$wifi_type=$wifi_type==0?0:1;
		$info=Db::name('wifi')->where('wifimac',$wifiMac)->find();
		if($info){
			return json(['code'=>-5,'message'=>'该wifi已经存在了']);
		}
		$data=[
				'wifiname'=>$wifiname,
				'wifimac'=>$wifiMac,
				'wifi_x'=>$wifi_x,
				'wifi_y'=>$wifi_y,
				'iswarning'=>$wifi_type,
				'wifi_wz'=>$wifi_wz
		];
		$res=Db::name('wifi')->insert($data);
		if($res){
			return json(['code'=>1,'message'=>'添加成功']);
		}else{
			return json(['code'=>-2,'message'=>'添加失败']);
		}
	}
	
	//wifi单个查询
	public function wifiidsearch(){
		$id=input('post.id');
		if(!$id){
			return json(['code'=>-2,'message'=>'非法操作']);
		}
		$res=Db::name('wifi')->where('id',$id)->find();
		if($res){
			return json(['code'=>1,'message'=>'操作成功','data'=>$res]);
		}else{
			return json(['code'=>-1,'message'=>'操作失败']);
		}
	}
	
	//wifi查询所有的
	public function wifisearch(){
		$res=Db::name('wifi')->select();
		if($res){
			return json(['code'=>1,'message'=>'操作成功！','data'=>$res]);
		}else{
			return json(['code'=>-1,'message'=>'没有任何数据']);
		}
	}
	
	//wifi删除
	public function delwifi(){
		$wifiid=input('post.id');
		if(!$wifiid){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$res=Db::name('wifi')->delete($wifiid);
		if($res){
			return json(['code'=>1,'message'=>'删除成功']);
		}else{
			return json(['code'=>-2,'message'=>'删除失败']);
		}
	}
	
	//腕表增加
	public function addwb(){
		$wbid=input('post.wbid');
		if(!$wbid){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$info=Db::name('watch')->where('wbid',$wbid)->find();
		if($info){
			return json(['code'=>-5,'message'=>'该腕表ID已经存在']);
		}
		$data=[
				'wbid'=>$wbid,
				'status'=>0
		];
		$res=Db::name('watch')->insert($data);
		if($res){
			return json(['code'=>1,'message'=>'添加成功']);
		}else{
			return json(['code'=>-2,'message'=>'添加失败']);
		}
	}
	
	//腕表删除
	public function delwb(){
		$id=input('post.id');
		if(!$id){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$res=Db::name('watch')->delete($id);
		if($res){
			return json(['code'=>1,'message'=>'删除成功']);
		}else{
			return json(['code'=>-2,'message'=>'删除失败']);
		}
	}
	
	//报警接口
	public function warning(){
		$time=time()-300;//五分钟没有上报数据
		$data=Db::name('watch')->alias('a')->field('a.id,a.wbid,a.p_phone,a.wifiname,a.wifiMac,a.wifistrong,w.wifi_x,w.wifi_y,w.wifi_wz')->join('wifi w','a.wifiMac=w.wifimac')->where('a.status',1)->where("a.lasttime < $time")->select();
		
		$names=Db::name('wifi')->field('wifimac')->where('iswarning',1)->select();
		
		$arr=array();
		if(!empty($names)){
			foreach($names as $name){
				$arr[]=Db::name('watch')->alias('a')->field('a.id,a.wbid,a.p_phone,a.wifiname,a.wifiMac,a.wifistrong,w.wifi_x,w.wifi_y,w.wifi_wz')->join('wifi w','a.wifiMac=w.wifimac')->where('a.status',1)->where('a.wifiMac',$name['wifimac'])->select();
			}
		}
		$arr1=array();
		foreach($arr as $v){
			foreach($v as $v1){
				$arr1[]=$v1;
			}
		}
		return json(['code'=>1,'message'=>'操作成功','data1'=>$arr1,'data2'=>$data]);
	}
	
	//添加保安
	public function security(){
		$b_name=input('post.b_name');
		$b_id=input('post.b_id');
		$b_phone=input('post.b_phone');
		if(!$b_name || !$b_id || !$b_phone){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$info=Db::name('security')->where('b_id',$b_id)->find();
		if($info){
			return json(['code'=>-5,'message'=>'该编号已经占用了']);
		}
		$data=[
				'b_name'=>$b_name,
				'b_id'=>$b_id,
				'b_phone'=>$b_phone
		];
		$res=Db::name('security')->insert($data);
		if($res){
			return json(['code'=>1,'message'=>'添加成功']);
		}else{
			return json(['code'=>-2,'message'=>'添加失败']);
		}
	}
	
	//查询保安
	public function securitysearch(){
		$infos=Db::name('security')->select();
		return json(['code'=>1,'message'=>'操作成功','data'=>$infos]);
	}
	
	//保安状态切换
	public function securitychange(){
		$id=input('post.id');
		$type=input('post.type');
		if(!$id || !$type){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$info=Db::name('security')->find($id);
		if(!$info){
			return json(['code'=>-2,'message'=>'没有找到改保安']);
		}
		if($type==1){
			$res=Db::name('security')->where('id',$id)->update(['type'=>2]);
			if($res){
				return json(['code'=>1,'message'=>'操作成功']);
			}else{
				return json(['code'=>-1,'message'=>'操作失败']);
			}
		}elseif($type==2){
			$res=Db::name('security')->where('id',$id)->update(['type'=>1]);
			if($res){
				return json(['code'=>1,'message'=>'操作成功']);
			}else{
				return json(['code'=>-1,'message'=>'操作失败']);
			}
		}
	}
	
	//删除保安
	public function securitydel(){
		$id=input('post.id');
		if(!$id){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$res=Db::name('security')->delete($id);
		if($res){
			return json(['code'=>1,'message'=>'删除成功']);
		}else{
			return json(['code'=>-2,'message'=>'删除失败']);
		}
	}

	//获取腕表上传ud历史记录
	public function getwbudinfo(){
		$wb=input('post.wbid');
		if(!$wb){
			return json(['code'=>-1,'message'=>'数据不完整']);
		}
		$res=Db::name('wbtime')->where('wbid',$wb)->order('id desc')->limit(20)->select();

		if($res){
			return json(['code'=>1,'data'=>$res,'message'=>'操作成功']);
		}else{
			return json(['code'=>-1,'message'=>'操作失败']);
		}
	}
	//首页轮播图上传
	public function lunbo(){
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('image');
		if(!$file){
			return json(['code'=>-2,'message'=>'没有文件信息']);
		}
		// 移动到框架应用根目录/public/uploads/ 目录下
		$info = $file->validate(['size'=>102400,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads');
		if($info){
			// 成功上传后 获取上传信息
			$filename=$info->getSaveName();
			$data=[
				'imageurl'=>$filename,
				'state'=>1//启用
			];
			$res=Db::name('lunbo')->insert($data);
			if($res){
				return json(['code'=>1,'message'=>'上传成功']);
			}else{
				return json(['code'=>-1,'message'=>'上传失败']);
			}
		}else{
			// 上传失败获取错误信息
			return json(['code'=>-3,'message'=>$file->getError()]);

		}
	}
	//新闻上传
	public function news(){
		$title=input('post.title');
		$describe=input('post.describe');
		$content=input('post.content');
		$file = request()->file('image');
		var_dump($file);
		exit;
	}






	
	//设置终端的参数
	public function setwb(){
		set_time_limit(0);
		$wbid=input('post.wbid');//腕表的imie号
		
		/* if(!$wbid || !$d_time){
			return json(['code'=>-1,'message'=>'数据不完整']);
		} */
		$info=Db::name('watch')->where('wbid',$wbid)->find();
		if($info){
			return json(['code'=>-2,'message'=>'不存在该腕表']);
		}
		if($info['wb_phone']){
			return json(['code'=>-3,'message'=>'该玩吧还没有绑定号码']);
		}
		$wb_phone=$info['wb_phone'];//腕表电话
		$len=sprintf("%04d", $info['cmd_num']);//转换成四位十六进制的数
		$number=$len;//次数流水号
		$shezhi='F48';//设置项
		$en_time='08:00-11:30|14:00-16:30|12345';//上课禁用时间段
		$kai_time="06:05";//开机时间
		$close_time="23:00";//关机时间
		$l_time='10';//亮屏时间
		$language='2';//语言1英语 2简体中文 3繁体中文
		$area='480';//时区时区 偏移的分钟数 UTC+8：00 为 480，UTC-7:00 为 -420
		$bulb='0';//指示灯，0常灭,1常亮,2,闪烁
		$period1="0:12345";//闹钟周期1
		$period2="0:0";//闹钟周期2
		$period3="0:12345";//闹钟周期3
		$alarm_time1="06:30";//闹钟时间1
		$alarm_time2="00:00";//闹钟时间2
		$alarm_time3="00:00";//闹钟时间3
		$d_moshi="1";//定位模式，0，1
		$d_time=input('post.d_time')?input('post.d_time'):60;//发送定位时间
		$a1='';
		$a2='';
		$a3='';
		$a4='';
		$a5='';
		$a6='宝贝';//手表宝贝名称
		$str='SET,'.$wb_phone.','.$number.','.$shezhi.','.$en_time.','.$kai_time.','.$close_time.','.$l_time.','.$language.','.$area.','.$bulb.','.$period1.','.$period2.','.$period3.','.$alarm_time1.','.$alarm_time2.','.$alarm_time3.','.$d_moshi.','.$d_time.','.$a1.','.$a2.','.$a3.','.$a4.','.$a5.','.$a6;
		$c_len=strlen($str)+16;//内容长度
		$content_len=sprintf("%04d", dechex($c_len));//转换成四位十六进制的数
		$verifycode=$this->Lowermd5("YW*$wbid*$number*$content_len*SETmybhkj@ncyely@wyy_20170102150500");//验证密码
		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)or die("Could not create    socket\n"); // 创建一个Socket
		$connection = socket_connect($socket, 'nc.galbs.cn', 2048) or die("Could not connet server\n");    //  连接
		$msg="[YW*$wbid*$number*$content_len*".$str.','.$verifycode."]\n";//传输的数据
		socket_write($socket, $msg) or die("Write failed\n"); // 数据传送 向服务器发送消息
		while ($buff = @socket_read($socket, 1024, PHP_NORMAL_READ)) {
			echo("Response was:" . $buff . "\n");
		}
		socket_close($socket);
	}
	
	//获取小写16为MD5加密
	public function Lowermd5($str){
		$p_str=md5($str);
		return substr($p_str,8,16);
	}
	
	
	
	
	
	
	
	
	
	//测试
	public function ceshi(){
		//$smsapi="http://dx110.ipyy.net/sms.aspx";
		$userid="123";//企业ID
		$account="GYKJ002";//账号
		$password=strtoupper(md5('GYKJ00236'));//密码
		$mobile='13076050636';//家长电话
		$content="腕表位置丢失！！,请速速查看！！【游乐园】";//内容
		$sendurl=$smsapi."?action=send&userid=".$userid."&account=".$account."&password=".$password."&mobile=".$mobile."&content=".$content."&sendTime=&extno=";
		
		$result =file_get_contents($sendurl) ;
		
		$arr=simplexml_load_string($result);
		if($arr->returnstatus=='Success'){
			echo "send $mobile success!";
		}else{
			echo "send $mobile Fails!";
		}
	}
	
	
	
	
	
	
	
	
	
	
	
	
}



















