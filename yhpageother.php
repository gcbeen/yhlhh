<?php
$url = "http://lianhanghao.com/index.php/Index/index/bank/%u/province/%u/city/%u/p/%u.html";
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$num_keys = $redis->keys('*_nu');

// 所有的页面
$check_page_keys = array(); //'1-1-35_p12'
$redis->set("check_page_keys", json_encode($check_page_keys));


$num_ary = array();// 需要请求的
$zero_page = array();//没有数据的
foreach ($num_keys as $num_key) {

    list($kCode, $o) = explode('_', $num_key);

    $check_page_keys = $redis->get("check_page_keys");
    $check_page_keys = json_decode($check_page_keys, true);
    $check_page_keys[] = $kCode.'_p1';
    $redis->set("check_page_keys", json_encode($check_page_keys));

    $num = $redis->get($num_key);
    if ($num == 0) {
        $zero_page[] = $num_key;
    }
    if ($num > 1) {
        $num_ary[$num_key] = $num;
    }
}

$redis->set('zero_page', json_encode($zero_page));

foreach ($num_ary as $k => $n) {
    list($kCode, $o) = explode('_', $k);
    list($bCode, $pCode, $cCode) = explode('-', $kCode);
    for ($i = 2; $i < $n + 1; $i++) {
        // 2..n

        $check_page_keys = $redis->get("check_page_keys");
        $check_page_keys = json_decode($check_page_keys, true);
        $check_page_keys[] = $kCode.'_p'.$i;
        $redis->set("check_page_keys", json_encode($check_page_keys));

        $p_url = sprintf($url,$bCode,$pCode,$cCode,$i);
        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL,$p_url);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
        $html = curl_exec($curl_handle);
        curl_close($curl_handle);
        // echo $html;
        if (empty($html)) {
            // $error_p[] = array($key_str, $url);
            $error_p = $redis->get("error_po");
            if (empty($error_p)) {
                $error_p = json_encode(array());
            }
            $error_p = json_decode($error_p, true);
            $error_p[] = array($kCode.'_p'.$i, $p_url);
            $error_p = json_encode($error_p);
            $redis->set("error_po", $error_p);
            continue;
        }
        // memcache_add($memcache_obj, $key_str.'_p1', $html, false, 0);
        $redis->set($kCode.'_p'.$i, $html);
        $redis->set($kCode.'p_count', $i);
    }
}


die();

// $memcache_obj = memcache_connect("localhost", 11211);
$i = $redis->get('p_count');
// echo $pages[$i][0];
// echo '<br/>';
// echo $i;die();
for (; $i < count($pages); $i++) {
    if ($i%10000==0) {
        sleep(30);
    }
// foreach ($pages as $page) {
    # code...
    $page = $pages[$i];
    $key_str = $page[0];
    $page = $page[1];
    $url = sprintf($page,1);



    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,$url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
    $html = curl_exec($curl_handle);
    curl_close($curl_handle);

    // echo $html;
    if (empty($html)) {
        // $error_p[] = array($key_str, $url);
        $error_p = $redis->get("error_p");
        if (empty($error_p)) {
            $error_p = json_encode(array());
        }
        $error_p = json_decode($error_p, true);
        $error_p[] = array($key_str, $url);
        $error_p = json_encode($error_p);
        $redis->set("error_p", $error_p);
        continue;
    }
    // memcache_add($memcache_obj, $key_str.'_p1', $html, false, 0);
    $redis->set($key_str.'_p1', $html);

    $redis->set('p_count', $i+1);
    // mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    // $dom = new DomDocument();
    // @$dom->loadHTML($html);
    // // $dom->loadXML($html);
    // $xpath = new DomXPath($dom);
    // //页码
    // $pages = $xpath->query("//a[@class='end']");
    // if ($pages->length == 0) {
    //     $num = 1;
    // } else {
    //     $num = $pages[0]->nodeValue;
    // }
    // $pagenum[] = $num;
    // $pageNums[] = $pagenum;
}