<?php
   include 'lib.php';
   
   $pageTitle = "Client Detail";
  
   session_start();
   $currentClient = null;
   $clientRow = null;
   $clientUpdated = false;
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

   if (isset($_POST['updateClient']) && !empty($clientId) && !empty($username)) {
      $updatedFirstName = $_POST['firstName'];
      $updatedLastName = $_POST['lastName'];
      $updatedDescription = $_POST['description'];
      $updatedPhoneNumber = $_POST['phoneNumber'];
      
      $sql = "SELECT clientId, firstName, lastName, description, phoneNumber FROM Client " .
             "WHERE clientId = " . $clientId;
      $currentClient = executeSql($sql)->fetch_assoc();
      
      $currentFirstName = $currentClient['firstName'];
      $currentLastName = $currentClient['lastName'];
      $currentDescription = $currentClient['description'];
      $currentPhoneNumber = $currentClient['phoneNumber'];
      
      if (isDifferent($currentFirstName,$updatedFirstName)) {
         $clientUpdated = true;
         insertClientLog($clientId,$username,"FirstName",$currentFirstName);
      }
      
      if (isDifferent($currentLastName,$updatedLastName)) {
         $clientUpdated = true;
         insertClientLog($clientId,$username,"LastName",$currentLastName);
      }
      
      if (isDifferent($currentDescription,$updatedDescription)) {
         $clientUpdated = true;
         insertClientLog($clientId,$username,"Description",$currentDescription);
      }
      
      if (isDifferent($currentPhoneNumber,$updatedPhoneNumber)) {
         $clientUpdated = true;
         insertClientLog($clientId,$username,"PhoneNumber",$currentPhoneNumber);
      }
      
      // If any field was updated, update client record
      if ($clientUpdated) {
         updateClient($clientId,$updatedFirstName,$updatedLastName,$updatedDescription,$updatedPhoneNumber);
      }
   }
   
   if (!empty($clientId)) {
      $sql = "SELECT clientId, firstName, lastName, description, phoneNumber FROM Client " .
             "WHERE clientId = " . $clientId;

      $result = executeSql($sql);
      
      if ($result->num_rows > 0) {
         // Client exists, pull back data and process.
         $clientRow = $result->fetch_assoc();

         // Retrieve history
         $clientModificationHistory = retrieveModificationHistory($clientId);
         $clientServiceUsageHistory = retrieveServiceUsageHistory($clientId);
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
            if (validateField("firstName") && validateField("lastName") && validateField("description") && validateValidCharacter("phoneNumber")) {
               return true;
            } else {
               alert(clientRequiredField);
               return false;
            }
         }
      </script>
   </head>
   <body>
      <?php displayFormHeader($MAIN_FORM,$CLIENT_DETAIL_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <?php displayClientDataField($clientRow);?>
            <p>
               <?php displayUpdateClientSubmitButton(); ?>
               <?php displayCheckinClientSubmitButton(); ?>
               <?php displayClientSearchSubmitButton(); ?>
               <?php displayHiddenField(); ?>               
            </p>
         </div>
         <?php
           displayModificationHistory($clientModificationHistory);
           displayServiceUsageHistory($clientServiceUsageHistory);
         ?>
   </tbody>
</form>
</body>
</html>
