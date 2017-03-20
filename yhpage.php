<?php
//$url = 'http://www.posp.cn/cnaps?p=%u&province=%u&bank=%u&key=&city=%u';
$host = "http://lianhanghao.com";
$url = "http://lianhanghao.com/index.php/Index/index/bank/%u/province/%u/city/%u/p/%u.html";
$banks = file_get_contents('bank.json');
$banks = json_decode($banks, true);
$city = file_get_contents('city.json');
$city = json_decode($city, true);
// var_dump($city);
try{
    // $db = new PDO("mysql:host=127.0.0.1;dbname=xiaozhu;charset=utf8","root","root");
    // $provinces = array();
    // foreach ($db->query('SELECT * from xz_area WHERE PARENT_ID = 0', PDO::FETCH_ASSOC) as $row) {
    //     $province = array();
    //     $province = array($row['REGION_ID'], $row['REGION_NAME']);
    //     $provinces[] = $province;
    // }
    // $city = array();
    // foreach ($provinces as $province) {
    
    //     foreach ($db->query('SELECT * from xz_area WHERE PARENT_ID = '.$province[0], PDO::FETCH_ASSOC) as $row) {
    //         $city[] = array($province[0], $row['REGION_ID'] ,$row['REGION_NAME']);
    //     }
    // }
    // var_dump($city);
    // echo json_encode($city, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);

    // $stmt = $dbh->prepare("INSERT INTO REGISTRY (name, value) VALUES (:name, :value)");
    // $stmt->bindParam(':name', $name);
    // $stmt->bindParam(':value', $value);
    // // 插入一行
    // $name = 'one';
    // $value = 1;
    // $stmt->execute();
    // //  用不同的值插入另一行
    // $name = 'two';
    // $value = 2;
    // $stmt->execute();
} catch (PDOException  $e ){
    echo "Error: ".$e;
}
// var_dump($banks);
// var_dump($city);
// die();
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
        $keystr = $bCode.'-'.$pCode.'-'.$cCode;
        $purl = "http://lianhanghao.com/index.php/Index/index/bank/".$bCode."/province/".$pCode."/city/".$cCode."/p/%u.html";
        $url_ary[] = array($keystr, $purl);//sprintf($url,$bCode,$pCode,$cCode,1);
        // $string = file_get_contents($p_url);

        // $curl_handle=curl_init();
        // curl_setopt($curl_handle, CURLOPT_URL,$p_url);
        // curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
        // curl_setopt($curl_handle, CURLOPT_TIMEOUT, 1);
        // curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        // // curl_setopt($curl_handle, CURLOPT_USERAGENT, 'yhlhh');
        // $html = curl_exec($curl_handle);
        // curl_close($curl_handle);
        // if (empty($html)) {
        //     $error_p[] = array($bCode, $pCode, $cCode);
        //     continue;
        // }

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