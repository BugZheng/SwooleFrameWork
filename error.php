<?php

register_shutdown_function('handleFatal');
//进程关闭时触发
//发送错误信息到某个设备\发送邮件
function handleFatal(){
    $error = error_get_last(); //获取最后的错误
    if (isset($error['type'])) {
        switch ($error['type']) {
            case E_ERROR :
            case E_PARSE :
            case E_CORE_ERROR :
            case E_COMPILE_ERROR :
                $message = $error['message'];
                $file = $error['file'];
                $line = $error['line'];
                $log = "$message ($file:$line)\nStack trace:\n";
                $trace = debug_backtrace();
                foreach ($trace as $i => $t)
                {
                    if (!isset($t['file']))
                    {
                        $t['file'] = 'unknown';
                    }
                    if (!isset($t['line']))
                    {
                        $t['line'] = 0;
                    }
                    if (!isset($t['function']))
                    {
                        $t['function'] = 'unknown';
                    }
                    $log .= "#$i {$t['file']}({$t['line']}): ";
                    if (isset($t['object']) and is_object($t['object']))
                    {
                        $log .= get_class($t['object']) . '->';
                    }
                    $log .= "{$t['function']}()\n";
                }
                if (isset($_SERVER['REQUEST_URI']))
                {
                    $log .= '[QUERY] ' . $_SERVER['REQUEST_URI'];
                }
                file_put_contents(__DIR__.'/log.log',$log);//将错误写进当前的路径的log.log文件
                return $log;
                //echo $log;
            default:
                break;
        }
    }
}