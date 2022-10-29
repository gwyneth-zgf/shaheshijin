<?php
namespace app\index\controller;
//信息->常见问题 http://localhost:8080/mine/public/index.php/index/faq
class Faq
{
    public function index()
    {
        return view('faq');
    }
}