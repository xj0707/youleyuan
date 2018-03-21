<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/4/1
 * Time: 9:53
 */

namespace app\admin\controller;

use think\Controller;
use think\Db;
use think\Session;
use think\Image;
class AppApi extends Controller{
    protected $beforeActionList = [
        'first'=>  ['except'=>'login,verifycode'],
    ];

    //前置方法
    protected function first(){
        if(!Session::get('user_phone')){
            return json(['code'=>-10,'message'=>'未登录访问']);
        }
    }

    /**
     * 获取验证码
     */
    public function verifycode(){
        $phone=input('post.phone');//用户电话
        if(!$phone){
            return json(['code'=>-2,'message'=>'数据不完整']);
        }
        $radio = mt_rand(100000, 999999);//随机数
        //发送短消息
        $smsapi="http://dx110.ipyy.net/sms.aspx";
        $userid="";//企业ID
        $account="GYKJ002";//账号
        $password=strtoupper(md5('GYKJ00236'));//密码
        $content="验证码:{$radio}【游乐园】";//内容
        $sendurl=$smsapi."?action=send&userid=".$userid."&account=".$account."&password=".$password."&mobile=".$phone."&content=".$content."&sendTime=&extno=";
        $result =file_get_contents($sendurl) ;
        $arr=simplexml_load_string($result);
        $send_time=date('Y-m-d H:i:s');
        if($arr->returnstatus=='Success'){
            Session::set('code',$radio);
            return json(['code'=>1,'message'=>'发送成功，请耐心等待！','info'=>$radio]);
        }else{
            return json(['code'=>-1,'message'=>'发送失败，请稍后重试！']);
        }
    }
    /**
     * 登录接口
     */
    public function login(){
        $phone=input('post.phone');//用户电话
        $code=input('post.code');
        if(!$phone || !$code){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $truecode=Session::get('code');
        if($code==$truecode){
            //Session::set('user_phone',$phone);
            $res=Db::name('user')->where('phone',$phone)->find();
            $userId=$res['id'];
            if(!$res){
                Db::name('user')->insert(['phone'=>$phone]);
                $userId = Db::name('user')->getLastInsID();
            }
            $info=[
                'userId'=>$userId,
                'phone'=>$phone
            ];
            return json(['code'=>1,'message'=>'登录成功','info'=>$info]);
        }else{
            return json(['code'=>-1,'message'=>'登录失败']);
        }
    }
    /**
     *已经绑定的腕表
     */
    public function lockwb(){
        $phone=input('post.phone');//用户电话
        $id=input('post.id');//用户id
        if(!$phone || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-2,'message'=>'非法操作']);
        }
        $watchs=Db::name('watchusage')->field('watchname')->where('w_phone',$phone)->where('state',0)->select();
        $arr=array();
        foreach($watchs as $w){
            $arr[]=$w['watchname'];
        }
        return json(['code'=>1,'message'=>'操作成功','info'=>$arr]);
    }
    /**
     * 添加 绑定的腕表
     */
    public function addwb(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        $imie=input('post.imie');//腕表imie号
        if(!$phone || !$imie || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-5,'message'=>'非法操作']);
        }
        $wbinfo=Db::name('watch')->where('wbid',$imie)->find();
        if(!$wbinfo){
            return json(['code'=>-2,'message'=>'改腕表不存在']);
        }
        if($wbinfo['status']==1){
            return json(['code'=>-3,'message'=>'改腕表已经绑定了']);
        }
        $time=time();
        $res1=Db::name('watch')->where('wbid',$imie)->update([
            'p_phone'=>$phone,
            'status'=>1,
            'lasttime'=>$time,
        ]);
        $data=[
            'watchname'=>$imie,
            'w_phone'=>$phone,
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
    /**
     * 修改性别
     */
    public function setsex(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        $sex=input('post.sex/d');//用户性别

        if(!$phone || !$sex || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $userinfo=Db::name('user')->where('phone',$phone)->find();
        if($userinfo){
            $id=$userinfo['id'];
            $res=Db::name('user')->where('id',$id)->update(['sex'=>$sex]);
            if($res){
                return json(['code'=>1,'message'=>'操作成功']);
            }else{
                return json(['code'=>-2,'message'=>'修改失败']);
            }
        }else{
            return json(['code'=>-2,'message'=>'修改失败']);
        }
    }
    /**
     * 修改上传图片（用户头像）
     */
    public function uploadpic(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        //$file=input('file.pic');//上传图片
        $file = request()->file('pic');
        if(!$phone || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }

        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $oldimage=$res['tourl'];
        // 上传图片验证
        if (true !== $this->validate(['image' => $file], ['image' => 'require|image'])) {
            return json(['code'=>-2,'message'=>'图片不合法']);
        }
        // 读取图片
        $image = \think\Image::open($file);
        // 保存图片（以当前时间戳）
        $rand=mt_rand('10000','99999');
        $str=time().$rand;
        $saveName = $str. '.png';
        $image->save(ROOT_PATH . 'public/uploads/' . $saveName);
        $info=Db::name('user')->where('phone',$phone)->update(['tourl'=>$saveName]);
        if($info){
            return json(['code'=>1,'message'=>'操作成功','info'=>$saveName]);
        }else{
            return json(['code'=>-4,'message'=>'操作失败']);
        }

    }
    /**
     * 获取用户性别和头像
     */
    public function getuserinfo(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        if(!$phone || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $userinfo=Db::name('user')->where('phone',$phone)->find();
        if($userinfo){
            $data=[
                'sex'=>$userinfo['sex'],
                'tourl'=>$userinfo['tourl']
            ];
            return json(['code'=>1,'message'=>'操作成功','info'=>$data]);
        }else{
            return json(['code'=>-2,'message'=>'操作失败']);
        }
    }
    /**
     * 腕表运动轨迹
     */
    public  function movepath(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        if(!$phone || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $infos=Db::name('watchusage')->field('id,watchname')->where('w_phone',$phone)->where('state',0)->select();
        $arr=array();
        $arr1=array();
        foreach($infos as $v){
            $id=$v['id'];
            $key=$v['watchname'];
            $location=Db::name('watchlocation')->field('longitude,latitude')->where('watchusage_id',$id)->order("id desc")->limit('10')->select();
            $arr[$key]=$location;
        }
        return json(['code'=>1,'message'=>'操作成功','info'=>$arr]);
    }
    /**
     * 新闻列表展示
     */
    public function newslist(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        $type=input('post.type');//用户电话
        if(!$phone || !$id || !$type){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $news=Db::name('news')->where("type",$type)->order('id desc')->limit(10)->select();
        return json(['code'=>1,'message'=>'操作成功','info'=>$news]);
    }
    /**
     * 新闻类型
     */
    public function newstype(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        if(!$phone || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $newstype=Db::name('newsType')->order('id asc')->select();
        return json(['code'=>1,'message'=>'操作成功','info'=>$newstype]);
    }
    /**
     * 首页轮播图
     */
    public function lunbo(){
        $id=input('post.id');//用户id
        $phone=input('post.phone');//用户电话
        if(!$phone || !$id){
            return json(['code'=>-1,'message'=>'数据不完整']);
        }
        $res=Db::name('user')->where('id',$id)->find();
        $truephone=$res['phone'];
        if($phone != $truephone){
            return json(['code'=>-3,'message'=>'非法操作']);
        }
        $images=Db::name('lunbo')->field('imageurl')->where('state',1)->limit(4)->select();
        $data=array();
        foreach($images as $v){
            $data[]=$v['imageurl'];
        }
        return json(['code'=>1,'message'=>'操作成功','info'=>$data]);
    }

    //测试
    public function ceshi(){
        return $this->fetch('ceshi');
    }
    public function ceshi1(){
       $s= Session::get('user');
       if($s=='13076050636'){
           return json(['code'=>2,'message'=>'121成功']);
       }else{
           return json(['code'=>-1,'message'=>'失败']);
       }
    }

}