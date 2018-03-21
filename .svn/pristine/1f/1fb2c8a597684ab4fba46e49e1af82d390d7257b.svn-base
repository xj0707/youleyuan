<?php
namespace app\admin\controller;

use think\Controller;
class Common extends Controller{
	//初始化检查
	public function _initialize(){
		if(!session('user_id')){
			$this->error('请登陆', 'login/login', '', 0);
		}
	}
}

	