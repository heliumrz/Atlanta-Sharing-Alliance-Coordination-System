<?php
   include 'lib.php';
  
   $pageTitle = "User Home";
   
   session_start();
   $result = null;
   $userRow = null;

   // Ensure session is valid. If not, go to login page.
   checkValidSession();
   
   // Inlude in all pages
   logout(isset($_POST['formAction']) && ($_POST['formAction'] == 'logout'));

   if (isset($_POST['clientSearch'])) {
      goToClientSearch(true);
   } else if (isset($_POST['itemSearch'])) {
       goToItemSearch(true);
   } else if (isset($_POST['outstandingRequest'])) {
       header("Location: /view_outstanding_requests.php");
       exit;
   } else if (isset($_POST['requestStatus'])) {
       header("Location: /user_request_status.php");
       exit;
   } else if (isset($_POST['listServices'])) {
       header("Location: /services.php");
       exit;
   } else {
      $username = $_SESSION["username"];
      $userRow = retrieveUserData($username);
   } 
?>
<html>
   <head>
      <?php displayTitle($pageTitle); ?>
      <?php displayCss();?>
      <script>
         <?php displayJavascriptLib();?>
      </script>
   </head>
   <?php displayBodyHeading(); ?>
      <?php displayFormHeader($MAIN_FORM,$USER_HOME_URL); ?>
         <div>
            <?php displayPageHeading($pageTitle); ?>            
            <?php 
               displayLogout();
            ?>
         </div>
         <br>
         <div>
            <?php displayUserHomeDataField($userRow); ?>
            <p>
               <?php 
                  displayClientSearchSubmitButton();
                  displayItemSearchSubmitButton();
				 
			  	$sql = "SELECT facilityId " .
			  	"FROM `user`, `sitetoservice` " .
			  	"WHERE `user`.`SiteId` = `sitetoservice`.`SiteId` AND username = '" . $_SESSION["username"] . "' AND facilityId IN " .
			  	"	(SELECT facilityId " .
			  	"	FROM `service` " .
			  	"	WHERE facilityId IN (SELECT `foodbank`.`FacilityId` from `foodbank`))";
			  	") ";

			    $result = executeSql($sql);
			  	$userFoodbank = null;
			  	if ($result->num_rows > 0) {
					displayOutstandingRequestSubmitButton();
			  	} 
				
                  displayRequestStatusSubmitButton();
                  displayServicesSubmitButton();
                  displayHiddenField(); 
               ?>
            </p>
         </div>
      </form>
   </body>
</html>
