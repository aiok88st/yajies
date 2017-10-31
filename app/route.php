<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

Route::get('index','home/Index/index');
Route::get('exhibition','home/Exhibition/index');
Route::get('shop','home/Shop/index');
Route::get('contact','home/Contact/index');
Route::get('products/[:catid]','home/Products/index',[],['catid'=>'\d+']);
Route::get('about','home/about/index');
Route::post('product/:catId','home/index/product',[],['catId' => '\d+']);
Route::post('detail/:id','home/exhibition/detail',[],['id' => '\d+']);
Route::post('products/[:catId]','home/Products/product',[],['catId' => '\d+']);

Route::get('ch/index$','ch/Index/index');
Route::get('ch/exhibition$','ch/Exhibition/index');

Route::get('ch/detail/:id$','ch/exhibition/detail',[],['id' => '\d+']);

Route::get('ch/shop$','ch/Shop/index');
Route::get('ch/contact$','ch/Contact/index');
Route::get('ch/products/[:catid]$','ch/Products/index',[],['catid'=>'\d+']);
Route::get('ch/about$','ch/about/index');

Route::post('ch/product/:catId','ch/index/product',[],['catId' => '\d+']);
Route::post('ch/detail/:id','ch/Exhibition/detail',[],['id' => '\d+']);
Route::post('ch/products/[:catId]','ch/Products/product',[],['catId' => '\d+']);


