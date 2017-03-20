<?php
//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$url = "http://lianhanghao.com/index.php/Index/index/bank/%u/province/%u/city/%u/p/%u.html";
$error_p = $redis->get("error_p");
$error_p = json_decode($error_p, true);
$ekeys = array();//array("1-20-258","1-31-344");

foreach ($ekeys as $ek) {
    list($bCode, $pCode, $cCode) = explode('-', $ek);
    $ep = sprintf($url,$bCode,$pCode,$cCode,1);
    $error_p[] = array($ek, $ep);
}
// echo count($error_p);
// var_dump($error_p);
// die();
$errors = array();
foreach ($error_p as $page) {
    $key_str = $page[0];
    $url = $page[1];
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,$url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
    $html = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($html)) {
        $errors[] = array($key_str, $url);
        continue;
    }

    // echo $key_str;
    // echo '<br/>';
    // echo $html;
    // die();
    $redis->set($key_str.'_p1', $html);

    // memcache_add($memcache_obj, $key_str.'_p1', $html, false, 0);
    // $redis->set('p_count', $i+1);
}
$error_p = $redis->set("error_p", json_encode($errors, JSON_UNESCAPED_SLASHES));
