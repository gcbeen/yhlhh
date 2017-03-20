<?php

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 列出所有的key
$page_keys = $redis->keys('*_p1');

// $nu_keys = $redis->keys('*_nu');
// echo count($page_keys);
// echo '=========';
// echo count($nu_keys);
// die();

$pagenum = array();
foreach ($page_keys as $page_key) {
    // $page_key = '1-1-35_p1';//多页
    // $page_key = '1-16-186_p1';//多页
    // $page_key = '1-19-238_p1';//一页
    // $page_key = '37-19-238_p1';//0
    // echo $page_key;
    $html = $redis->get($page_key);

    mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    $dom = new DomDocument();
    @$dom->loadHTML($html);
    // $dom->loadXML($html);
    $xpath = new DomXPath($dom);
    //页码
    $xStr = "//a[@class='end']";
    $pages = $xpath->query($xStr);
    if ($pages == false || $pages->length == 0) {
        $xStr = "//div[@class='pagination']//a[@class='num'][last()]";
        $pages = $xpath->query($xStr);
        if ($pages == false || $pages->length == 0) {
            $items = $xpath->query("//table[@class='table table-hover']//tbody//tr");
            if ($items == false || $items->length == 0) {
                $num = 0;
            } else {
                $num = 1;
            }
        } else {
            $num = $pages[0]->nodeValue;
        }
    } else {
        $num = $pages[0]->nodeValue;
    }
    // echo $num;die();
    $mum_key = str_replace("_p1", "_nu", $page_key);
    $redis->set($mum_key, $num);
}

//检测

$num_keys = $redis->keys('*_nu');


foreach ($page_keys as $pk) {
    $nk = str_replace("_p1", "_nu", $pk);
    if (!in_array($nk, $num_keys)) {
        echo '<br/>error::'.$nk;
    }
}

echo '<br/>end parse page number';
