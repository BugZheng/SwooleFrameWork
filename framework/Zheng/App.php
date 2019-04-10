<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/8
 * Time: 10:28
 */

namespace  Zheng;

use Zheng\Core\Http;
use Swoole\Http\Server;
use Zheng\Core\Route;

class  App{

    protected $server;

    /**
     * 定义根目录
     * */
    public static  $frameworkDirPath;
    public static  $applicationPath;

    /**
     * 定义热重启监视的目录
     */

     protected  $watchDir;

    /**
     * 记录文件散列值
     */
    protected    $md5File;

    protected  $route;


    public  function  run(){

        self::$frameworkDirPath = dirname(__DIR__);
        self::$applicationPath= dirname(self::$frameworkDirPath).'/application';
        $this->watchDir = [self::$frameworkDirPath,self::$applicationPath];

        /**
         * 初始化监视文件的散列值
         */

        $this->md5File = $this->getMd5();

        $this->server = new Server('0.0.0.0',9800);
        $this->server->set([
            'worker_num'=>2,
        ]);
        $this->server->on('request',[$this,'request']);
        $this->server->on('start',[$this,'start']);
        $this->server->on('workerStart',[$this,'workerStart']);
        $this->server->start();
    }

    public function request($request, $response){

    }
    public  function  start($server){

        echo "HttpServer 服务已经启动,监听的端口号是：9800" ;
        /**
         * 由于的Swoole 常驻内存的，所以传统的php 开发模式的就无法在这里的使用
         * 即：这里的就没有那种使用动态脚本开发的感觉
         * 故：实现热重启的功能，提高的在开发环境的效率
         */
        $this->reload();
    }

    public  function  workerStart($server,$worker_id){
            echo "我worker进程启动啦啦啦啦啦啦啦啦啦啦啦啦";

    }
    /**
     *加载热重启(比对文件的散列值)
     */

    public function  reload(){
        swoole_timer_tick(3000,function (){
            /**
             * 如果文件的散列值跟上一次的发生了变化，则重启服务
            */
           if($this->md5File !== self::getMd5()){
                $this->server->reload();
                $this->md5File=self::getMd5(); //修改初始化md5
            }

        });
    }
    public  function getMd5(){
        $md5='';
        //3秒钟之内去比较当前的文件散列值跟上一次文件的散列值
        foreach ($this->watchDir as $dir){
            $md5.=self::md5File($dir);
        }
        return $md5;
    }

    public static  function md5File($dir){

        //遍历文件夹当中的所有文件,得到所有的文件的md5散列值
        if (!is_dir($dir)) {
            return '';
        }
        $md5File = array();
        $d       = dir($dir);
        while (false !== ($entry = $d->read())) {
            //递归判断的目录下文件的散列值的变化
            if ($entry !== '.' && $entry !== '..') {
                if (is_dir($dir . '/' . $entry)) {
                    //递归调用
                    $md5File[] = self::md5File($dir . '/' . $entry);
                } elseif (substr($entry, -4) === '.php') {
                    $md5File[] = md5_file($dir . '/' . $entry);
                }
                $md5File[] = $entry;
            }
        }
        $d->close();
        return md5(implode('', $md5File));
    }


}
