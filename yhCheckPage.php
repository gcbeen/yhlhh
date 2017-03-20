<?php
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);

// 列出所有的key
$page_keys = $redis->keys('*_p1');
// var_dump($page_keys);
// 列出出错的key
$error_p = $redis->get('error_p');
$error_p = json_decode($error_p, true);
$error_k = array();
foreach ($error_p as $p) {
    $error_k[] = $p[0];
}
// var_dump($error_k);
// 列出pages.json里的抓取了的页面

// $done_ary = array_merge($error_k, $page_keys);
// var_dump($done_ary);
echo 'pages:'.count($page_keys);
echo '<br/>';
echo 'error pages:'.count($error_k);
echo '<br/>';
echo 'done pages:'.(count($error_k)+count($page_keys));
echo '<br/>';
$p_count = $redis->get('p_count');
echo 'count page:'.$p_count;

$p_count;

$pages = file_get_contents('pages.json');
$pages = json_decode($pages, true);
$key_ary = array();
for($i = 0; $i < $p_count; $i++) {
    $page = $pages[$i];
    $key_ary[] = $page[0];
}
// $undo_ary = array_diff($key_ary, $done_ary);
$i = 0;
echo '<br />';
echo 'not records pages::<br/>';
foreach ($key_ary as $value) {
    if (!in_array($value.'_p1', $page_keys)) {
        if (!in_array($value, $error_k)) {
            echo $value;
            echo '<br/>';
            $i++;
        }
        // if ($i == 2) {
        //     break;
        // }
    }
}
// var_dump($done_ary);
echo 'page checked end';
die();
