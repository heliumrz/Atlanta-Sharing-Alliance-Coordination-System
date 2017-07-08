<?php
   include 'lib.php';
  
   $pageTitle = "Client Search";  
   
   session_start();
   $result = null;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   logout(isset($_POST['logout']));
   goToUserHome(isset($_POST['userHome']));
   goToAddNewClient(isset($_POST['addNewClient']));
   
   if (isset($_POST['search']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['description'])) {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $description = $_POST['description'];
      $phoneNumber = $_POST['phoneNumber'];
   
      $sql = "SELECT clientId, firstName, lastName, description, phoneNumber FROM Client " .
             "WHERE firstName like '%" . $firstName . "%' " .
             "AND lastName like '%" . $lastName . "%' " .
             "AND description like '%" . $description . "%' ";
               
      if (!empty($phoneNumber )) {
         $sql = $sql . "AND phoneNumber like '%" . $phoneNumber . "%'";
      }

      $result = executeSql($sql);
   } 
?>
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
      <?php displayCss();?>
      <script>
         <?php displayJsLib();?>
         <?php displayValidateField();?>
      
         function validateInput() {
            if (validateField("firstName") && validateField("lastName") && validateField("description")) {
               return true;
            } else {
               alert("First Name, Last Name, and Description are required. \nThe following characters are not allowed in fields: ;");
               return false;
            }
         }
      </script>
   </head>
   <body>
      <form id="mainForm" action="/client_search.php" method="post" onSubmit="return formValidation()">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
            </div>
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <?php displayClientDataField(""); ?>
            <p>
               <?php displaySearchSubmitButton(); ?>
               <?php displayAddNewClientSubmitButton(); ?>
            </p>
         </div>
         <?php
            if (isset($_POST['search'])) {
               displayClientSearchResult($result);
            }
        ?>
      </form>
   </body>
</html>
