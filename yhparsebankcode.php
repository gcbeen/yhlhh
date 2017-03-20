<?php
// pages - zero page
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// $check_page_keys = $redis->get("check_page_keys");
// $check_page_keys = json_decode($check_page_keys, true);//_p1

// foreach($check_page_keys as $check_page_key) {
//     $redis->delete($check_page_key.'CODE');
// }

// die();



$zero_page = $redis->get("zero_page"); //_nu
$zero_page = json_decode($zero_page, true);

$check_page_keys = $redis->get("check_page_keys");
$check_page_keys = json_decode($check_page_keys, true);//_p1

// var_dump($check_page_keys);die();

$parse_pages = array();
foreach ($check_page_keys as $check_page_key) {
    list($kCode, $o) = explode('_', $check_page_key);
    $zero_page_check = $kCode.'_nu';
    if (in_array($zero_page_check, $zero_page)) {
        continue;
    }
    $parse_pages[] = $check_page_key;

}

echo count($parse_pages);
echo '<br/>';

// parse page

$yhllCode = array();
$done_num = 0;
$parse_keys = array();
foreach ($parse_pages as $parse_page) {
    // list($kCode, $o) = explode('_', $parse_page);
    $html = $redis->get($parse_page);
    
    mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom = new DomDocument();
    @$dom->loadHTML($html);
    $xpath = new DomXPath($dom);
    $items = $xpath->query("//table[@class='table table-hover']//tbody//tr");

    if (!$items || $items->length == 0) {
        break;
    }
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
