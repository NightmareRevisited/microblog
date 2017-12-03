<?php

/**
 * Created by PhpStorm.
 * Author: Yang Changning (thevile@126.com)
 * Time: 2017/11/28 15:34
 */

error_reporting(E_ALL^E_NOTICE);
$username=$_GET['username'];
$secret_password = $_GET['password'];
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

<?php

$sql3 = "SELECT * FROM friendrequest where touser='$username' and readstatus='0'";
$fresult = $conn->query($sql3);

?>
<a href="http://microblog.com/messagebox.php?username=<?php echo $username;?>&password=<?php echo base64_encode($password);?>">好友请求(<?php echo $fresult->num_rows;?>)</a>
&nbsp&nbsp&nbsp
<a href="http://microblog.com/seekuser.php?username=<?php echo $username;?>&password=<?php echo base64_encode($password);?>">查找用户</a>
<style>
    a:visited {color: blue}
</style>


<br>
<form action ='main.php?username=<?php echo $username; ?>&password=<?php echo $secret_password;?>' method = 'post'>
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


?>
<hr>

<ul>
<?php
$sql4 = "SELECT * FROM micro_blog WHERE micro_blog.username IN ( SELECT DISTINCT friendname FROM relation WHERE relation.username='$username') or micro_blog.username='$username' ORDER BY mb_time DESC";
$mbresult = $conn->query($sql4);

while ($row = $mbresult->fetch_assoc()) {
    $mb_id = $row['blogid'];
    $mb_content = $row['mb_content'];
    $mb_username = $row['username'];
    $mb_time = $row['mb_time'];
    echo "<li style='margin:20px 0;'>" .$mb_username ."<br>"."内容:".$mb_content."<br>".$mb_time. "</li>";
}
?>
</ul>

</body>
</html>
