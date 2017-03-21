<?php

// $bulk = new MongoDB\Driver\BulkWrite;
// $bulk->delete(['x' => 1], ['limit' => 1]);   // limit 为 1 时，删除第一条匹配数据
// $bulk->delete(['x' => 2], ['limit' => 0]);   // limit 为 0 时，删除所有匹配数据

// $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
// $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
// $result = $manager->executeBulkWrite('test.sites', $bulk, $writeConcern);

// die();




// $bulk = new MongoDB\Driver\BulkWrite;
// $bulk->update(
//     ['x' => 2],
//     ['$set' => ['name' => '菜鸟工具', 'url' => 'tool.runoob.com']],
//     ['multi' => false, 'upsert' => false]
// );

// $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
// $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
// $result = $manager->executeBulkWrite('test.sites', $bulk, $writeConcern);

// die();


// $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  

// // 插入数据
// $bulk = new MongoDB\Driver\BulkWrite;
// $bulk->insert(['x' => 1, 'name'=>'菜鸟教程', 'url' => 'http://www.runoob.com']);
// $bulk->insert(['x' => 2, 'name'=>'Google', 'url' => 'http://www.google.com']);
// $bulk->insert(['x' => 3, 'name'=>'taobao', 'url' => 'http://www.taobao.com']);
// $manager->executeBulkWrite('test.sites', $bulk);

// $filter = ['x' => ['$gt' => 1]];
// $options = [
//     'projection' => ['_id' => 0],
//     'sort' => ['x' => -1],
// ];

// // 查询数据
// $query = new MongoDB\Driver\Query($filter, $options);
// $cursor = $manager->executeQuery('test.sites', $query);

// foreach ($cursor as $document) {
//     print_r($document);
// }

// die();




// $bulk = new MongoDB\Driver\BulkWrite;
// $document = ['_id' => new MongoDB\BSON\ObjectID, 'name' => '菜鸟教程'];

// $_id= $bulk->insert($document);

// var_dump($_id);

// $manager = new MongoDB\Driver\Manager("mongodb://localhost:27017");  
// $writeConcern = new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000);
// $result = $manager->executeBulkWrite('test.runoob', $bulk, $writeConcern);

// die();

$mongo = new MongoDB\Driver\Manager();
$bulk = new MongoDB\Driver\BulkWrite(['ordered' => true]);
$data = $mongo->executeQuery('db.collection', new MongoDB\Driver\Query([]), new MongoDB\Driver\ReadPreference(MongoDB\Driver\ReadPreference::RP_PRIMARY_PREFERRED))->toArray();

$arr = ['a' => 'b'];
if (empty($data[0])) { // 确定不存在，插入
    $bulk->insert($arr);
} else { // 否者更新
    
    $bulk->update([], array('$set' => $arr)); // $arr同样是刚才的数组
}
// 还没完，还要执行下一步：db.collection要替换成实际的数据库、集合名
$result = $mongo->executeBulkWrite('db.collection', $bulk, new MongoDB\Driver\WriteConcern(MongoDB\Driver\WriteConcern::MAJORITY, 1000));
// 返回插入或更新是否成功：
$ok = $result->getInsertedCount() || $result->getModifiedCount() ? 1 : 0;
echo 'ok::'.$ok;
// 真是够了！