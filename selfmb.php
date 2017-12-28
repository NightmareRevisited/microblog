<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/12/28 12:11
 */
error_reporting(E_ALL^E_NOTICE);
$username = $_GET['username'];
$servername = "localhost:3306";
$sqlname = "root";
$sqlpassword = "Diabl0s3";
$dbname = "microblog";
$conn = new mysqli($servername,$sqlname,$sqlpassword,$dbname);

if ($conn->connect_error) {
    die("连接失败:" . $conn->connect_error);
}

$sql = "SELECT * FROM micro_blog WHERE username='$username'";
$result = $conn->query($sql);
$response = "<button type='button' onclick='show_self_mb(\"$username\")'>主页</button>";
$response.="<ul>";
while ($row = $result->fetch_assoc()) {
    $mb_id = $row['blogid'];
    $commentid = 'comment'.$mb_id;
    $mb_content = $row['mb_content'];
    $mb_username = $row['username'];
    $mb_time = $row['mb_time'];
    $cable = $mb_username == $username;
    $sqlx="SELECT count(mb_id) as zannum from zan where mb_id='$mb_id'";
    $xresult = $conn->query($sqlx)->fetch_assoc();
    $zannum=$xresult['zannum'];
    $sqlcn = "SELECT count(mb_id) as cn FROM comment WHERE mb_id='$mb_id'";
    $cnr = $conn->query($sqlcn)->fetch_assoc();
    $commentnum = $cnr['cn'];
    $sqlfc = "SELECT * FROM zan WHERE username='$username' and mb_id='$mb_id'";
    if ($conn->query($sqlfc)->num_rows>0) {
        $color = 'red';
    }
    else {
        $color = 'black';
    }
    $response.="<li style='margin:20px 0;'>" ."<form action=\"main.php?username=$username&password=$secret_password\" method=\"post\">" ."<input type='hidden' name='id' value='$mb_id'>".$mb_username ."<br>"."内容:".$mb_content."&nbsp&nbsp&nbsp"."<input type='submit' name='zan' value='赞($zannum)' style='color: $color' />" ."<br>".$mb_time."<br>"."<input type='text' name='comment'   >" . "&nbsp&nbsp&nbsp&nbsp" . "<input type='submit' value='评论' />" ."&nbsp&nbsp&nbsp&nbsp"."<button type='button' onclick='loadComment(\"$commentid\",\"$mb_id\",\"$cable\")'>查看评论($commentnum)</button>"."<br>"."<div id='$commentid'></div>". "</form>". "</li>";
}
$response.="</ul>";
echo $response;
?>
