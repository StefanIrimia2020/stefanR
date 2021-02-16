<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "blog";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// sql to create table
$sql = "CREATE TABLE `articole` (
    `id` int(5)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` int(5) NOT NULL DEFAULT '1',
    `titlu` varchar(255) NOT NULL,
    `articol` text NOT NULL,
    `categorie` int(5) DEFAULT NULL,
    `tags` varchar(255) NOT NULL,
    `data_adaugare` datetime NOT NULL,
    `imagine` varchar(255) DEFAULT NULL
  )";

if (mysqli_query($conn, $sql)) {
    echo "Table articole  created successfully";
  } else {
    echo "Error creating table: " . mysqli_error($conn);
  }
  $sql2 = "CREATE TABLE `categorii` (
    `id` int(5)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `nume` varchar(255) NOT NULL,
    `data_adaugare` datetime NOT NULL
  )";
if (mysqli_query($conn, $sql2)) {
    echo "Table categorii  created successfully";
  } else {
    echo "Error creating table: " . mysqli_error($conn);
  }

  $sql3 = "CREATE TABLE `comentarii` (
    `id` int(5)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `post_id` int(5) NOT NULL,
    `nume` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `comentariu` varchar(255) NOT NULL,
    `data_adaugare` datetime NOT NULL
  )";
if (mysqli_query($conn, $sql3)) {
    echo "Table comentarii  created successfully";
  } else {
    echo "Error creating table: " . mysqli_error($conn);
  }

  $sql4 = "CREATE TABLE `utilizatori` (
    `id` int(5)  UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `username` varchar(255) NOT NULL,
    `email` varchar(255) NOT NULL,
    `parola` char(60) NOT NULL
  )";
if (mysqli_query($conn, $sql4)) {
    echo "Table utilizatori created successfully";
  } else {
    echo "Error creating table: " . mysqli_error($conn);
  }

  mysqli_close($conn);

?>