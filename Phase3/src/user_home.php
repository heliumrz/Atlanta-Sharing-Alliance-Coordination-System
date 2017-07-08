<?php
   include 'lib.php';
  
   $pageTitle = "User Home";
   
   session_start();
   $result = null;
   $userRow = null;

   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   logout(isset($_POST['logout']));
   
   if (isset($_POST['clientSearch'])) {
      header("Location: /client_search.php");
      exit;
   } else {
      $username = $_SESSION["username"];
            
      $sql = "SELECT firstName, lastName, email FROM User " .
             "WHERE username = '" . $username . "' ";

      $result = executeSql($sql);
      $userRow = $result->fetch_assoc();
   } 
?>
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
   </head>
   <body>
      <form action="/user_home.php" method="post">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
            </div>
            <?php 
               displayLogout();
            ?>
         </div>
         <br>
         <div>
            <p>
               <label>
                  First Name: <?php echo $userRow['firstName']?>
               </label> 
            </p>
            <p>
               <label>
                  Last Name: <?php echo $userRow['lastName']?>
               </label> 
            </p>
            <p>
               <label>
                  Email: <?php echo $userRow['email']?>
               </label> 
            </p>
            <p>
               <label>
                  <strong>Actions</strong>
               </label> 
            </p>
            <p>
               <?php displayClientSearchSubmitButton(); ?>
            </p>
         </div>
      </form>
   </body>
</html>
