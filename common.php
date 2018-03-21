<?php
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
