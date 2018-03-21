<?php

$host = "0.0.0.0";
$port = 9501;
$server = new swoole_websocket_server($host,$port);

$server->on('open', function($server,$request){
    echo "server: 成功握手==".$request->fd."\n";
       $server->push($request->fd, "当前在线人数：".count($server->connections));
    foreach ($server->connections as $fd) {
        $uid = $request->fd;
        if($uid!=$fd){
            $server->push($fd,"用户：$uid 上线啦！！！！");
        }
    }
});
$server->on('message',function($server,$frame){
    // echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $fromId = $frame->fd;
    $msg = $frame->data;
    foreach ($server->connections as $toUser) {
        # code...
        if($toUser!=$fromId){
            $server->push($toUser,$fromId."用户说了:".$msg);
        }else{
            $server->push($fromId,"我说：".$msg);
        }
    }
});

$server->on('close',function($ser,$fd){
    echo "客户端 {$fd} 已关闭";
});

$server->start();