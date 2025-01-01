<?php
    $host = "localhost";
    $username = "root";
    $password = "";
    $dbname = "qly_thuvien";

    $conn = mysqli_connect($host,$username,$password,$dbname);
    if(!$conn) {
        die("connect db failed" . mysqli_connect_error());
    }
?>