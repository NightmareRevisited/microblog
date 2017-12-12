<?php

/**
* Created by PhpStorm.
* Author: Yang Changning (thevile@126.com)
* Time: 2017/11/30 1:14
*/

error_reporting(E_ALL^E_NOTICE);
$username=$_GET['username'];
$secret_password=$_GET['password'];
$password=base64_decode($_GET['password']);
$servername = "localhost:3306";
$sqlname = "root";
$sqlpassword = "Diabl0s3";
$dbname = "microblog";
$conn = new mysqli($servername,$sqlname,$sqlpassword,$dbname);

if ($conn->connect_error) {
    die("连接失败:".$conn->connect_error);
}
$sql1 = "SELECT * FROM login_info where username='$username'";
$result = $conn->query($sql1);
$rows = $result->fetch_assoc();
if ($result->num_rows < 1 or $password != $rows["password"]) {
    header('Location:http://microblog.com/login.php');
}

?>

<html>
<head>
    <meta charset="utf-8">
    <title>信息箱</title>
</head>
<body>

<a href="http://microblog.com/main.php?username=<?php echo $username;?>&password=<?php echo base64_encode($password);?>">返回主界面</a>
&nbsp&nbsp&nbsp
<br>
<h2>好友请求</h2>
<style>
    a:visited {color: blue}
</style>

<?php
if ($_POST['searchname']) {
    $acceptuser = $_POST['searchname'];
    if ($_POST['refuse']) {
        $friend_request_operation = -1;
    } elseif ($_POST['accept']) {
        $friend_request_operation = 1;
        $sql5 = "INSERT INTO relation (username,friendname) VALUES ('$username','$acceptuser')";
        $sql6 = "INSERT INTO relation (username,friendname) VALUES ('$acceptuser','$username')";
        $conn->query($sql5);
        $conn->query($sql6);
    }
    $sql4 = "UPDATE friendrequest SET readstatus='$friend_request_operation' WHERE touser='$username' and fromuser='$acceptuser'";
    $conn->query($sql4);
}
?>


<?php
$sql2 = "SELECT * FROM friendrequest where touser='$username' and readstatus='0'";
$fresult = $conn->query($sql2);
$unread_rownum = $fresult->num_rows;

echo "<ul>";

while ($rows = $fresult->fetch_assoc()) {
    $fromuser = $rows['fromuser'];
    echo "<li style='margin:20px 0;'>" . "<form action=\"messagebox.php?username=$username&password=$secret_password\" method=\"post\">" . "<input type='text' name='searchname' value=$fromuser style='border:none;font-size:18;width: 100px;' readonly='true'>"  ."&nbsp&nbsp&nbsp&nbsp". "<input type='submit' name='accept' value='接受' />" ."&nbsp&nbsp&nbsp"."<input type='submit' name='refuse' value='拒绝' />" . "</form>" . "</li>";

}
echo "</ul>";
?>

<?php

$sql3 = "SELECT * FROM friendrequest where touser='$username' and readstatus!='0'";
$fresult = $conn->query($sql3);

$unread_rownum = $fresult->num_rows;

echo "<ul>";

while ($rows = $fresult->fetch_assoc()) {
    $fromuser = $rows['fromuser'];
    if ($rows['readstatus']==1) {
        $readstatus = '已接受';
    }
    else {
        $readstatus = '已拒绝';
    }
    echo "<li style='margin:20px 0;'>" .$fromuser ."&nbsp&nbsp&nbsp".$readstatus. "</li>";

}
echo "</ul>";


?>
<hr>
<br>
<h2>好友列表</h2>
<ul>
<?php
    $sql7 = "SELECT * FROM relation WHERE username='$username'";
    $friend = $conn->query($sql7);
    while ($row = $friend->fetch_assoc()){
        $friendname = $row['friendname'];
        echo "<li>$friendname&nbsp;&nbsp;&nbsp;<button type='button' onclick='unfriend(\"$username\",\"$friendname\")'>删除好友</button></li><br>";
    }
?>
</ul>
</body>
</html>