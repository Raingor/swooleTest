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
});

/**
 * 当服务收到数据后
 */
$server->on('message',function($server,$fd){
    $fromUser = $fd->fd;
   $content = $fd->data;
    foreach ($server->connections as $toUser) {
        if($toUser!=$fromUser){
            $server->push($toUser,json_encode(['content'=>$content,'isMe'=>false],JSON_UNESCAPED_UNICODE));
        }else{
            $server->push($fromUser,json_encode(['content'=>$content,'isMe'=>true],JSON_UNESCAPED_UNICODE));
        }
    }
});

/**
 * 当用户和服务断开时
 */
$server->on('close',function($server,$fd){
    echo $fd."已断开连接\n";
});


$server->start();