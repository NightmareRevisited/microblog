<?php
/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/11/26 16:34
 */

$servername = "localhost:3306";
$username = "root";
$password = "Diabl0s3";
$dbname = "microblog";

$conn = new mysqli($servername,$username,$password,$dbname);

if ($conn->connect_error) {
    die("连接失败:".$conn->connect_error);
}


$username = $_POST['username'];
$password = $_POST['password'];

$sql1 = "SELECT * FROM login_info where username='$username' ";
$result = $conn->query($sql1);

if ($result->num_rows > 0) {
    echo "注册失败，该用户名已存在";
}
else {

    $sql2 = "INSERT INTO login_info (username,password) VALUES ('$username','$password')";

    if ($conn->query($sql2) === TRUE) {
        echo "注册成功" . PHP_EOL;
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

?>