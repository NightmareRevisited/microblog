<?php
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
    <title>
        <?php
        echo $username."的主页";
        ?>
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


    $time = date("Y-m-d H:i:s");
    $sql2 = "INSERT INTO micro_blog (mb_content,username,mb_time) VALUES ('$content','$username','$time')";
    if ($conn->query($sql2) === TRUE) {
        echo  "发表成功" ."<br>".$time;
    } else {
        echo "Error:" . $sql2 . "<br>" . $conn->error;
    }
}
$conn->close();

?>

</body>
</html>
