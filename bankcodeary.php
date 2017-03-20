<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
$yhllCode = $redis->get('BANKCODE');

$yhllCode = json_decode($yhllCode, true);
$ary = array();
//2-6-84_p1
$lhCode = array();
foreach ($yhllCode as $pageNum => $cAry) {
    list($kCode, $p) = explode('_p', $pageNum);
    list($bCode, $pCode, $cCode) = explode('-', $kCode);
    foreach ($cAry as $code) {
        $lhCode[] = $code[0];
        $tmp = $code;
        $tmp[] = $bCode;
        $tmp[] = $pCode;
        $tmp[] = $cCode;
        $ary[] = $tmp;
    }
}
// echo count($lhCode);
// var_dump($lhCode);
// echo '------------------';
// echo count(array_unique($lhCode));
// echo '------------------';
// echo count($ary);
// echo 'BANKCODE end...';

foreach($ary as $line){
    fputcsv($handle,$line);
}


// $handle = fopen("csvfile.csv","w");
// // $file = fopen("contacts.csv","w");
// foreach($ary as $line){
//     fputcsv($handle,$line);
// }
// fclose($handle);
die();