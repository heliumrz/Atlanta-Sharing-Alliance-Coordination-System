<?php
   include 'lib.php';
   
   $pageTitle = "Add New Client";
   
   session_start();
   $result = null;
   $insertSql = null;
   $clientExists = false;
   
   logout(isset($_POST['logout']));
   goToUserHome(isset($_POST['userHome']));   
   goToClientSearch(isset($_POST['cancel']));

   if (isset($_POST['save']) && !empty($_POST['firstName']) && !empty($_POST['lastName']) && !empty($_POST['description'])) {
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
            <p>
               <label>
                  <strong>First Name</strong>
               </label> 
               <input name="firstName" required="" type="text" />
            </p>
            <p>
               <label>
                  <strong>Last Name</strong>
               </label> 
               <input name="lastName" required="" type="text" />
            </p>
            <p>
               <label>
                  <strong>Description</strong>
               </label> 
               <input name="description" required="" type="text" />
            </p>
            <p>
               <label>
                  <strong>Phone Number</strong>
               </label> 
               <input name="phoneNumber" type="text" />
            </p>
            <p>
               <button name="save" type="submit">Save</button>
               <button name="cancel" type="submit">Client Search</button>
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
