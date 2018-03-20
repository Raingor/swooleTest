<?php

$host = "0.0.0.0";
$port = 9501;
$server = new swoole_websocket_server($host,$port);

$server->on('open', function($server,$request){
    echo "server: 成功握手==".$request->fd."\n";
});
$server->on('message',function($server,$frame){
    echo "receive from {$frame->fd}:{$frame->data},opcode:{$frame->opcode},fin:{$frame->finish}\n";
    $server->push($frame->fd, "this is server");
});

$server->on('close',function($ser,$fd){
    echo "客户端 {$fd} 已关闭";
});

$server->start();