<?php
   include 'lib.php';
   
   $pageTitle = "Client Detail";
  
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
   
   // Handle data update logic
   if (isset($_POST['updateClient']) && !empty($clientId) && !empty($username)) {
      updateClientData($clientId,$username,$_POST);
   }
   
   // Go to Client Check-In
   if (isset($_POST['checkinClient']) && !empty($clientId) && !empty($username)) {
      goToClientCheckin(true);
   }
   
   if (!empty($clientId)) {
      $result = retrieveClientFromId($clientId);
      
      if ($result->num_rows > 0) {
         // Client exists, pull back data and process.
         $clientRow = $result->fetch_assoc();

         // Retrieve history
         $clientModificationHistory = retrieveClientModificationHistory($clientId);
         $clientServiceUsageHistory = retrieveClientServiceUsageHistory($clientId);
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
         
         // Determine whether any data element was changed
         function validateDataUpdated() {
            var currentFirstName = document.getElementById("currentFirstName").value;
            var currentLastName = document.getElementById("currentLastName").value;
            var currentDescription = document.getElementById("currentDescription").value;
            var currentPhoneNumber = document.getElementById("currentPhoneNumber").value;
            
            var firstName = document.getElementById("firstName").value;
            var lastName = document.getElementById("lastName").value;
            var description = document.getElementById("description").value;
            var phoneNumber = document.getElementById("phoneNumber").value;
            
            if ((currentFirstName != firstName) || (currentLastName != lastName) || (currentDescription != description) || (currentPhoneNumber != phoneNumber)) {
               return true;
            }
            return false;
         }
         
         function validateInput() {
            if (validateField("firstName") && validateField("lastName") && validateField("description") && validateValidCharacter("phoneNumber")) {
               if (validateDataUpdated()) {
                  return true;
               } else {
                  alert(clientNoDataUpdated);
                  return false;
               }
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
           displayClientModificationHistory($clientModificationHistory);
           displayClientServiceUsageHistory($clientServiceUsageHistory);
         ?>
   </tbody>
</form>
</body>
</html>
