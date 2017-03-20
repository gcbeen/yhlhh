<?php

$url = "http://lianhanghao.com/index.php/Index/index/bank/%u/province/%u/city/%u/p/%u.html";

$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$num_keys = $redis->keys('*_nu');

$total = 0;
$sec_total = 0;
$sec_req = 0;
foreach ($num_keys as $nk) {
    $total += $redis->get($nk);
    if ($redis->get($nk) > 1) {
        $sec_req += ($redis->get($nk)-1);
    }
}

// echo '<br/>real total paginate number::'.$sec_total;

echo '<br/>second req number::'.$sec_req;
echo '<br/><br/>';
echo '<br/>real total page::'.$total;

$zero_page = $redis->get('zero_page');
$zero_page = json_decode($zero_page, true);
echo "<br/>zero page::".count($zero_page);

echo "<br/>should do page::".(count($zero_page)+$total);

echo '<br/><br/>';
$page_keys = $redis->keys('*_p*');
echo "<br/>down total page::".count($page_keys);

$error_pages = $redis->get('error_po');
$error_pages = json_decode($error_pages, true);

echo "<br/>error    page::".count($error_pages);

echo "<br/>do total page::".(count($page_keys)+count($error_pages));

$check_page_keys = $redis->get('check_page_keys');
$check_page_keys = json_decode($check_page_keys, true);

// echo "<br/>check num:::".$len;
echo '<br/>diff count:';
echo count($check_page_keys) - count($page_keys);
echo '<br/>';
var_dump(array_diff($check_page_keys, $page_keys));
echo '<br/>';
var_dump(array_diff($page_keys, $check_page_keys));
die();
$diff = array_values(array_diff($check_page_keys, $page_keys));
// var_dump($diff);die();
$adiff = array();
foreach ($diff as $d) {
    list($kCode, $p) = explode('_p', $d);
    list($bCode, $pCode, $cCode) = explode('-', $kCode);
    $purl = sprintf($url, $bCode, $pCode, $cCode, $p);
    $adiff[] = array($d, $purl);
}
var_dump($adiff);
die();

// $num_keys = $redis->set('error_po', json_encode($adiff));

// $myfile = fopen("odiff.json", "w") or die("Unable to open file!");
// $diff = json_encode($diff, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// fwrite($myfile, $diff);
// fclose($myfile);
echo 'diff end';
die();