<?php
   include 'lib.php';
  
   $pageTitle = "User Home";
   
   session_start();
   $result = null;
   $userRow = null;

   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   
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
      <?php displayTitle($pageTitle); ?>
      <?php displayCss();?>
      <script>
         <?php displayJavascriptLib();?>
      </script>
   </head>
   <body>
      <?php displayFormHeader($MAIN_FORM,$USER_HOME_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
            ?>
         </div>
         <br>
         <div>
            <?php displayUserHomeDataField($userRow); ?>
            <p>
               <?php 
                  displayClientSearchSubmitButton();
                  displayHiddenField(); 
               ?>
            </p>
         </div>
      </form>
   </body>
</html>
