<?php
   include 'lib.php';
   
   $pageTitle = "Login";  
   
   ob_start();
   session_start();
   $loginvalid = false;
   
   // Check whether login data was provided
   if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['pwd'])) {
      $user = $_POST['username'];
      $pwd = $_POST['pwd'];
   
      $sql    = "SELECT password FROM User WHERE username = '" . $user . "'";
      $result = executeSql($sql);
   
      if ($result->num_rows > 0) {
          // output data of each row
          $row = $result->fetch_assoc();
          //echo "password: " . $row["password"] . "<br>";
          if ($row["password"] == $pwd) {
             $loginvalid = true;
          }
          $_SESSION["username"] = $user;
      }
   
      if ($loginvalid == true) {
         // Navigate to User Home if successful login
         header("Location: /user_home.php");
         exit;
      }
   } 
?>
   
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
   </head>
   <body>
      <form action="/login.php" method="post">
         <div style="float: left">
            <label><strong><?php displayText($pageTitle);?></strong></label>
         </div>
       <br>
       <div>
         <?php displayUsernamePasswordField(); ?>
       <p>
         <?php displayLoginSubmitButton(); ?>
       </p>
       <p>
         <?php 
            if (isset($_POST['login']) && $loginvalid == false) {
               echo "Username and password are invalid. Please try again.";
            }
         ?>
       </p></div>
       
     </form>
   </body>
</html>
