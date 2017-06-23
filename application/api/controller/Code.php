<?php
namespace app\api\controller;

use think\Controller;
class Code extends Controller
{
    public function index()
    {
        $captcha = new \org\Captcha(config('captcha'));
        $captcha->entry();
    }
}