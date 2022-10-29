<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
//商品详情界面
class Productdetail extends Controller
{
    public function index()
    {
        $iid=input('get.iid');
        $data=Db::table('items')->where('iid',$iid)->find();
        $data['idescription']=explode('+',$data['idescription']);
        $related=[];
        return view('productdetail',['data'=>$data,'related'=>$related]);
    }
    
    /*本来有个加入购物车按钮，注释掉了
    public function addTocart()
    {
        if(input('?post.add')){
            if (session('?mid'))//已经登录
            {
                $num=input('post.num');
                $gid=input('post.gid');
                $time= date('Y-m-d H:i:s');
                $mid=session('mid');
                $cart=Db::table('itcast_shopcart')->insert(['num'=>$num,'mid'=>$mid,'gid'=>$gid,'addTime'=>$time]);
                if ($cart)
                {
                    //                  获得购物车商品数量和总价
                    $sum=0;
                    $count=0;
                    $freight=Db::table('itcast_address')->where('mid',session('mid'))->column('freight');
                    $count=Db::table('itcast_shopcart_goods')->where('mid',session('mid'))->count();
                    $S=Db::table('itcast_shopcart_goods')->where('mid',session('mid'))->column('price,num');
                    foreach ($S as $key=>$value)
                    {
                        $sum+=$key*$value;
                    }
                    $sum+=$count*$freight[0];
                    session('count',$count);//商品种类
                    session('sum',$sum);//总价
                    return $this->redirect('shopcart/index');
                }
                else
                {
                    return $this->error('加入购物车失败',"index");
                }
            }
            else
            {
                return $this->error('清先登录您的账户',"login/index");
            } 
        }
    }*/
}