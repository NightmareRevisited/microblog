<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/11/26 16:17
 */

echo "连接成功",PHP_EOL;

$sql = "CREATE DATABASE micro_blog";

if ($conn->query($sql) === True) {
    echo "数据库 micro_blog 创建成功".PHP_EOL;
}
else {
    echo "创建失败:".$conn->error;
}

$sql = "CREATE TABLE comment (
mb_id INT(8) 
)"

$conn -> close();

?>
