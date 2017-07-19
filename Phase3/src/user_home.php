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
   } else if (isset($_POST['outstandingRequest'])) {
       header("Location: /view_outstanding_requests.php");
       exit;
   } else if (isset($_POST['requestStatus'])) {
       header("Location: /user_request_status.php");
       exit;
   } else {
      $username = $_SESSION["username"];
      $userRow = retrieveUserData($username);
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
   <?php displayBodyHeading(); ?>
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
				  displayOutstandingRequestSubmitButton();
				  displayRequestStatusSubmitButton();
                  displayHiddenField(); 
               ?>
            </p>
         </div>
      </form>
   </body>
</html>
