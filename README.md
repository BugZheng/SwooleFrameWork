# SwooleFrameWork

This is a php framework by Swoole. It some part is better than nginx  but  can't absolutly replace nginx, such as requiring css,js,,html,img 
and so on.. It can replace some part of nginx and all fpm ,It is good for api or  combines with nginx for traditionnal Business development.

Be careful,It is not suitable for production environment at present, if you do it, Sorry ,I will be Not responsible for you.

Finally , Welcome to join this project.

基于Swoole 实现高性能PHP框架

Swoole 实现的HttpServer 性能相当的强悍，但是不能完全替代掉nginx的存在，比如对静态文件的加载部分，因此本框架只代替 nginx的部分功能和fpm。
最佳的适用场景是api接口的开发，或者结合nginx的代理，做传统的业务开发。

由于Swoole 的代码常驻内存的，因此每次的修改代码必须重启服务，代码才能生效。
本框架为了提高开发者在开发环境的开发效率，实现了热重启的功能。即在开发模式，无感知重启服务。

引入了composer，实现了自动加载功能,可以根据需要自行引入框架所需的组件.
