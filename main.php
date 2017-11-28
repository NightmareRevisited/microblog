<?php
error_reporting(E_ALL^E_NOTICE);
?>

<html>
<head>
    <title><?php $username=$_GET['username']; echo $username."的主页"; ?>
    </title>
</head>
<body>
<form action ='main.php?username=<?php echo $username; ?>' method = 'post'>
    <label style="vertical-align: top"> 发布状态： </label>
    <textarea rows='8' cols="50" wrap="virtual" name="content"></textarea>
    <br>
    <input type="submit" value="发表">
</form>

<?php
$content = $_POST['content'];
if ($_POST['content']) {
    $servername = "localhost:3306";
    $sqlname = "root";
    $password = "Diabl0s3";
    $dbname = "microblog";
    $conn = new mysqli($servername,$sqlname,$password,$dbname);

    if ($conn->connect_error) {
        die("连接失败:".$conn->connect_error);
    }

    $time = date("Y-m-d H:i:s");
    $sql = "INSERT INTO micro_blog (mb_content,username,mb_time) VALUES ('$content','$username','$time')";
    if ($conn->query($sql) === TRUE) {
        echo  "发表成功" ."<br>".$time;
    } else {
        echo "Error:" . $sql . "<br>" . $conn->error;
    }
}
else {
    echo "输入不能为空！";
}
?>

</body>
</html>
