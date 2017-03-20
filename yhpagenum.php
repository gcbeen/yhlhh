<?php
//$url = 'http://www.posp.cn/cnaps?p=%u&province=%u&bank=%u&key=&city=%u';
$pages = file_get_contents('pages.json');
$pages = json_decode($pages, true);

// var_dump($pages[453]);
// die();
// $error_p = array();
// $nextNum = $num + 100;
// $pageNums = array();
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
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

// sleep(30);
// $errors = array();
// foreach ($error_p as $key_url) {
//     list($key_str, $url) = $key_url;
//     $curl_handle=curl_init();
//     curl_setopt($curl_handle, CURLOPT_URL,$url);
//     curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 1);
//     curl_setopt($curl_handle, CURLOPT_TIMEOUT, 2);
//     curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
//     curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
//     $html = curl_exec($curl_handle);
//     curl_close($curl_handle);
//     if (empty($html)) {
//         $errors[] = $key_url;
//         continue;
//     }

//     memcache_add($memcache_obj, $key_str.'_p1', $html, false, 0);
// }

// memcache_close($memcache_obj);
// $myfile = fopen("yhcurrnum.json", "w") or die("Unable to open file!");
// fwrite($myfile, $nextNum);
// fclose($myfile);
// var_dump($errors);
// $myfile = fopen("error_pages.json", "w") or die("Unable to open file!");
// $errors = json_encode($errors, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
// fwrite($myfile, $errors);
// fclose($myfile);

echo 'base pages end';
die();
$error_p = array();
$url_ary = array();
try {
// $dom = new DomDocument();
// $memcache_obj = memcache_connect("localhost", 11211);
foreach ($banks as $bCode => $bank) {//bank
    // $yhlhhs = array();
    foreach ($city as $c_tmp) {
    foreach ($c_tmp as $c) {//province
        $pCode = $c['pid'];
        $cCode = $c['id'];
        $purl = "http://lianhanghao.com/index.php/Index/index/bank/".$bCode."/province/".$pCode."/city/".$cCode."/p/%u.html";
        $url_ary[] = $purl;//sprintf($url,$bCode,$pCode,$cCode,1);
        // $string = file_get_contents($p_url);



        // mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
        
        // @$dom->loadHTML($html);
        // // $dom->loadXML($html);
        // $xpath = new DomXPath($dom);
        // //页码
        // $pages = $xpath->query("//a[@class='end']");
        // if ($pages->length == 0) {
        //     $pages = 1;
        // } else {
        //     $pages = $pages[0]->nodeValue;
        // }
        // $url_ary[$bCode][$pCode][$cCode] = $pages;


        // $yylhh = array();
        // for ($p=1; $p < $pages+1; $p++) {
        //     $p_url = sprintf($url,$bCode,$pCode,$cCode,$p);
        //     $opts = array('http'=>array('method'=>"GET",'timeout'=>30,));
        //     $context = stream_context_create($opts);
        //     $html = file_get_contents($p_url, false, $context);
        //     $string = $html;
        //     if (empty($html)) {
        //         $error_pp[] = array($bCode, $pCode, $cCode, $p);
        //         continue;
        //     }
        //     mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8');
        //     $dom = new DomDocument();
        //     @$dom->loadHTML($string);
        //     $xpath = new DomXPath($dom);

        //     $items = $xpath->query("//table[@class='table']//tbody//tr");
        //     if (!$items) {
        //         break;
        //     }
        //     for ($i = 0; $i < $items->length; $i++) {
        //         $yhlhh_a = $xpath->query("//table[@class='table']//tbody//tr[".($i+1)."]/td");
        //         $tmp = array($bCode, $cCode);
        //         foreach ($yhlhh_a as $yhlhh) {
        //             $tmp[] = trim($yhlhh->nodeValue);
        //         }
        //         $yylhh[] = $tmp;
        //     }
        // }
        // $yhlhhs[$cCode] = $yylhh;

    }
    }
    // $key_str = 'b_'.$bCode;
    // $val_str = json_encode($yhlhhs);
    // memcache_add($memcache_obj, $key_str, $val_str, false, 0);
}

$myfile = fopen("pages.json", "w") or die("Unable to open file!");
$url_ary = json_encode($url_ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
fwrite($myfile, $url_ary);
fclose($myfile);
echo 'base pages end';
die();
sleep(30);
$errors = array();
foreach ($error_p as $bpc) {
    list($bCode,$pCode,$cCode) = $bpc;
    $p_url = sprintf($url,$bCode,$pCode,$cCode,1);

    // $string = file_get_contents($p_url);
    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,$p_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
    $html = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($html)) {
        $errors[] = array($bCode, $pCode, $cCode);
        continue;
    }

    // mb_convert_encoding($html, 'HTML-ENTITIES', 'UTF-8');
    // @$dom->loadHTML($html);
    // // $dom->loadXML($html);
    // $xpath = new DomXPath($dom);
    // //页码
    // $pages = $xpath->query("//a[@class='end']");
    // if ($pages->length == 0) {
    //     $pages = 1;
    // } else {
    //     $pages = $pages[0]->nodeValue;
    // }
    // $url_ary[$bCode][$pCode][$cCode] = $pages;
}

// $myfile = fopen("code_pages.json", "w+") or die("Unable to open file!");
// $url_ary = json_encode($url_ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// fwrite($myfile, $url_ary);
// fclose($myfile);
var_dump($errors);
echo 'pages end';
die();
// memcache_close($memcache_obj);
// echo 'last key: '.$key_str;
} catch (Exception $e) {
    // echo 'last key: '.$key_str;
    // memcache_close($memcache_obj);
}
// var_dump($error_pp);
var_dump($error_p);
// {"bankCode":{"cityCode":["联行号", "网点名称", "电话", "地址"]}}