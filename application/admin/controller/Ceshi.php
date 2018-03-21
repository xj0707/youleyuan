<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/2/28
 * Time: 16:02
 */
use think\Db;
class Ceshi extends \think\Controller{
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