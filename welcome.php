<?php

session_start();

$servername = "localhost:3306";
$username = "root";
$password = "Diabl0s3";
$dbname = "microblog";

$conn = new mysqli($servername,$username,$password,$dbname);

if ($conn->connect_error) {
    die("连接失败:".$conn->connect_error);
}

$username = $_POST["username"];
$password = $_POST["password"];

$sql = "SELECT * FROM login_info where username='$username' ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $rows =$result ->fetch_assoc();
    if ($password != $rows["password"]  ) {
        $message = "密码错误,请重新输入！"."<br>"."三秒后跳转至登录页面……";
        $url = "http://microblog.com/login.php";
    }
    else {
        $message =  "欢迎回来 ， $username ！"."<br>"."三秒后跳转至首页……";
        $url = "http://microblog.com/main.php";
        $_SESSION['username'] = $username;

    }
}
else {
    $message = "用户名不存在，请注册！"."<br>"."三秒后跳转至登录页面……";
    $url = "http://microblog.com/login.php";
}

?>


<html>
<head>
    <meta charset="utf-8">
    <title>登陆界面</title>
    <meta  http-equiv = "refresh"   content ="3;
    url = <?php echo $url;  ?> " >
</head>
<body>

<?php
echo $message;
?>


</body>
</html>