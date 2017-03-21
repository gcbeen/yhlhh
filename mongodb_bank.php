<?php
$banks = file_get_contents('bank.json');
$banks = json_decode($banks, true);

$city = file_get_contents('city.json');
$city = json_decode($city, true);

$province = file_get_contents('province.json');
$province = json_decode($province, true);


// var_dump(count($city));die();

$manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
$filter = ['id' => 6];
$options = [
    'projection' => ['_id' => 0],
    'sort' => ['id' => -1],
    'limit' => 1,
];
// 查询数据
$query = new MongoDB\Driver\Query($filter, $options);
$cursor = $manager->executeQuery('test.province', $query)->toArray();;
// var_dump($cursor);
;
$filter = ['pid' => ''.$cursor[0]->id];
$options = [
    'projection' => ['_id' => 0],
    'sort' => ['id' => -1],
    'limit' => 1,
];
// 查询数据
$query = new MongoDB\Driver\Query($filter, $options);
// var_dump($query);
$cursor = $manager->executeQuery('test.city', $query);

foreach ($cursor as $document) {
    print_r($document);
}
echo 'aaaaaaaaaa';
die();
// 查询数据
$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('test.bank', $query);

foreach ($cursor as $document) {
    print_r($document);
}

die();

// 插入数据
$bulk = new MongoDB\Driver\BulkWrite;

foreach ($city as $p) {
    foreach ($p as $c) {
        $bulk->insert($c);
    }
}
$manager->executeBulkWrite('test.city', $bulk);

// 插入数据
$bulk = new MongoDB\Driver\BulkWrite;
foreach ($province as $key => $p) {
    $bulk->insert(['id' => $key, 'name' => $p]);
}
$manager->executeBulkWrite('test.province', $bulk);

// 插入数据
$bulk = new MongoDB\Driver\BulkWrite;
foreach ($banks as $key => $b) {
    $bulk->insert(['id' => $key, 'name' => $b]);
}
$manager->executeBulkWrite('test.bank', $bulk);


// $filter = ['x' => ['$gt' => 1]];
// $options = [
//     'projection' => ['_id' => 0],
//     'sort' => ['x' => -1],
// ];

// 查询数据
$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('test.province', $query);

foreach ($cursor as $document) {
    print_r($document);
}

// 查询数据
$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('test.city', $query);

foreach ($cursor as $document) {
    print_r($document);
}

// 查询数据
$query = new MongoDB\Driver\Query([]);
$cursor = $manager->executeQuery('test.bank', $query);

foreach ($cursor as $document) {
    print_r($document);
}