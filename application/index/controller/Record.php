<?php
namespace app\index\controller;
use think\Controller;
use think\Db;
//购买记录
class Record extends Controller
{
    public function index()
    {
        //如果已经登录
        if (session('?uid'))
        {
            $record=Db::table('records')->join('items','items.iid=records.iid')->where('records.uid_buy',session('uid'))->whereOr('records.uid_sale',session('uid'))->order('time','desc')->paginate(9,false,['query' => request()->param()]);
            foreach($record as $row){
            $user_buy0=Db::table('users')->where('uid',$row['uid_buy'])->column('uuser');
            $user_buy[$row['iid']]=$user_buy0[0];
            $user_sale0=Db::table('users')->where('uid',$row['uid_sale'])->column('uuser');
            $user_sale[$row['iid']]=$user_sale0[0];
            }
            //$user_buy=Db::table('users')->where('uid',$record[])
            $page=$record->render();
            $recordCount=Db::table('records')->where('uid_buy',session('uid'))->whereOr('uid_sale',session('uid'))->count();
            return view('record',['record'=>$record,'page'=>$page,'recordCount'=>$recordCount,'user_buy'=>$user_buy,'user_sale'=>$user_sale]);
        }

        //如果未登录，则跳转到login/index登录界面
        else
        {
            return $this->error('清先登录您的账户',"login/index");
        }
        
    }
}