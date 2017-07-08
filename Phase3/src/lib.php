<?php

// Check if a session is valid (username session variable set). If not, direct to login page.
function checkValidSession() {
   $username = $_SESSION["username"];
   // If username does not exist, then route to login screen.
   if (empty($username)) {
      goToLogin(true);
   }
}

function logout($isLogout) {
   if ($isLogout) {
      // Reset username session variable
      $_SESSION["username"] = NULL;
      header("Location: /login.php");
      exit;
   }
}

function goToLocation($isTrue,$location) {
   if ($isTrue) {
      header("Location: " . $location);
      exit;
   }
}

function goToLogin($isLogin) {
   goToLocation($isLogin,"/login.php");
}

function goToUserHome($isUserHome) {
   goToLocation($isUserHome,"/user_home.php");
}

function goToAddNewClient($isAddNewClient) {
   goToLocation($isAddNewClient,"/client_add.php");
}

function goToClientSearch($isClientSearch) {
   goToLocation($isClientSearch,"/client_search.php");
}

function displayText($text) {
   echo "$text";
}

// Display User Home button on top right corner
function displayUserHome() {
   echo '<div style="float: right">
            <button name="userHome" type="submit"  onClick="validationRequired=false">User Home</button>
         </div>';
}

// Display Logout button on top right corner
function displayLogout() {
   echo '<div style="float: right">
            <button name="logout" type="submit" onClick="validationRequired=false">Logout</button>
         </div>';
}

// Display Login submit button
function displayLoginSubmitButton() {
   echo '
               <button name="login" type="submit" onClick="validationRequired=true">Login</button>';
}

// Display Search submit button
function displaySearchSubmitButton() {
   echo '
               <button name="search" type="submit" onClick="validationRequired=true">Search</button>';
}

// Display Add New Client submit button
function displayAddNewClientSubmitButton() {
   echo '
               <button name="addNewClient" type="submit" onClick="validationRequired=false">Add New Client</button>';
}

// Display Add Client submit button
function displayAddClientSubmitButton() {
   echo '
               <button name="addClient" type="submit" onClick="validationRequired=true">Add Client</button>';
}

// Display Client Search submit button
function displayClientSearchSubmitButton() {
   echo '
               <button name="clientSearch" type="submit" onClick="validationRequired=false">Client Search</button>';
}

// Display username and password fields. Used in Login page.
function displayUsernamePasswordField() {
   echo '
            <p>
               <label>Username:</label> 
               <input name="username" required="true" type="text" value="test1" />
            </p>
            <p>
               <label>Password:</label> 
               <input name="pwd" required="true" type="password" value="test1" /></p>
            </p>';
}

// Display empty fields if no data provided or display data provided on field
function displayClientDataField($clientData) {
   $firstName = "";
   $lastName = "";
   $description = "";
   $phoneNumber = "";
   
   if (!empty($clientData)) {
      $firstName = $clientData["firstName"];
      $lastName = $clientData["lastName"];
      $description = $clientData["description"];
      $phoneNumber = $clientData["phoneNumber"];
   }
   
   echo '
            <p>
               <label>First Name (*):</label> 
               <input id="firstName" name="firstName" type="text" value="' . $firstName . '"/>
            </p>
            <p>
               <label>Last Name (*):</label> 
               <input id="lastName" name="lastName" type="text" value="' . $lastName . '"/>
            </p>
            <p>
               <label>Description (*):</label> 
               <input id="lastName" name="description" type="text" value="' . $description . '"/>
            </p>
            <p>
               <label>Phone Number:</label> 
               <input name="phoneNumber" type="text" value="' . $phoneNumber . '"/>
            </p>
            <p>
               <label>(*)</strong> denotes required fields.</label> 
            </p>';
}

function displayClientExistsMessage($clientExists) {
   if ($clientExists) {
      echo "Client already exists. Please search for the client or change input data.";
   }
}

function displayCss() {
   echo "
      <style>
         .hide {
            display: none;
         }
      </style>\n";
}

function displayJsLib() {
   echo "
      validationRequired = false;
      
      function formValidation() {
         if (validationRequired) {
            return validateInput();
         }
         
         return true;
      }
      ";
}

function displayValidateField() {
   echo "
         function validateField(fieldName) {
            var fieldValue = document.getElementById(fieldName).value;
            if (fieldValue == '') {
               return false;
            } else {
               if (fieldValue.includes(';')) {
                  return false;
               }
            }

            return true;
         }";
}

function displayClientSearchResult($result) {
   $searchValid = false;
   $errorMsg = "";
   $rowcnt = $result->num_rows;
   if ($rowcnt > 0 && $rowcnt < 5 ) {
       $searchValid = true;
   } elseif ($rowcnt == 0) {
      $errorMsg = "No Client found, please try again.";
   } elseif ($rowcnt >= 5) {
      $errorMsg = "Too many Clients found, please narrow search criteria(s).";
   }
   
   if ($searchValid) {
      echo "
      <label>
         <strong>Search Results:</strong>
      </label> 
      <table border='1'>
         <thead>
            <tr>
               <th align='left' class='hide'>Client Id</th>
               <th align='left'>First Name</th>
               <th align='left'>Last Name</th>
               <th align='left'>Description</th>
               <th align='left'>Phone Number</th>
            </tr>
         </thead>
         <tbody>";
      
      while($row = $result->fetch_assoc()) {
            echo "
            <tr>
               <td class='hide'>" . $row['clientId'] . "</td>
               <td>" . $row['firstName'] . "</td>
               <td>" . $row['lastName'] . "</td>
               <td>" . $row['description'] . "</td>
               <td>" . $row['phoneNumber'] . "</td>
            </tr>";
      }
      echo "
         </tbody>
      </table>";

   } else {
      echo "<p>
            <label>
              <strong>" . $errorMsg . "</strong>
            </label>
            </p>";
   }     
}

function openConnection() {
   $servername = "localhost";
   $username   = "gatechuser";
   $password   = "gatech123";
   $dbname     = "cs6400_su17_team022";
   
   // Create connection
   $conn = new mysqli($servername, $username, $password, $dbname);
   // Check connection
   if ($conn->connect_error) {
       die("Connection failed: " . $conn->connect_error);
   }
   
   return $conn;
}   

function closeConnection($conn) {
   $conn->close();
}

function executeSql($sql) {
   $conn = openConnection();
   $result = $conn->query($sql);
   closeConnection($conn);
   return $result;
}

function insertSql($sql) {
   $insertId = -1;
   $conn = openConnection();   
   $result = $conn->query($sql);
   if ($result) {
      $insertId = mysqli_insert_id($conn);
   }
   $conn->close();
   return $insertId;
}

function retrieveModificationHistory($clientId) {
   $sql = "SELECT modifiedDateTime, fieldModified, previousValue " .
          "FROM ClientLog " .
          "WHERE clientId = " . $clientId;

   $result = executeSql($sql);
   return $result;
}

function retrieveServiceUsageHistory($clientId) {
   $sql = "SELECT siteId, facilityId, serviceDateTime, description, note " .
          "FROM ClientServiceUsage " .
          "WHERE clientId = " . $clientId;

   $result = executeSql($sql);
   return $result;
}
?>