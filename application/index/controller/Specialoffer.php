<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
//特价优惠。看看之后能不能改成别的板块，暂且放着
class Specialoffer extends Controller
{
    public function index()
    {
        $products=Db::table('items')->where('uid%2=0')->paginate(9,false,['query' => request()->param()]);
        $page = $products->render();
        return view('specialoffer',['products'=>$products,'page'=>$page]);
    }
}