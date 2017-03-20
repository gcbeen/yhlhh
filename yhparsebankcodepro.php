<?php
// $redis = new Redis();
// $redis->connect('127.0.0.1', 6379);

// $url = "http://lianhanghao.com/index.php/Index/index/bank/%u/province/%u/city/%u/p/%u.html";


// $purl = sprintf($url,13,9,107,3);
// echo $purl;
// $curl_handle=curl_init();
// curl_setopt($curl_handle, CURLOPT_URL,$purl);
// curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
// curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
// curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
// // curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
// $html = curl_exec($curl_handle);
// curl_close($curl_handle);
// echo $html;

// $redis->set("13-9-107_p3", $html);
// die();
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

$parse_keys = $redis->get("parse_keys"); //_nu
$parse_keys = json_decode($parse_keys, true);//_p1

$check_page_keys = $redis->get("check_page_keys");
$check_page_keys = json_decode($check_page_keys, true);//_p1

$zero_page = $redis->get("zero_page"); //_nu
$zero_page = json_decode($zero_page, true);

// var_dump($parse_keys);

foreach ($check_page_keys as $check_page_key) {
    list($kCode, $o) = explode('_', $check_page_key);
    $zero_page_check = $kCode.'_nu';
    if (in_array($zero_page_check, $zero_page)) {
        continue;
    }
    $parse_pages[] = $check_page_key;

}

$diff = array_diff($parse_pages, $parse_keys);

// var_dump($diff);

// die();

$yhllCode = $redis->get('BANKCODE');
$yhllCode = json_decode($yhllCode, true);

$done_num = count($parse_keys);
// $parse_keys = array();
foreach ($diff as $parse_page) {
    // list($kCode, $o) = explode('_', $parse_page);
    $html = $redis->get($parse_page);
    
    mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom = new DomDocument();
    @$dom->loadHTML($html);
    $xpath = new DomXPath($dom);
    $items = $xpath->query("//table[@class='table table-hover']//tbody//tr");
    // echo 'aaa';
    if (!$items || $items->length == 0) {
        continue;
    }
    // echo 'bbb';
    // echo 'aaa';die();
    $yylhh = array();
    for ($i = 0; $i < $items->length; $i++) {
        $yhlhh_a = $xpath->query("//table[@class='table table-hover']//tbody//tr[".($i+1)."]/td");
        $tmp = array();
        foreach ($yhlhh_a as $tdNode) {
            $tmp[] = trim($tdNode->nodeValue);
        }
        $yylhh[] = $tmp;
    }

    // if ($done_num == 5) {
    //     var_dump($yylhh);die();
    // }
    $done_num++;
    $parse_keys[] = $parse_page;
    //$redis->set($parse_page.'CODE', json_encode($yylhh));

    // echo 'aaaaaaaaaaaa';
    // echo $parse_page;
    // var_dump($yylhh);
    // die();
    $yhllCode[$parse_page] = $yylhh;

}

$redis->set('parse_keys', json_encode($parse_keys));
$redis->set('BANKCODE', json_encode($yhllCode));

echo $done_num;

echo 'parse bank code done....';


