<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
class Category extends Controller
{
    public function index()
    {
        if (session('?admin_name'))
        {
            $category=Db::table('category')->column('cname,cid');
            return view('Category-index',['category'=>$category]);
        }
        else
        {
            $this->redirect('login/index');
        }
    }
    public function addCategory()
    {
        if (session('?admin_name'))
        {
            if(input('?post.sm'))
            {
                $cname=input('post.cname');
                $pcname=input('post.pcname');
                $data=['cname'=>$cname,'pcname'=>$pcname];
                $msg=Db::table('category')->insert($data);
                if ($msg)
                {
                    return $this->success('添加商品分类成功','category/index')  ;
                }
                else
                {
                    return $this->error('添加商品分类失败','category/index')   ;
                }
            }
            else
            {
                $pcategory=Db::table('category')->distinct(true)->column('pcname');
                return view('Category-add',['pcategory'=>$pcategory]);
            }
        }
        else
        {
            $this->redirect('login/index');
        }       
    }
    public function delCategory()
    {
        $cid = input('get.cid');
        $msg1=Db::table('category')->where('cid',$cid)->delete();
        $msg2=Db::table('items')->where('cid',$cid)->delete();
        session('count',0);
        session('sum',0);
        echo json_encode($msg1+$msg2);
    }
}