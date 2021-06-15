<?php 

$conn = mysqli_connect('localhost', 'root', '', 'sikayetsitesi');

if ($conn->connect_error){
    die("Database error: ".$conn->connect_error);
}