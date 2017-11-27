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
    $message = "注册失败，该用户名已存在"."<br>"."3秒后返回注册页面……";
    $url = "http://microblog.com/register.php";
}
else {
    $sql2 = "INSERT INTO login_info (username,password) VALUES ('$username','$password')";
    if ($conn->query($sql2) === TRUE) {
        $message = "注册成功" ."<br>"."3秒后返回登陆页面……";
        $url = "http://microblog.com/login.php";
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}
$conn->close();
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
