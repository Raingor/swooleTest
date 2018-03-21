<?php

$host = "0.0.0.0";
$port = 9501;

/**
 * 创建websocket的 服务
 */
$server = new swoole_websocket_server($host,$port);
require_once('common.php');

$server->on('open',function($server,$fd){
    $fromUser = $fd->fd;
    echo "成功握手======".$fromUser."\n";
    $server->push($fromUser,"成功握手");
    $userid = '';
    foreach ($server->connections as $user) {
        $userid.="$user,";
    }
    $server->push($fromUser,"当前用户列表".$userid);
    foreach ($server->connections as $toUser) {
        $server->push($toUser,"用户".$fromUser."已上线");
    }
});

/**
 * 当服务收到数据后
 */
$server->on('message',function($server,$fd){
    $fromUser = $fd->fd;
   $content = $fd->data;
    foreach ($server->connections as $toUser) {
        if($toUser!=$fromUser){
            $server->push($toUser,$fromUser."用户说了:".$content);
        }else{
            $server->push($fromUser,"我说：".$content);
        }
    }
});

/**
 * 当用户和服务断开时
 */
$server->on('close',function($server,$fd){
    echo $fd."已断开连接\n";
    $server->push($fd,"已和服务器断开连接");
    foreach ($server->connections as $toUser) {
        assert($fd!=$toUser);
        $server->push($toUser,$fd."已下线");
    }
});


$server->start();