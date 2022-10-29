<?php
namespace app\index\controller;
use think\Controller;
use think\captcha\Captcha;
use think\Db;
//登录界面
class Login extends Controller
{
    public function index()
    {
        //是否创建一个用户？
        if (input('?post.create')){
            return $this->redirect('register/index',['email'=>input('post.email1')]);
        }//redirect:重定向.重定向到register/index，传入参数'email'=>input('post.email1')

        elseif(input('?post.sm')){
            $email=input('post.email2');
            $pwd=input('post.pwd');
            $info=Db::query("select uid,uuser from users where uemail=:email and upwd=:pwd ",["email"=>$email,'pwd'=>$pwd]);
            $cp=1;
            //cap,cp,captcha等与验证码有关，图没弄好，先注释掉，$cp恒等于1
            //$cap=input('post.captcha');
            //$cp=$this->check_verify($cap);
            
            //如果搜不到该用户，登陆失败
            if(empty($info[0])){
                return $this->error('登陆失败',"index");
            }

            //验证验证码,登陆 获取该用户信息
            else{
                
                if($cp){
                    session('uuser',$info[0]['uuser']);
                    session('uid',$info[0]['uid']);
                    //获得购物车商品数量和总价
                    $sum=0;
                    $count=0;

                    $count=Db::table('records')->where('uid_buy',session('uid'))->whereOr('uid_sale',session('uid'))->count();//作为买方或卖方的交易总数，后期打算分开写
                    $id=Db::table('records')->where('uid_buy',session('uid'))->whereOr('uid_sale',session('uid'))->column('iid');
                    
                    //计算出有相关交易的每件商品价格总和sum
                    foreach ($id as $iid){
                        $price=Db::table('items')->where('iid',$iid)->column('iprice');
                        $sum+=$price[0];
                    }

                    session('count',$count);  //作为买方/卖方的交易次数
                    session('sum',$sum);  //总交易额
                    
                    //原来是跳转到购物车 return $this->success('登陆成功','shopcart/index');
                    return $this->success('登陆成功','index/index');//登陆成功，跳转到http://localhost:8080/mine/public/index.php/index/index/index.html
                }
                else {
                    return $this->error('登陆失败,验证码错误',"index");
                }
            }
        }

        elseif(session('?uuser'))
        {
            if (!session('?uid') or session('uuser')=='请登陆'){
                return view("login");
            }
            else{
                return $this->redirect('shopcart/index');
                //return $this->success('登陆成功','index/index');
            }
        }
        else{
            return view("login");
        }
    }
    
    public function logout()//退出登录
    {
        session('uid', null);
        session('uuser','请登陆');
        return $this->redirect('index/index');
    }
    
    //和验证码有关
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