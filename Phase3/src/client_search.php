<?php
   include 'lib.php';
  
   $pageTitle = "Client Search";  
   
   session_start();
   $result = null;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));
   
   goToAddNewClient(isset($_POST['addNewClient']));
   
   if (isset($_POST['search']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['description'])) {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $description = $_POST['description'];
      $phoneNumber = $_POST['phoneNumber'];
   
      $sql = "SELECT clientId, firstName, lastName, description, phoneNumber " .
             "FROM Client " .
             "WHERE firstName like '%" . $firstName . "%' " .
             "AND lastName like '%" . $lastName . "%' " .
             "AND description like '%" . $description . "%' ";
               
      if (!empty($phoneNumber )) {
         $sql = $sql . "AND phoneNumber like '%" . $phoneNumber . "%'";
      }

      $result = executeSql($sql);
   }
   
   if (isset($_POST['clientDetail'])) {
      $_SESSION["clientId"] = $_POST["clientId"];
      goToClientDetail(true);
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
   <?php displayBodyHeading(); ?>
      <?php displayFormHeader($MAIN_FORM,$CLIENT_SEARCH_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <?php displayClientDataField($EMPTY_STRING); ?>
            <p>
               <?php displaySearchSubmitButton(); ?>
               <?php displayAddNewClientSubmitButton(); ?>
               <?php displayClientDetailSubmitButtonHidden(); ?>
               <?php displayHiddenField(); ?>
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
