<?php

static $INVALID_USER_PASS = "Username and password are invalid. Please try again."; 
static $EMPTY_STRING = "";
static $LOGIN_URL = "/login.php";
static $USER_HOME_URL = "/user_home.php";
static $CLIENT_SEARCH_URL = "/client_search.php";
static $CLIENT_ADD_URL = "/client_add.php";
static $CLIENT_DETAIL_URL = "/client_detail.php";
static $CLIENT_CHECKIN_URL = "/client_checkin.php";
static $MAIN_FORM = "mainForm";

// Display CSS styling
function displayCss() {
   echo "
      <style>
         .hide {
            display: none;
         }
         table.altcolor th {
            border: 1px solid black;
            background-color: #ecede8;
         }
         table.altcolor tr:nth-child(even) {
            background-color: #ecede8;
         }
         table.datatable {
            border: solid black;
         }
         table.datatable th, tr, td {
            border: solid black;
            height: 40px;
         }
      </style>\n";
}

// Display Javascript library functions
function displayJavascriptLib() {
   echo '
         validationRequired = false;
         clientRequiredField = "First Name, Last Name, and Description are required in search. \nThe following characters are not allowed: ;";
         checkinRequiredField = "Facility and Service Description are required. \nThe following characters are not allowed: ;";
         clientNoDataUpdated="No data was updated. Please update data fields and try again.";

         function mainFormValidation() {
            if (validationRequired) {
               return validateInput();
            }

            return true;
         }
         ' . "
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
         }

         function validateValidCharacter(fieldName) {
            var fieldValue = document.getElementById(fieldName).value;
            if (fieldValue.length > 0 && fieldValue.includes(';')) {
               return false;
            }

            return true;
         }

         function handleClientDetail(clientIdSelected) {
            document.getElementById('clientId').value = clientIdSelected;
            document.getElementById('clientDetail').click();
         }

         function submitMainForm(action) {
            document.getElementById('formAction').value = action;
            document.getElementById('mainForm').submit();
         }" . "\n";
}

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

function goToClientDetail($isClientDetail) {
   goToLocation($isClientDetail,"/client_detail.php");
}

function goToClientCheckin($isClientCheckin) {
   goToLocation($isClientCheckin,"/client_checkin.php");
}

// Display the text provided
function displayText($text) {
   echo "$text";
}

// Display title
function displayTitle($pageTitle) {
   echo "
      <title>" . $pageTitle . "</title>";
}

// Display page heading
function displayPageHeading($pageHeading) {
   echo '
            <div style="float: left"><strong>' . $pageHeading . '</strong></div>';
}

// Display User Home button on top right corner
function displayUserHome() {
   echo '<div style="float: right">
            <button id="userHome" name="userHome" type="button" onClick="submitMainForm(' . "'userHome'" . ')">User Home</button>
         </div>';
}

// Display Logout button on top right corner
function displayLogout() {
   echo '<div style="float: right">
            <button id="logout" name="logout" type="button" onClick="submitMainForm(' . "'logout'" . ')">Logout</button>
         </div>';
}

// Display form header
function displayFormHeader($formName,$actionUrl) {
   echo '
      <form id="' . $formName . '" action="' . $actionUrl . '" method="post" onSubmit="return ' . $formName . 'Validation()">';
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

// Display a hidden Client Detail submit button
function displayClientDetailSubmitButton() {
   echo '
               <button id="clientDetail" name="clientDetail" type="submit" onClick="validationRequired=false">Client Detail</button>';
}

// Display a hidden Client Detail submit button
function displayClientDetailSubmitButtonHidden() {
   echo '
               <button id="clientDetail" name="clientDetail" type="submit" onClick="validationRequired=false" hidden="hidden">Client Detail</button>';
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

// Display Update Client submit button
function displayUpdateClientSubmitButton() {
   echo '
               <button id="updateClient" name="updateClient" type="submit" onClick="validationRequired=true">Update Info</button>';
}

// Display Check-In Client submit button
function displayCheckinClientSubmitButton() {
   echo '
               <button id="checkinClient" name="checkinClient" type="submit" onClick="validationRequired=false">Check-In Client</button>';
}

// Display Check-In submit button
function displayCheckinSubmitButton() {
   echo '
               <button id="checkin" name="checkin" type="submit" onClick="validationRequired=true">Check-In</button>';
}

// Display hidden fields
function displayHiddenField() {
   echo '
               <input id="formAction" name="formAction" type="hidden" />
               <input id="clientId" name="clientId" type="hidden"/>';
}

// Display username and password fields. Used in Login page.
function displayUsernamePasswordField() {
   echo '
            <p>
               <label>Username:</label>
               <input name="username" required="true" type="text" value="emp1" />
            </p>
            <p>
               <label>Password:</label>
               <input name="pwd" required="true" type="password" value="gatech123" /></p>
            </p>';
}

// Display username and password fields. Used in Login page.
function displayUserHomeDataField($userRow) {
   echo '
            <p>
               <label>
                  First Name: ' . $userRow['firstName'] . '
               </label> 
            </p>
            <p>
               <label>
                  Last Name: ' . $userRow['lastName'] . '
               </label> 
            </p>
            <p>
               <label>
                  Email: ' . $userRow['email'] . '
               </label> 
            </p>
            <p>
               <label>
                  User Type: ' . $userRow['userType'] . '
               </label> 
            </p>
            <p>
               <label>
                  <strong>Actions</strong>
               </label> 
            </p>';
}

// Display user home data
function displayUserHomeDataFieldTable($userRow) {
   echo '
  <table class="datatable">
    <tr>
      <td align="left">First Name:</td>
      <td align="left">' . $userRow['firstName'] . '</td>
    </tr>
    <tr>
      <td align="left">Last Name:</td>
      <td align="left">' . $userRow['lastName'] . '</td>
    </tr>
    <tr>
      <td align="left">Email:</td>
      <td align="left">' . $userRow['email'] . '</td>
    </tr>
    <tr>
      <td align="left">Email:</td>
      <td align="left">' . $userRow['userType'] . '</td>
    </tr>
  </table>';
}

// Display empty fields if no data provided or display data provided on field
function displayClientDataField($clientData) {
   $firstName = "Joe";
   $lastName = "Client";
   $description = "TestID";
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
               <input id="description" name="description" type="text" value="' . $description . '"/>
            </p>
            <p>
               <label>Phone Number:</label>
               <input id="phoneNumber" name="phoneNumber" type="text" value="' . $phoneNumber . '"/>
            </p>
            <p>
               <label>(*)</strong> denotes required fields.</label>
            </p>
            <input id="currentFirstName" name="" type="hidden" value="' . $firstName . '"/>
            <input id="currentLastName" name="" type="hidden" value="' . $lastName . '"/>
            <input id="currentDescription" name="" type="hidden" value="' . $description . '"/>
            <input id="currentPhoneNumber" name="" type="hidden" value="' . $phoneNumber . '"/>
            ';
            
}

// Display client data in read only format
function displayClientDataFieldRO($clientData) {
   $firstName = $clientData["firstName"];
   $lastName = $clientData["lastName"];
   $description = $clientData["description"];
   $phoneNumber = $clientData["phoneNumber"];
   
   echo '
            <p>
               <label>First Name: ' . $firstName . '</label>
            </p>
            <p>
               <label>Last Name: ' . $lastName . '</label>
            </p>
            <p>
               <label>Description: ' . $description . '</label>
            </p>
            <p>
               <label>Phone Number: ' . $phoneNumber . '</label>
            </p>';
}

// Display Client Check-In data fields
function displayClientCheckinDataFieldLabel() {
   $EMPTY_STRING = "";
   echo '
            <p>
               <label><strong>Check-In Details</strong></label>
            </p>   
            <p>
               <label>Check-In Date/Time (*):</label>
               <input id="checkinDateTime" name="checkinDateTime" type="text" value="' . $EMPTY_STRING . '"/>
            </p>
            <p>
               <label>Facility (*):</label>
               <input id="checkinDateTime" name="checkinDateTime" type="text" value="' . $EMPTY_STRING . '"/>
            </p>
            <p>
               <label>Service Description (*):</label>
               <input id="description" name="description" type="text" value="' . $EMPTY_STRING . '"/>
            </p>
            <p>
               <label>Note:</label>
               <input id="note" name="note" type="text" value="' . $EMPTY_STRING . '"/>
            </p>
            <p>
               <label>(*) denotes required fields.</label>
            </p>';
}

function displayClientCheckinDataField($siteId) {
   $EMPTY_STRING = "";
   echo '
            <p>
               <label><strong>Check-In Details</strong></label>
            </p>   
  <table class="datatable">
    <col width="20%">
    <col width="80%">
    <tr>
      <td align="right">Facility (*):</td>
      <td align="left">' . 
         displaySiteFacility($siteId) . '
      </td>
    </tr>
    <tr>
      <td align="right">Service Description (*):</td>
      <td align="left"><input id="description" name="description" type="text" style="width:100%" value="' . $EMPTY_STRING . '"/></td>
    </tr>
    <tr>
      <td align="right">Notes:</td>
      <td align="left"><input id="note" name="note" type="text" style="width:100%" value="' . $EMPTY_STRING . '"/></td>
    </tr>
  </table>
            <p>
               <label>(*) denotes required fields.</label>
            </p>';
  
}

function displayClientExistsMessage($clientExists) {
   if ($clientExists) {
      echo "Client already exists. Please search for the client or change input data.";
   }
}

// Display Client Search results in a table format
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
      <table border='1' class='altcolor'>
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
            <tr onClick='handleClientDetail(" . $row['clientId'] . ")'>
               <td class='hide'>" . $row['clientId'] . "</td>
               <td>" . $row['firstName'] . "</td>
               <td>" . $row['lastName'] . "</td>
               <td>" . $row['description'] . "</td>
               <td>" . $row['phoneNumber'] . "</td>
            </tr>";
      }
      echo "
         </tbody>
      </table>
      <br>
      <label><i>Click row for more detail.</i></label>

      ";

   } else {
      echo "<p>
            <label>
              <strong>" . $errorMsg . "</strong>
            </label>
            </p>";
   }
}

// Display client data modification history
function displayClientModificationHistory($result) {
   echo "
      <label>
         <strong>Modification History:</strong>
      </label> 
      <table border='1' class='altcolor'>
         <thead>
           <tr>
             <th>Modified DateTime</th>
             <th>Field Modified</th>
             <th>Previous Value</th>
           </tr>
         </thead>
         <tbody>";
   
   while($row = $result->fetch_assoc()) {
         echo "
            <tr>
               <td>" . $row['modifiedDateTime'] . "</td>
               <td>" . $row['fieldModified'] . "</td>
               <td>" . $row['previousValue'] . "</td>
            </tr>";
   }
   
   echo "
         </tbody>
      </table>
      <p></p>";
}

// Display client service usage history
function displayClientServiceUsageHistory($result) {
   echo "
      <label>
         <strong>Service Usage History:</strong>
      </label> 
      <table border='1' class='altcolor'>
         <thead>
         <tr>
            <th>Site Name</th>
            <th>Facility Name</th>
            <th>Service DateTime</th>
            <th>Description</th>
            <th>Note</th>
         </tr>
         </thead>
         <tbody>";
   
   while($row = $result->fetch_assoc()) {
         echo "
            <tr>
               <td>" . $row['siteName'] . "</td>
               <td>" . $row['facilityName'] . "</td>
               <td>" . $row['serviceDateTime'] . "</td>
               <td>" . $row['description'] . "</td>
               <td>" . $row['note'] . "</td>
            </tr>";
   }
   echo "
         </tbody>
      </table>";
}

// Display all facility associated with a site
function displaySiteFacility($siteId) {
   $result = retrieveFacilityFromSite($siteId);
   $str = "
      <select id='facilityId' name='facilityId' style='width:100%'>";
   
   while($row = $result->fetch_assoc()) {
      $str = $str . "
         <option value='" . $row['facilityId'] . "'>" . $row['facilityName'] . "</option>";
   }
   $str = $str . "
      </select>";
   return $str;
}

// Open a mysql connection
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

// Close a mysql connection
function closeConnection($conn) {
   $conn->close();
}

// Execute a sql statement
function executeSql($sql) {
   $conn = openConnection();
   $result = $conn->query($sql);
   closeConnection($conn);
   return $result;
}

// Insert a record and retrieve the last generated id for the insert
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

// Retrieve Client Modification history for a client
function retrieveClientModificationHistory($clientId) {
   $sql = "SELECT modifiedDateTime, fieldModified, previousValue " .
          "FROM ClientLog " .
          "WHERE clientId = " . $clientId . " " . 
          "ORDER BY modifiedDateTime DESC";

   $result = executeSql($sql);
   return $result;
}

// Retrieve ClientServiceUsage history for a client
function retrieveClientServiceUsageHistory($clientId) {
   $sql = "SELECT sit.shortName siteName, cse.facilityName, serviceDateTime, description, note " .
          "FROM ClientServiceUsage csu, Site sit, ClientService cse " .
          "WHERE csu.siteId = sit.siteId " .
          "AND csu.facilityId = cse.facilityId " .
          "AND clientId = " . $clientId . " " . 
          "ORDER BY serviceDateTime DESC";

   $result = executeSql($sql);
   return $result;
}

// Check if two values are different
function isDifferent($firstVal,$secondVal) {
   if ($firstVal != $secondVal) {
      return true;
   }
   
   return false;
}

// Insert into ClientLog with data provided
function insertClientLog($clientId,$username,$fieldModified,$previousValue) {
   $sql = "INSERT INTO ClientLog (clientId,username,modifiedDateTime,fieldModified,previousValue) " . 
          "VALUES (" . $clientId . ",'" . $username . "',now(),'" . $fieldModified . "','" . $previousValue . "')";
   //echo "insertClientLog sql: " . $sql;
   return insertSql($sql);
}

// Update Client with data provided
function updateClient($clientId,$firstName,$lastName,$description,$phoneNumber) {
   $sql = "UPDATE Client " . 
          "SET firstName = '" . $firstName . "', lastName = '" . $lastName . "', description = '" . $description . 
          "', phoneNumber = '" . $phoneNumber . "' " . 
          "WHERE clientID = " . $clientId ;
   //echo "updateClient sql: " . $sql;
   return executeSql($sql);
}

function retrieveUserData($username) {
   $sql = "SELECT firstName, lastName, email, userType " . 
          "FROM User " .
          "WHERE username = '" . $username . "' ";

   $result = executeSql($sql);
   return $result->fetch_assoc();
}

function retrieveClientFromId($clientId) {
   $sql = "SELECT clientId, firstName, lastName, description, phoneNumber " . 
           "FROM Client " .
          "WHERE clientId = " . $clientId;

   $result = executeSql($sql);
   return $result;
}

function updateClientData($clientId,$username,$updatedData) {
   $clientUpdated = false;   
   $updatedFirstName = $updatedData['firstName'];
   $updatedLastName = $updatedData['lastName'];
   $updatedDescription = $updatedData['description'];
   $updatedPhoneNumber = $updatedData['phoneNumber'];
   
   $sql = "SELECT clientId, firstName, lastName, description, phoneNumber " .
          "FROM Client " .
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

function retrieveFacilityFromSite($siteId) {
   $sql = "SELECT cse.facilityId, cse.facilityName " . 
          "FROM SiteToService sts, ClientService cse " .
          "WHERE sts.facilityId = cse.facilityId " . 
          "AND sts.siteId = " . $siteId;

   $result = executeSql($sql);
   return $result;
}

function retrieveSiteFromUser($username) {
   $sql = "SELECT siteId " . 
          "FROM User " .
          "WHERE username = '" . $username . "'";

   $result = executeSql($sql);
   $row = $result->fetch_assoc();
   return $row['siteId'];
}

// Insert into Client Service Usage
function addClientServiceUsage($clientId,$siteId,$facilityId,$username,$description,$note) {
   $sql = "INSERT INTO ClientServiceUsage (clientId,siteId,facilityId,username,serviceDateTime,description,note) " . 
          "VALUES (" . $clientId . "," . $siteId . "," . $facilityId . "," . "'" . $username . "',now(),'" . $description . "','" . $note . "')";
   // echo "addClientServiceUsage sql: " . $sql;
   return insertSql($sql);
}
?>