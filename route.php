<?php
include_once 'error.php';
$http=new \swoole\http\server('0.0.0.0',9502);
$http->set([
    'package_max_length'=>1024*1024*10,
    'upload_tmp_dir'=>__DIR__.'/upload'
]);
//监听http协议
$http->on('request',function ($request,$response){
    $response->header('Content-Type','text/html');
    $response->header('Charset','utf-8');
    $server=$request->server;
    $path_info=$server['path_info'];
    if($path_info=='/'){
        $path_info='/';
    }else{
        $path_info=explode('/',$path_info);
    }
    if(!is_array($path_info)) {
        $response->status(404);
        $response->end('<meta charset="UTF-8">请求路径无效');
    }
    //模块
    $model=(isset($path_info[1]) && !empty($path_info[1]))?$path_info[1]:'Swooleman';
    //控制器
    $controller=(isset($path_info[2]) && !empty($path_info[2]))?$path_info[2]:'error';
    //方法
    $method=(isset($path_info[3]) && !empty($path_info[3]))?$path_info[3]:'index';
    //结合错误处理
    $class_name="\\{$model}\\$controller";
    if($class_name == '\favicon.ico\Index'||$class_name == '\favicon.ico\error' ){
           return ;
    }
    //判断控制器类是否存在
   if(file_exists(__DIR__.'/../'.str_replace('\\', '/', $class_name).'.php')){
       require __DIR__.'/../'.str_replace('\\', '/', $class_name).'.php';
       $obj= new $class_name;
       //判断控制器方法是否存在
       if(!method_exists($obj,$method)){
           $response->status(404);
           $response->end("<meta charset='UTF-8'>兄Dei,方法不存在，别瞎几把访问好吗");
       }else{
           //如果存在此方法，返回结果，return 无效
           $response->end($obj->$method());
       }
   }else{
       $response->status(404);
       $response->end("<meta charset='UTF-8'>兄Dei,类不存在，别瞎几把访问好吗");
   }
});
//启动http服务器
$http->start();




