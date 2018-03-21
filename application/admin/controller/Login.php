<?php
namespace app\admin\controller;

use think\Controller;
use think\Db;
/**
 * 登录
 */
class Login extends Controller{
	//登录页面
	public function login(){
		return $this->fetch();
	}
	//登录验证
	public function loginCheck(){
		$username=input('post.username');
		$password=input('post.password');
		if(!$username){
			$this->error('用户名不能为空');
		}
		if(!$password){
			$this->error('密码不能为空');
		}
		$info=Db::name('admin')->where('username',$username)->find();
		if(!$info){
			$this->error('用户名或密码错误');
		}
		if(md5($password)!=$info['password']){
			$this->error('用户名或密码错误');
		}else{
			session('user_name', $info['username']);
            session('user_id', $info['id']);
            $this->success('登录成功',url('admin/index'),'',1);
		}
	}
	//登录退出
	public function outlogin(){
		session('user_name', null);
		session('user_id', null);
		$this->success('退出成功', 'login/login','',1);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}