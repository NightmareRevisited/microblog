<?php

/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/11/30 1:14
 */

error_reporting(E_ALL^E_NOTICE);
$username=$_GET['username'];
$secret_password = $_GET['password'];
$password=base64_decode($secret_password);
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
    <title>查找用户</title>
</head>
<body>

<form action="seekuser.php?username=<?php echo $username;?>&password=<?php echo base64_encode($password);?>" method="post">
    <input type="text" name="seekuser">
    &nbsp&nbsp&nbsp
    <input type="submit" value="查找相关用户">
</form>
<br>

<?php
$seekstr = $_POST['seekuser'];
if ($seekstr) {
    $sql2 = "SELECT * FROM login_info WHERE username LIKE '%$seekstr%'";
    $seekresult = $conn->query($sql2);
    $seek_rownum = $seekresult->num_rows;
    if ($seek_rownum >0) {

        while ($rows = $seekresult->fetch_assoc()) {
            $namelist = $rows['username'];
            if ($namelist!=$username) {
                if ($conn->query("SELECT * FROM relation WHERE username='$username' and friendname='$namelist'")->num_rows > 0 ) {
                    echo "<li style='margin:20px 0;'>" . $namelist . "&nbsp&nbsp&nbsp" . "您的好友" . "</li>";
                }
                elseif ($conn->query("SELECT * FROM friendrequest WHERE touser='$namelist' and fromuser='$username'")->num_rows > 0) {
                    echo "<li style='margin:20px 0;'>" . $namelist . "&nbsp&nbsp&nbsp" . "好友请求等待处理中" . "</li>";
                }
                else {
                    echo "<li style='margin:20px 0;'>" . "<form action=\"seekuser.php?username=$username&password=$secret_password\" method=\"post\">" . "<input type='text' name='searchname' value=$namelist style='border:none;' readonly='true'>" . "&nbsp&nbsp&nbsp&nbsp" . "<input type='submit' value='发送好友请求' />" . "</form>" . "</li>";
                }
            }
            else {
                $seek_rownum-=1;
            }
        }
        echo "一共有$seek_rownum"."个相关用户"."<br>";
    }
    else {
        echo "没有查找到相关用户!";
    }

}

?>

<?php
$search_name = $_POST['searchname'];
if ($search_name) {
    $sql3 = "SELECT * FROM friendrequest WHERE touser='$search_name' and fromuser='$username'";
    $sql4 = "INSERT INTO friendrequest (touser,fromuser,readstatus) VALUES ('$search_name','$username',0)";
    $addresult_1 = $conn->query($sql3);
    if ($addresult_1->num_rows > 0) {
        echo "无法重复发送好友请求，等待对方处理中！";
    }
    else {
        if ($conn->query($sql4) === TRUE) {
            echo "好友请求已发送！";
        } else {
            echo "Error:" . $sql . "<br>" . $conn->error;
        }
    }
}

?>

</body>
</html>