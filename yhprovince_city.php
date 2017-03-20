<?php

$city = file_get_contents('city.json');
$city = json_decode($city, true);
$key_ary = array();
// var_dump(count($city));
// die();
foreach ($city as $p) {
    foreach ($p as $c) {
        // if (!array_key_exists($c['pid'], $key_ary)) {
        //     $key_ary[$c['pid']] = array();
        // }
        $key_ary[$c['pid']][$c['id']] = $c["name"];
    }
}
// var_dump($key_ary);
// echo count($key_ary);
$key_ary = json_encode($key_ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
$myfile = fopen("pcity.json", "w") or die("Unable to open file!");
fwrite($myfile, $key_ary);
fclose($myfile);
die();



$host = 'http://lianhanghao.com/';//'http://www.posp.cn';
// $xml = simplexml_load_file($host);

// print_r($xml);

// $p_c_a_str = file_get_contents('p_c_a.json');
// $p_c_a_ary = json_decode($p_c_a_str, true);

// $myfile = fopen("province_city_area.json", "w") or die("Unable to open file!");
// $province_city_area = json_encode($p_c_a_ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// fwrite($myfile, $province_city_area);
// fclose($myfile);

$html = file_get_contents($host);
$string = $html;

mb_convert_encoding($string, 'HTML-ENTITIES', 'UTF-8');
$dom = new DomDocument();
$dom->loadHTML($string);
// $dom->loadXML($string);
$xpath = new DomXPath($dom);

// 银行号
// $items = $xpath->query("//select[@id='bank']/option");
// $ary = array();
// foreach ($items as $item) {
//     if ($item->getAttribute('value') == '') {
//         continue;
//     }
//     $key = $item->getAttribute('value');
//     // echo $item->nodeValue.'<br />';
//     $ary[$key] = $item->nodeValue;
// }
// // var_dump($ary);
// $myfile = fopen("bank.json", "w") or die("Unable to open file!");
// $bank = json_encode($ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// fwrite($myfile, $bank);
// fclose($myfile);
// echo 'bank end';
// sleep(30);


// 省份
$items = $xpath->query("//select[@id='province']/option");
$ary = array();
foreach ($items as $item) {
    if ($item->getAttribute('value') == '') {
        continue;
    }
    $key = $item->getAttribute('value');
    // echo $item->nodeValue.'<br />';
    $ary[$key] = $item->nodeValue;
}

$myfile = fopen("province.json", "w") or die("Unable to open file!");
$province = json_encode($ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
fwrite($myfile, $province);
fclose($myfile);
echo 'province end';
// sleep(30);
die();
// 检测

// $city = file_get_contents('city.json');
// $city = json_decode($city, true);
// $pids = array();
// foreach ($city as $c_tmp) {
//     foreach ($c_tmp as $c) {
//         if (!in_array($c['pid'], $pids)) {
//             $pids[] = $c['pid'];
//         }
//     }
// }
// var_dump(array_keys($provinces));
// var_dump($pids);
// die();

// 城市
$url = "http://lianhanghao.com/index.php/Index/Ajax?id=%u";
$cities = array();
$error_p = array();
foreach ($provinces as $p => $value) {
    $c_url = sprintf($url, $p);
    // try {
    //     $html = file_get_contents($c_url);
    // } catch (Exception $e) {
    //     $html = file_get_contents($c_url);
    // }

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,$c_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
    $html = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($html)) {
        $error_p[] = $p;
        continue;
    }

    $cities[] = json_decode($html, true);
}

sleep(30);

$erros = array();
foreach ($error_p as $p) {
    $c_url = sprintf($url, $p);
    // try {
    //     $html = file_get_contents($c_url);
    // } catch (Exception $e) {
    //     $html = file_get_contents($c_url);
    // }

    $curl_handle=curl_init();
    curl_setopt($curl_handle, CURLOPT_URL,$c_url);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
    $html = curl_exec($curl_handle);
    curl_close($curl_handle);
    if (empty($html)) {
        $erros[] = $p;
        continue;
    }

    $cities[] = json_decode($html, true);
}

$myfile = fopen("city.json", "w") or die("Unable to open file!");
$cities = json_encode($cities, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
fwrite($myfile, $cities);
fclose($myfile);
var_dump($erros);
echo 'city end';
die();

// $items = $xpath->query("//select[@id='province']/option");
// $ary = array();
// foreach ($items as $item) {
//     if ($item->getAttribute('value') == '') {
//         continue;
//     }
//     $key = $item->getAttribute('value');
//     // echo $item->nodeValue.'<br />';
//     $ary[$key] = $item->nodeValue;
// }

// $myfile = fopen("province.json", "w") or die("Unable to open file!");
// $province = json_encode($ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// fwrite($myfile, $province);
// fclose($myfile);


//省份
// 银行号
// $items = $xpath->query("//select[@id='province']/option");
// $ary = array();
// foreach ($items as $item) {
//     if ($item->getAttribute('value') == '') {
//         continue;
//     }
//     $key = $item->getAttribute('value');
//     // echo $item->nodeValue.'<br />';
//     $ary[$key] = $item->nodeValue;
// }
// // echo '银行';
// // echo json_encode($ary, JSON_UNESCAPED_UNICODE);

// $myfile = fopen("province.json", "w") or die("Unable to open file!");
// $province = json_encode($ary, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
// fwrite($myfile, $province);
// fclose($myfile);
