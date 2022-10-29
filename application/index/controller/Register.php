<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
use think\Exception;
//注册一个账号
class Register extends Controller
{
    public function index($email='')
    {
        if(input('?post.register')){
            $uid0=Db::query("SELECT MAX(uid) FROM users ");
            $uid=$uid0[0]['MAX(uid)']+1;
            $user=input('post.user');
            $email=input('post.email');
            $pwd=input('post.pwd');
            $name=input('post.name');
            $add=input('post.add');
            $number=input('post.number');
            $qq=input('post.qq');
            $findemail=Db::table('users')->where('uemail',$email)->select();
            
            if (!$findemail)
            {   //之后加上对姓名昵称的过滤功能
                if(is_numeric($number)&&strlen($number)==13){
                    if(is_numeric($qq)){
                        if($add=='清水河' OR $add=='沙河'){
                            if(filter_var($email, FILTER_VALIDATE_EMAIL)){
                                $num_pre=substr($number,0,7);
                                $data=['uemail'=>$email,'uuser'=>$user,'uname'=>$name,'upwd'=>$pwd,'unumber'=>$number,'uqq'=>$qq,'uadd'=>$add,'unumber_pre'=>$num_pre];
                                $info=Db::table('users')->insert($data);
                                if($info==1){
                                    return $this->success('注册成功，请登录',"login/index");
                                }
                                else{
                                    return $this->error('注册失败',"index");
                                }
                            }
                            else{
                                return $this->error('请使用正确的邮箱格式',"index");
                            }

                        }
                        return $this->error('所填校区不存在',"index");

                    }
                    return $this->error('QQ号格式错误',"index");

                }
                else{
                    return $this->error('学号错误',"index");
                }

            }
            else 
            {
                return $this->error('注册失败,该用户已经存在',"index");
            }
        }
        else{
            return view('register',['email'=>$email]);
        }
    }
}