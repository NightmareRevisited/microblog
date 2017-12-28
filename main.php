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
    <script>
        function loadComment(commentid,mb_id,cable) {
            var aj;
            if (window.XMLHttpRequest) {
                aj = new XMLHttpRequest();
            }
            else {
                aj = new ActiveXObject("Microsoft.XMLHTTP");
            }
            aj.onreadystatechange=function () {
                if (aj.readyState == 4 && aj.status == 200) {
                    document.getElementById(commentid).innerHTML = aj.responseText;
                }
            }
            aj.open('get','comment.php?mbid='+mb_id+'&cable='+cable,true);
            aj.send();
        }

        function reply(cid,commentid,mbid,cable) {
            var aj;
            var content = document.getElementById(cid).value;
            if (window.XMLHttpRequest) {
                aj = new XMLHttpRequest();
            }
            else {
                aj = new ActiveXObject("Microsoft.XMLHTTP");
            }
            aj.onreadystatechange=function () {
                if (aj.readyState == 4 && aj.status == 200) {
                    document.getElementById(commentid).innerHTML = aj.responseText;
                }
            }
            aj.open('get','comment.php?cid='+cid+'&content='+content+'&mbid='+mbid+'&cable='+cable,true);
            aj.send();
        }

        function show_self_mb(username) {
            var aj;
            if (window.XMLHttpRequest) {
                aj = new XMLHttpRequest();
            }
            else {
                aj = new ActiveXObject("Microsoft.XMLHTTP");
            }
            aj.onreadystatechange = function () {
                if (aj.readyState == 4 && aj.status==200) {
                    document.getElementById("microblog").innerHTML = aj.responseText;
                }
            }
            aj.open('get','selfmb.php?username='+username,true);
            aj.send();
        }
    </script>
</head>
<body>

<?php

$sql3 = "SELECT * FROM friendrequest where touser='$username' and readstatus='0'";
$fresult = $conn->query($sql3);

?>
<a href="http://microblog.com/messagebox.php?username=<?php echo $username;?>&password=<?php echo base64_encode($password);?>">好友(<?php echo $fresult->num_rows;?>)</a>
&nbsp&nbsp&nbsp
<a href="http://microblog.com/seekuser.php?username=<?php echo $username;?>&password=<?php echo base64_encode($password);?>">查找用户</a>
&nbsp&nbsp&nbsp
<a href="login.php">登出</a>

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

<div id="microblog">

<?php
echo "<button type='button' onclick='show_self_mb(\"$username\")'>我的微博</button>";
$zon = $_POST['zan'];
$comment = $_POST['comment'];
$this_id = $_POST['id'];
if ($zon) {
    $sql5 = "SELECT * FROM zan WHERE username='$username' and mb_id = '$this_id'";
    if ($conn->query($sql5)->num_rows>0) {
        $sql6 = "DELETE FROM zan WHERE username='$username' and mb_id = '$this_id'";
        $conn->query($sql6);
        echo "已取消点赞！<br>";
    }
    else {
        $sql6 = "INSERT INTO zan (username,mb_id) VALUES ('$username','$this_id')";
        $conn->query($sql6);
        echo "已点赞！<br>";
    }
}
if ($comment) {
    $sql7 = "INSERT INTO comment (mb_id,comment_username,mb_comment) VALUES ('$this_id','$username','$comment')";
    $conn->query($sql7);
    echo "评论成功！<br>";
}

?>

<ul>
<?php
$sql4 = "SELECT * FROM micro_blog WHERE micro_blog.username IN ( SELECT DISTINCT friendname FROM relation WHERE relation.username='$username') or micro_blog.username='$username' ORDER BY mb_time DESC";
$mbresult = $conn->query($sql4);
while ($row = $mbresult->fetch_assoc()) {
    $mb_id = $row['blogid'];
    $commentid = 'comment'.$mb_id;
    $mb_content = $row['mb_content'];
    $mb_username = $row['username'];
    $mb_time = $row['mb_time'];
    $cable = $mb_username == $username;
    $sqlx="SELECT count(mb_id) as zannum from zan where mb_id='$mb_id'";
    $xresult = $conn->query($sqlx)->fetch_assoc();
    $zannum=$xresult['zannum'];
    $sqlcn = "SELECT count(mb_id) as cn FROM comment WHERE mb_id='$mb_id'";
    $cnr = $conn->query($sqlcn)->fetch_assoc();
    $commentnum = $cnr['cn'];
    $sqlfc = "SELECT * FROM zan WHERE username='$username' and mb_id='$mb_id'";
    if ($conn->query($sqlfc)->num_rows>0) {
        $color = 'red';
    }
    else {
        $color = 'black';
    }
    echo "<li style='margin:20px 0;'>" ."<form action=\"main.php?username=$username&password=$secret_password\" method=\"post\">" ."<input type='hidden' name='id' value='$mb_id'>".$mb_username ."<br>"."内容:".$mb_content."&nbsp&nbsp&nbsp"."<input type='submit' name='zan' value='赞($zannum)' style='color: $color' />" ."<br>".$mb_time."<br>"."<input type='text' name='comment'   >" . "&nbsp&nbsp&nbsp&nbsp" . "<input type='submit' value='评论' />" ."&nbsp&nbsp&nbsp&nbsp"."<button type='button' onclick='loadComment(\"$commentid\",\"$mb_id\",\"$cable\")'>查看评论($commentnum)</button>"."<br>"."<div id='$commentid'></div>". "</form>". "</li>";
}
?>
</ul>
</div>
</body>
</html>
