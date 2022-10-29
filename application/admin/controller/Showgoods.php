<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
class Showgoods extends Controller
{
    public function index()
    {
        if (session('?admin_name'))
        {
            $category=Db::query('SELECT DISTINCT pcname FROM category;');
            foreach ($category as $row){
                $kind=Db::query('SELECT cname,cid FROM category where pcname=:pcname;',["pcname"=>$row['pcname']]);
                $kinds[$row['pcname']]=$kind;
            }
            $cid = input('get.cid');
            $order=input('get.order');//排序方式，1默认，2价格降序，3价格降序，4销量降序
            
            if($cid)
            {
                if(is_numeric( $cid ) )
                {
                    $goods=Db::table('items')->where('cid',$cid);
                }
                else
                {
                    $goods=Db::table('items');
                }
            }
            
            else
            {
                $goods=Db::table('items');
            }
            
            switch ($order){
                case '1':
                    $goods=$goods->order('turn desc')->paginate(9,false,['query' => request()->param()]);
                    break;
                case 2:
                    $goods=$goods->order('iprice asc')->paginate(9,false,['query' => request()->param()]);
                    break;
                case 3:
                    $goods=$goods->order('iprice desc')->paginate(9,false,['query' => request()->param()]);
                    break;
                
                default:
                    $goods=$goods->paginate(9,false,['query' => request()->param()]);
                    break;
            }
            $page=$goods->render();
            return view('Goods-index',['goods'=>$goods,'page'=>$page,'kinds'=>$kinds]);
        }
        else
        {
            $this->redirect('login/index');
        }
        
    }


    public function showDetails()
    {
        $iid=input('get.iid');
        $goods=Db::table('items')->where('iid',$iid)->find();
        $uname=Db::table('users')->where('uid',$goods['uid'])->column('uname');
        return view('showdetails',['goods'=>$goods,'uname'=>$uname]);
        
        /*if (session('?admin_name'))
        {
            $iid=input('get.iid');
            $goods=Db::table('items')->where('iid',$iid)->select();
        $uname=Db::table('users')->where('uid',$goods[0]['uid'])->column('uname');
            return view('showdetails',['goods'=>$goods,'uname'=>$uname]);
        }
        else
        {
            $this->redirect('login/index');
        }*/
        
    }

    /*删除文件夹以及其下的所有文件  */
    public function deldir($dir) {
        //先删除目录下的文件：
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if($file != "." && $file!="..") {
                $fullpath = $dir."/".$file;
                if(!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    //deldir($fullpath);
                }
            }
        }
        closedir($dh);
        
        //删除当前文件夹：
        if(rmdir($dir)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function delGoods()
    {
        $iid = input('get.iid');
        $d=Db::table('items')->where('iid',$iid)->column('img1');
        $dir=ROOT_PATH . 'public' . DS . 'static/imgs/'.$d['0'];
        $msg=Db::table('items')->where('iid',$iid)->delete();
        if(!$this->deldir($dir))
        {
            $msg=0;
        }
        echo json_encode($msg);
    }
}