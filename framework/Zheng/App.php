<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9
 * Time: 10:28
 */

namespace  Zheng;
use Zheng\Core\httpServer;

class  App{

    public  function  run(){
        //Macaw::dispatch();

        define('ROOT_PATH',dirname(dirname(__DIR__)));
        define('FRAME_PATH',ROOT_PATH.'/framework');
        define('APP_PATH',ROOT_PATH.'/application');
        define('CONFIG_PATH',APP_PATH.'/config');

        $httpServer = new httpServer();
        $httpServer->run();
    }
}
