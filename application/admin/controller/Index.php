<?php
namespace app\admin\controller;
use think\Controller;
class Index extends Controller
{
    public function index()
    {
        if (session('?admin_name'))
        {
            return view('index');
        }
        else
        {
            $this->redirect('login/index');    
        }
    }
}