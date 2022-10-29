<?php
namespace app\admin\controller;
use think\Controller;
use think\captcha\Captcha;
use think\Db;
class Login extends Controller
{
    public function index()
    {
        if(input('?post.sm')){
            $name=input('post.admin_name');
            $pwd=input('post.admin_pwd');
            $cap=input('post.verify');
            $cp=$this->check_verify($cap);
            $cp=1;
            if($name=='admin'&&$pwd='123456'){
                if($cp){
                    session('admin_name',$name);
                    return $this->success('登陆成功','index/index');
                }
                else {
                    return $this->error('登陆失败,验证码错误',"index");
                }
            }
            else{
                
            }
        }
        elseif(session('?admin_name'))
        {
            if (session('admin_name')=='请登陆'){
                return view("login");
            }
            else{
                return $this->redirect('index/index');
            }
        }
        else{
            return view("login");
        }
    }
    public function logout()
    {
        session('admin_name', null);
        return $this->redirect('login/index');
    }
    public function getCaptcha()
    {
        $captcha = new Captcha();
        return $captcha->entry();
    }
    public function check_verify($code, $id = '')
    {
        $captcha = new Captcha();
        return $captcha->check($code, $id);
    }
}