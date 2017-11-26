<html>
<head>
    <meta charset="utf-8">
    <title>登陆结果</title>
</head>
<body>

<?php
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
        echo "密码错误,请重新输入！".PHP_EOL;
    }
    else {
        echo "$username 登陆成功！";
    }
}
else {
    echo "用户名不存在，请注册！".PHP_EOL;
}

?>



</body>
</html>