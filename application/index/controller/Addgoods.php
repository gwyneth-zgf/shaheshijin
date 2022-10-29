<?php
namespace app\index\controller;
use think\Db;
use think\Controller;
use think\Exception;
class Addgoods extends Controller
{
    public function index()
    {
        if (session('?uid'))
        {
            $category=Db::query('SELECT DISTINCT pcname FROM category;');
            foreach ($category as $row){
                $kind=Db::query('SELECT cname,cid FROM category where pcname=:pcname;',["pcname"=>$row['pcname']]);
                $kinds[$row['pcname']]=$kind;
            }
            if(input('?post.sm'))
            {
                $iid0=Db::query("SELECT MAX(iid) FROM items ");
                $iid=$iid0[0]['MAX(iid)']+1;
                $iname=input('post.iname');
                $price=(int)input('post.price');
                $status=input('post.status');
                $description=input('post.description');
                $cid=input('post.cid');
                switch ($cid)
                {
                    case 1: $img_pre='ershou/books/';$finame='二手交易';$siname='书籍';break;
                    case 2: $img_pre='ershou/cloth/';$finame='二手交易';$siname='服饰';break;
                    case 3: $img_pre='ershou/food/';$finame='二手交易';$siname='零食';break;
                    case 4: $img_pre='ershou/makeup/';$finame='二手交易';$siname='化妆品';break;
                    case 5: $img_pre='huli/speeches';$finame='资源互利';$siname='门票';break;
                    case 6: $img_pre='huli/movies';$finame='资源互利';$siname='影票';break;
                    case 7: $img_pre='huli/courses';$finame='资源互利';$siname='退课选课';break;

                }
                $img1=$img_pre.$iid;
                $data=['iname'=>$iname,'iprice'=>$price,'status'=>$status,
                    'idescription'=>$description,'cid'=>$cid,'iid'=>$iid,'img1'=>$img1];
                try {
                       $msg=Db::table('items')->where('iid',$iid)->insert($data);
                }
                catch (Exception $e){
                    echo "该商品已经添加";
                    return $this->redirect('__ROOT__/index.php/index/shopcart/index.html',['iid'=>$iid]);
                }
                if ($msg)
                {
                    $files = request()->file('img');
                    $i=1;
                    foreach($files as $file){
                        // 移动到框架应用根目录/public/static/imgs/ 目录下
                        /*$ext=$file->getExtension();
                        //$newname=$iid.'-'.$i.'.'.$ext;
                        $oldname=$_FILES['img']['tmp_name'][$i-1];
                        rename($oldname,$i);*/
                        
                        $info = $file->validate(['size'=>4194304,'ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'static/imgs/'.$img1,'');

                        $i++;
                        if(!$info){
                            return $this->error($file->getError(),'showgoods/showdetails');
                        }

/*
                        $fileName=$_FIFLES['file']['name'];//得到上传文件的名字
                        $name=explode('.',$fileName);//将文件名以'.'分割得到后缀名,得到一个数组
                        $newPath=$date.'.'.$name[1];//得到一个新的文件为'20070705163148.jpg',即新的路径
                        $oldPath=$_FILES['file']['tmp_name'];//临时文件夹,即以前的路径
                        rename($oldPath,$newPath); 就可以重命名了!*/

                    }
                    return $this->success('添加商品成功','addgoods/index')  ;
                }
                else
                {
                    return $this->error('添加商品失败','addgoods/index')   ;
                }
            }
            else
            {
                return view('addgoods',['kinds'=>$kinds]);
            }
        }
        else
        {
            $this->redirect('login/index');
        }        
    }

}