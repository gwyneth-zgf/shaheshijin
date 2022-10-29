<?php
namespace app\index\controller;
//信息->法律信息 http://localhost:8080/mine/public/index.php/index/legalnotice
class Legalnotice
{
    public function index()
    {
        return view('legalnotice');
    }
}