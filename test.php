<?php
require "inc_conn.php";
$sql = "SELECT * FROM categories";
$cate_result = mysqli_query($conn, $sql);
$cate_row = mysqli_fetch_assoc($cate_result);
echo"<pre>";
pr($cate_row['CategoryName']);
echo"</pre>";
?>