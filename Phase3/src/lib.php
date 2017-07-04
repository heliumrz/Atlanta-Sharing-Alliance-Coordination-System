<?php

function openConnection() {
   $servername = "localhost";
   $username   = "gatechuser";
   $password   = "gatech123";
   $dbname     = "cs6400_su17_team022";
   
   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   
   return $conn;
}   

function closeConnection($conn) {
   $conn->close();
}

function executeSql($sql) {
   $conn = openConnection();
   $result = $conn->query($sql);
   closeConnection($conn);
   return $result;
}

function insertSql($sql) {
   $insertId = -1;
   $conn = openConnection();   
   $result = $conn->query($sql);
   if ($result) {
      $insertId = mysqli_insert_id($conn);
   }
   $conn->close();
   return $insertId;
}

?>