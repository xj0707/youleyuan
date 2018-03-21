<?php
namespace app\admin\controller;
use app\admin\controller\Common;
use think\Db;
class Admin extends Common{
	
	public function index(){
		return $this->fetch();
	}
	
	public function map(){
		return $this->fetch();
	}
	
	public function watchUnbind(){
		return $this->fetch();
	}
	
	public function watchBind(){
		return $this->fetch();
	}
	
	public function messageDispose(){
		return $this->fetch();
	}
	
	public function GPSlocation(){
		return $this->fetch();
	}

	public function getUdInfo(){
		$wb=input('post.wbid');
		if(!$wb){
			return $this->fetch();
		}
		$res=Db::name('wbtime')->where('wbid',$wb)->order('id desc')->limit(20)->select();
		$this->assign('res',$res);
		return $this->fetch('udinfo');
	}
}