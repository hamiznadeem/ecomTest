<?php
    ob_start();
    session_start();
    function pr($data){
        print_r($data);
        return $data;
    }
    function safe_input($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $hostname = "localhost";
    $username = "root";
    $password = "";
    $database = "northwind";

    $conn = mysqli_connect($hostname, $username, $password, $database);
    if(!$conn){
        die("". mysqli_connect_error());
    }else{
        echo "<script> console.log('$database: Database Connected') </script>";
    }
?>