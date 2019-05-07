<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/10
 * Time: 15:30
 */

namespace Zheng\Core;


class Config
{

    /**
     * @var 配置map
     */
    public static $configMap = [];
    private static $instance;

    private function __construct ()
    {
    }

    public static function  get_instance(){
        if(is_null (self::$instance)){
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     *  Swoole的载入有效的文件是在 进程的start之前 ,所以之后的文件不支持热加载
     */
    public static function load()
    {
        self::$configMap = require CONFIG_PATH. '/default.php';
    }

    /**
     * 载入配置文件,可以热加载
     * 默认是application/config
     */
    public static function loadLazy()
    {
        $files = glob(CONFIG_PATH."/*.php");

        if (!empty($files)) {

            foreach ($files as $dir => $fileName) {
                //var_dump($fileName);
                self::$configMap +=  include "{$fileName}";
            }
        }
    }

    /**
     * @desc 读取配置
     * @return string|null
     */
    public static function get($key)
    {
        if (isset(self::$configMap[$key])) {
            return self::$configMap[$key];
        }
        return false;
    }

}