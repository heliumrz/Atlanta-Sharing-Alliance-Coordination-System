<?php
   include 'lib.php';
   
   $pageTitle = "Add New Client";
   
   session_start();
   $result = null;
   $insertSql = null;
   $clientExists = false;
   
   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   logout(isset($_POST['logout']));
   goToUserHome(isset($_POST['userHome']));   
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
      <title><?php displayText($pageTitle);?></title>
      <?php displayCss();?>
      <script>
         <?php displayJsLib();?>
         <?php displayValidateField();?>
      
         function validateInput() {
            if (validateField("firstName") && validateField("firstName") && validateField("firstName")) {
               return true;
            } else {
               alert("First Name, Last Name, and Description are required in search. \nThe following characters are not allowed: ;");
               return false;
            }
         }
      </script>
   </head>
   <body>
      <form action="/client_add.php" method="post">
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
            <?php displayClientDataField("");?>
            <p>
               <?php displayAddClientSubmitButton(); ?>
               <?php displayClientSearchSubmitButton(); ?>
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
