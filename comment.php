<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/12/11 20:19
 */

error_reporting(E_ALL^E_NOTICE);
$mb_id = $_GET['mbid'];
$cid = $_GET['cid'];
$content = $_GET['content'];
$comment_id = "comment".$mb_id;
$cable = $_GET['cable'];
$servername = "localhost:3306";
$sqlname = "root";
$sqlpassword = "Diabl0s3";
$dbname = "microblog";
$conn = new mysqli($servername,$sqlname,$sqlpassword,$dbname);

if ($conn->connect_error) {
    die("连接失败:".$conn->connect_error);
}


if ($content) {
    $cid = explode('d',$cid)[1];
    $sql1 = "UPDATE comment SET mb_reply2comment='$content' WHERE cid='$cid'";
    $conn->query($sql1);
}

$sql = "SELECT * FROM comment WHERE mb_id='$mb_id'";
$result = $conn->query($sql);
if ($result->num_rows > 0){
    $response = "<ul>";
    while ($row = $result->fetch_assoc()){
        $comment_author = $row['comment_username'];
        $comment_content = $row['mb_comment'];
        $reply_content = $row['mb_reply2comment'];
        $cid = 'cid'.$row['cid'];
        $response.="<li>".$comment_author.":&nbsp&nbsp".$comment_content."<br>";
        if ($reply_content) {
            $response.="&nbsp&nbsp"."博主回复:"."&nbsp&nbsp".$reply_content;
        }
        else {
            if ($cable==1) {
                $response.="<input type='text' id='$cid'>"."&nbsp&nbsp&nbsp&nbsp"."<button type='button' onclick='reply(\"$cid\",\"$comment_id\",\"$mb_id\",\"$cable\")'>回复</button>";
            }
        }
        $response.="</li>";
    }
    $response.="</ul>";
    echo $response;

}
else {
    echo '暂无评论';
}
?>