<?php
   include 'lib.php';
   
   $pageTitle = "Add New Client";
   
   session_start();
   $result = null;
   $insertSql = null;
   $clientExists = false;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));
   goToUserHome(isset($_POST['formAction']) && ($_POST['formAction'] == 'userHome'));
   goToClientSearch(isset($_POST['clientSearch']));

   if (isset($_POST['addClient']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['description'])) {
      $firstName = $_POST['firstName'];
      $lastName = $_POST['lastName'];
      $description = $_POST['description'];
      $phoneNumber = $_POST['phoneNumber'];
   
      $sql = "SELECT clientId FROM Client " .
             "WHERE firstName = '" . $firstName . "' " .
             "AND lastName = '" . $lastName . "' " .
             "AND description = '" . $description . "' ";
               
      if (!empty($phoneNumber )) {
         $sql = $sql . "AND phoneNumber = '" . $phoneNumber . "'";
      }
      $result = executeSql($sql);
      
      if ($result->num_rows == 0) {
         // No existing client with provided info, therefore, add new client.
         $insertSql = "INSERT INTO Client (clientId,firstName,lastName,description,phoneNumber) " . 
                      "VALUES (NULL,'" . $firstName . "','" . $lastName . "','"  . $description . "','"  . $phoneNumber . "')";
         $clientId = insertSql($insertSql);
         
         if ($clientId > 0) {
            $_SESSION["clientId"] = $clientId;
            header("Location: /client_detail.php");
            exit;
         } else {
            echo "Error: " . $insertSql . "<br>";
         }            
      } else {
         $clientExists = true;
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
      <?php displayFormHeader($MAIN_FORM,$CLIENT_ADD_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
               displayUserHome();
            ?>
         </div>
         <br>
         <div>
            <?php displayClientDataField("");?>
            <p>
               <?php displayAddClientSubmitButton(); ?>
               <?php displayClientSearchSubmitButton(); ?>
               <?php displayHiddenField(); ?>               
            </p>
         </div>
         <?php
            displayClientExistsMessage($clientExists);
         ?>
      </tbody>
   </table>
</form>
</body>
</html>
