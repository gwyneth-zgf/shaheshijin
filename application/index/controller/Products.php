<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
//多个商品展示的界面 如： http://localhost:8080/mine/public/index.php/index/products?key=&factor=所有&search=
class Products extends Controller
{
    public function index()
    {
        $cid = input('get.cid');
        $order=input('get.order');//排序方式，1默认，2价格降序，3价格降序，4销量降序（因为没有销量，所以4先写成和3一样
        
        //如果在搜索框内搜索.key对应搜索的关键字，factor对应检索栏的选项
        if(input('?get.search'))
        {
            $key=input('get.key');
            $factor=input('get.factor');
            $products=$this->findGoodsbyname($key,$factor);
        }
        //如果没有，显示所有
        else
        {
            $products=Db::table('items')->where('status','yes');
        }

        //对应左侧的分类栏，如果有选择分类：
        if($cid)
        {
            $cname=Db::table('category')->where('cid',$cid)->column('cname');
            $product=$products->where('siname',$cname[0]);
        }
        //没有选择分类：
        else
        {
            $product=$products;
        }
        
        //排序方式
        switch ($order){
            case 1:
                $products=$product->paginate(9,false,['query' => request()->param()]);
                break;
            case 2:
                $products=$product->order('iprice asc')->paginate(9,false,['query' => request()->param()]);
                break;
            case 3:
                $products=$product->order('iprice desc')->paginate(9,false,['query' => request()->param()]);
                break;
            case 4:
                $products=$product->order('iprice desc')->paginate(9,false,['query' => request()->param()]);
                break;
            default:
                $products=$product->paginate(9,false,['query' => request()->param()]);
                break;
        }
        $page = $products->render();
        return view('products',['products'=>$products,'page'=>$page]);
    }
    
    
    public function findGoodsbyname($key,$factor)
    {
        if($factor=='所有')
        {
            $products=Db::table('items')->where('iname', 'like', '%'.$key.'%' )->where('status','yes');
        }
        else
        {
            $products=Db::table('items')->where('iname', 'like', '%'.$key.'%' )->where('finame',$factor)->where('status','yes');
        }
        return $products;
    }
}