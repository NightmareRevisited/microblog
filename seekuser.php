<?php

/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/11/30 1:14
 */

error_reporting(E_ALL^E_NOTICE);
$username=$_GET['username'];
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
    echo "一共有$seek_rownum"."个相关用户";
    $rows = $seekresult->fetch_assoc();
}
?>

</body>
</html>