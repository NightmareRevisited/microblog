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
    <title>信息箱</title>
</head>
<body>

<?php
$sql2 = "SELECT * FROM friendrequest where username='$username' and readstatus='0'";
$fresult = $conn->query($sql2);
$unread_rownum = $fresult->num_rows;
echo "<ul>";
for ($i=0;$i<$unread_rownum;$i++) {

}
echo "</ul>";
?>

</body>
</html>