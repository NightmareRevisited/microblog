<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/12/15 12:53
 */
error_reporting(E_ALL^E_NOTICE);
$username = $_GET['username'];
$friendname = $_GET['friendname'];
$servername = "localhost:3306";
$sqlname = "root";
$sqlpassword = "Diabl0s3";
$dbname = "microblog";
$conn = new mysqli($servername,$sqlname,$sqlpassword,$dbname);

if ($conn->connect_error) {
    die("连接失败:".$conn->connect_error);
}

$sql1 = "DELETE FROM relation WHERE (username='$username' and friendname='$friendname') or (username='$friendname' and friendname='$username')";
$conn->query($sql1);

$sql2 = "SELECT * FROM relation WHERE username='$username'";
$result="";
while ($row = $conn->query($sql2)->fetch_assoc()) {
    $result.="<li>$friendname&nbsp;&nbsp;&nbsp;<button type='button' onclick='unfriend(\"$username\",\"$friendname\")'>删除好友</button></li><br>";
}
echo $result;

?>