<?php

$host = "0.0.0.0";
$port = 9501;

/**
 * 创建websocket的 服务
 */
$server = new swoole_websocket_server($host,$port);


$server->on('open',function($server,$fd){
    $fromUser = $fd->fd;
    echo "成功握手======".$fromUser."\n";
    $server->push($fromUser,"成功握手");
    getCurrentUser();
   noticeUp($fromUser);
});

/**
 * 当服务收到数据后
 */
$server->on('message',function($server,$fd){
    $fromUser = $fd->fd;
   $content = $fd->data;
});

/**
 * 当用户和服务断开时
 */
$server->on('close',function($server,$fd){
    $fromUser = $fd->fd;
    echo $fromUser."已断开连接\n";
    $server->push($fromUser,"已和服务器断开连接");
    noticeDown($fromUser);
});

/**
 * 通知上线
 */
function noticeUp($fromUser){
    foreach ($server->connections as $toUser) {
        $server->push($toUser,"用户".$fromUser."已上线");
    }
}
/**
 * 通知下线
 */
function noticeDown($fromUser){
foreach ($server->connections as $toUser) {
        assert($fromUser!=$toUser);
        $server->push($toUser,$fromUser."已下线");
    }

}


/**
 * 获取当前用户列表
 */
function getCurrentUser(){
    foreach ($server->connections as $user) {
        $userid.="$user,";
    }
    $server->push($user,"当前用户列表".$userid);
}


$server->start();