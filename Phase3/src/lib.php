<?php

function logout($isLogout) {
   if ($isLogout) {
      header("Location: /login.php");
      exit;
   }
}

function goToLocation($isTrue,$location) {
   if ($isTrue) {
      header("Location: " . $location);
      exit;
   }
}

function goToUserHome($isUserHome) {
   goToLocation($isUserHome,"/user_home.php");
}

function goToAddNewClient($isAddNewClient) {
   goToLocation($isAddNewClient,"/client_add.php");
}

function goToClientSearch($isClientSearch) {
   goToLocation($isClientSearch,"/client_search.php");
}

function displayText($text) {
   echo "$text";
}


function displayUserHome() {
   echo '<div style="float: right">
            <button name="userHome" type="submit">User Home</button>
         </div>';
}

function displayLogout() {
   echo '<div style="float: right">
            <button name="logout" type="submit">Logout</button>
         </div>';
}

function displayClientExistsMessage($clientExists) {
   if ($clientExists) {
      echo "Client already exists. Please search for the client or change input data.";
   }
}

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