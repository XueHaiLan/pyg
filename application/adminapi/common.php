<?php

//密码加密
if(!function_exists('enctypt_password')){
    function enctypt_password($pas){
        $key='dfvtsdfhvrejflkm';
        return md5(md5(trim($pas).$key));
    }
}
