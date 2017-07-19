<?php
   include 'lib.php';
   
   $pageTitle = "Client Check-In";
  
   session_start();
   $currentClient = null;
   $clientRow = null;
   $clientModificationHistory = null;
   $clientServiceUsageHistory = null;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));
   goToClientSearch(isset($_POST['clientSearch']));

   $clientId = $_SESSION["clientId"];
   $username = $_SESSION["username"];
   $siteId = retrieveSiteFromUser($username);
   
   // Go to Client Check-In
   if (isset($_POST['clientDetail']) && !empty($clientId) && !empty($username)) {
      goToClientDetail(true);
   }
   
   // Process checkin logic
   if (isset($_POST['checkin']) && !empty($clientId) && !empty($username)) {
      addClientServiceUsage($clientId,$siteId,$_POST['facilityId'],$username,$_POST['description'],$_POST['note']);
      goToClientDetail(true);
   }
   
   if (!empty($clientId)) {
      $result = retrieveClientFromId($clientId);
      
      if ($result->num_rows > 0) {
         // Client exists, pull back data and process.
         $clientRow = $result->fetch_assoc();
      } else {
         // If no client exist, go to Client Search screen
         goToClientSearch(true);          
      }
   } 
?>
<html>
   <head>
      <?php 
         displayTitle($pageTitle);
         displayCss();
      ?>
      <script>
         <?php displayJavascriptLib();?>
      
         function validateInput() {
            if (validateField("description") && validateValidCharacter("note")) {
               return true;
            } else {
               alert(checkinRequiredField);
               return false;
            }
         }
      </script>
   </head>
   <?php displayBodyHeading(); ?>
      <?php displayFormHeader($MAIN_FORM,$CLIENT_CHECKIN_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <?php displayClientDataFieldRO($clientRow);?>
            <?php displayClientCheckinDataField($siteId);?>
            <p>
               <?php displayCheckinSubmitButton(); ?>
               <?php displayClientDetailSubmitButton(); ?>
               <?php displayClientSearchSubmitButton(); ?>
               <?php displayHiddenField(); ?>
            </p>
         </div>
   </tbody>
</form>
</body>
</html>
