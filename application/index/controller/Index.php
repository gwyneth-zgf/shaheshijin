<?php
namespace app\index\controller;
use think\Db;
//主页 http://localhost:8080/mine/public/index.php/index/
class Index
{
    public function index()
    {
        //从表category中获得一级分类pcname,二级分类cname
        $category=Db::query('SELECT DISTINCT pcname FROM category;');
        foreach ($category as $row){
            $kind=Db::query('SELECT cname,cid FROM category where pcname=:pcname;',["pcname"=>$row['pcname']]);
            $kinds[$row['pcname']]=$kind;
        }
        //查看是否为登录状态
        if (empty(session('uuser'))){
            session('uuser','请登陆');//未登录，则用户名设为“请登录”（页面左上角）
        }
        if (!session('?uid')){
            session('sum',0);
            session('count',0);
        }
        session('kinds',$kinds);
        //获得新品在售 和推荐商品
        $newproduct=$this->newProduct();//新品在售
        $recommendproduct=$this->recommendProduct();//推荐商品（之前是精选商品$featureproduct,所以对应html文件里有的标签里还是feature作为名字
        return view("Index",['new'=>$newproduct,'recommend'=>$recommendproduct]);
        //////////意思是用Index模型渲染，就是view文件夹下同名文件，后面是传入的参数，''当中的就是在.html文件中的名字/////////////
    }
    
    public function newProduct()//获取前三新品
    {
        $product=Db::table('items')->where('status','yes')->order('turn','desc')->limit(3)->column('iid,iname,img1,iprice');
        return $product;
    }
    public function recommendProduct()//获取recommend商品
    {
        $product=Db::table('items')->where('status','yes')->where('recommend','yes')->column('iid,iname,img1,iprice');
        return $product;
    }
}
