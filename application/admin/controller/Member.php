<?php
namespace app\admin\controller;
use think\Db;
use think\Controller;
class Member extends Controller
{
    public function index()
    {
        if (session('?admin_name'))
        {
            $members=Db::table('users')->paginate(9,false,['query' => request()->param()]);
            $page=$members->render();
            return view('Member-index',['members'=>$members,'page'=>$page]);
        }
        else
        {
            $this->redirect('login/index');
        }
    }
    public function detail()
    {
        $uid=input('get.uid');
        $member=Db::table('users')->where('users.uid',$uid)->find();
        $record=Db::table('records')->join('items','items.iid=records.iid')->where('records.uid_buy',$uid)->whereOr('records.uid_sale',$uid)->order('time','desc')->paginate(9,false,['query' => request()->param()]);
        $uname=[];
        
        
        foreach($record as $row){

            //作为买方
            if($uid==$row['uid_buy']){
                $uname[$row['iid']]['buy']='*';

                if($row['status']=='yes'){
                    $uname[$row['iid']]['sale']='暂无';
                }
                else{
                    $uname[$row['iid']]['sale']=(Db::table('users')->where('uid',$row['uid_sale'])->column('uname'))[0];
                }
            }
            //作为卖方
            else{
                $uname[$row['iid']]['sale']='*';

                if($row['status']=='yes'){
                    $uname[$row['iid']]['buy']='暂无';
                }
                else{
                    $uname[$row['iid']]['buy']=(Db::table('users')->where('uid',$row['uid_buy'])->column('uname'))[0];
                }
            }
        }

        $page=$record->render();
        return view('Member-detail',['member'=>$member,'record'=>$record,'uname'=>$uname,'page'=>$page]);
    }
    
}