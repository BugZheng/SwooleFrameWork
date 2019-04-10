<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/9
 * Time: 9:50
 */

namespace Zheng\Core;


class Route
{
    // 路由处理
    public function route()
    {
        $controllerName = $this->config['defaultController'];
        $actionName = $this->config['defaultAction'];
        $param = array();

        $url = $_SERVER['REQUEST_URI'];
        // 清除?之后的内容
        $position = strpos($url, '?');
        $url = $position === false ? $url : substr($url, 0, $position);
        // 删除前后的“/”
        $url = trim($url, '/');

        if ($url) {
            // 使用“/”分割字符串，并保存在数组中
            $urlArray = explode('/', $url);
            // 删除空的数组元素
            $urlArray = array_filter($urlArray);

            // 获取控制器名
            $controllerName = ucfirst($urlArray[0]);

            // 获取动作名
            array_shift($urlArray);
            $actionName = $urlArray ? $urlArray[0] : $actionName;

            // 获取URL参数
            array_shift($urlArray);
            $param = $urlArray ? $urlArray : array();
        }

        // 判断控制器和操作是否存在
        $controller = 'app\\controllers\\'. $controllerName . 'Controller';
        if (!class_exists($controller)) {
            exit($controller . '控制器不存在');
        }
        if (!method_exists($controller, $actionName)) {
            exit($actionName . '方法不存在');
        }

        // 如果控制器和操作名存在，则实例化控制器，因为控制器对象里面
        // 还会用到控制器名和操作名，所以实例化的时候把他们俩的名称也
        // 传进去。结合Controller基类一起看
        $dispatch = new $controller($controllerName, $actionName);

        // $dispatch保存控制器实例化后的对象，我们就可以调用它的方法，
        // 也可以像方法中传入参数，以下等同于：$dispatch->$actionName($param)
        call_user_func_array(array($dispatch, $actionName), $param);
    }

    // 检测开发环境
    public function setReporting()
    {
        if (APP_DEBUG === true) {
            error_reporting(E_ALL);
            ini_set('display_errors','On');
        } else {
            error_reporting(E_ALL);
            ini_set('display_errors','Off');
            ini_set('log_errors', 'On');
        }
    }

    // 删除敏感字符
    public function stripSlashesDeep($value)
    {
        $value = is_array($value) ? array_map(array($this, 'stripSlashesDeep'), $value) : stripslashes($value);
        return $value;
    }


}