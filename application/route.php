<?php

use  \Zheng\Core\Route;

Route::get('/test',function(){
     return   324;
});

Route::get('/abc','index@index');


Route::post('/abc','index@test');

