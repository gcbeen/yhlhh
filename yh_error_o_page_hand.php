<?php
//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$url = "http://lianhanghao.com/index.php/Index/index/bank/%u/province/%u/city/%u/p/%u.html";
$error_p = $redis->get("error_po");
$error_p = json_decode($error_p, true);
// $ekeys = array();//"1-20-258","1-31-344" // "1-20-258_p11"
// foreach ($ekeys as $ek) {
//     list($bCode, $pCode, $cCode) = explode('-', $ek);
//     $ep = sprintf($url,$bCode,$pCode,$cCode,1);
//     $error_p[] = array($ek, $ep);
// }
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
    // memcache_add($memcache_obj, $key_str.'_p1', $html, false, 0);
    $redis->set($key_str, $html);
    // $redis->set('p_count', $i+1);
}

$redis->set("error_po", json_encode($errors, JSON_UNESCAPED_SLASHES));

