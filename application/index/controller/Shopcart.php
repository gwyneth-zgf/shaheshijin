<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
//购物车 http://localhost:8080/mine/public/index.php/index/shopcart/index.html
//打算做成 显示正在进行中的项目
class Shopcart extends Controller
{
    public function index()
    {
        //若已经登录：
        if (session('?uid')){
            $cart=Db::table('items')->where('uid',session('uid'))->select();
            $discount=Db::table('users')->where('uid',session('uid'))->select();//折扣。先用uid代替了，后面改掉
            $len=count($discount);
            for($i=0;$i<count($cart);$i++)
            {
                if($len)
                {
                    for($j=0;$j<count($discount);$j++)
                    {
                        if ($cart[$i]['iid']==$discount[$j]['uid'])
                        {
                            $cart[$i]['iid']=$discount[$j]['uid'];
                            $len=$len-1;
                            break;
                        }
                        else
                        {
                            $cart[$i]['iid']=0;
                        }
                    }
                }
                else
                {
                    $cart[$i]['discount']=0;
                }
            }

            return view("shopcart",["cart"=>$cart]);
        }

        //若为登录，跳转登录
        else{
            return $this->redirect('login/index');
        }
        
    }
    
    public function delcart()
    {
        $gid = input('get.iid');
        $mid = input('get.uid');
        $addTime=input('get.addTime');
        
        /* 获得购物车商品数量和总价 */
        $sum=0;
        $count=0;

        session('count',$count);//商品种类数目
        session('sum',$sum);//总价
        $msg=[$count,$sum];
        echo json_encode($msg);
    }
    
    public function purchase()
    {
        if (session('?mid')){
            return view("purchase");
        }
        else{
            return $this->redirect('login/index');
        }   
    }
    public function dopurchase()
    {
      
        $iid = input('get.iid');
        $uid = input('get.uid');
        $price = input('get.price');
        $num= input('get.num');
        $time= date('Y-m-d H:i:s');
        $data = ['uid_buy'=>$uid,'iid'=>$iid,'time'=>$time,'iprice'=>$price];
        $msg=Db::table('records')->insert($data);
        
        session('count',0);
        session('sum',0);
        echo json_encode($msg);
    }
}