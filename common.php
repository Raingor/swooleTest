<?php
function saveRecord($content,$uid){
    $sql = "INSERT INTO record (uid,talk_record) VALUES($uid,$content)";
    $pdo = getConnection();
    $row = $pdo->exec($sql);
}

function getConnection(){
    $driver = 'mysql';
    $host = 'localhost';
    $port = 3306;
    $usr = 'rong';
    $pwd = 'rong123';
    $dbname = 'test';
    $dns = "$driver:$dbname;host=$host:$port";
    return new PDO($dns,$usr,$pwd);
}