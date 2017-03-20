<?php
//连接本地的 Redis 服务
$redis = new Redis();
$redis->connect('127.0.0.1', 6379);
echo "Connection to server sucessfully<br/>";
//查看服务是否运行
echo "<br/>Server is running: ";
echo $redis->ping();

// $redis->set("tutorial-name", "Redis tutorial");
// 获取存储的数据并输出
echo "<br/>Stored string in redis:: ";
echo $redis->get("tutorial-name");
