<?php
include 'lib.php';

$pageTitle = "Services Directory";

if (!isset($_SESSION)) {
    session_start();
}
$siteId = $_SESSION['siteId'];
logout(isset($_POST['logout']));
goToUserHome(isset($_POST['userHome']));
goToClientSearch(isset($_POST['cancel']));

// if (isset($_POST['save']) && $_POST['serviceType'] != '0' ) {
//
//     $serviceType = $_POST['serviceType'];
//     $_SESSION["serviceType"] = $serviceType;
//     header('Location: create_service.php');
//     exit();
// }
?>
<html>
   <head>
      <title><?php displayText($pageTitle);?></title>
   </head>
   <?php displayBodyHeading(); ?>
       <div>
            <div style="float: right">
            <form action="./user_home.php">
                <input type="submit" value="User Home" />
            </form>
        </div>
            <div style="float: right">
            <form action="./login.php">
                <input type="submit" value="Logout" />
            </form>
            <!-- <?php
               displayLogout();
               displayUserHome();
            ?> -->
         </div>
       <h2>Client Services:</h2>
   </div>
       <?php
       $services = getClientServicesForSite($siteId);
       displayServicesTable($services);
       ?>
       <h2>Food Banks:</h2>
       <?php
       $foodbanks = getFoodBankForSite($siteId);
       displayFoodbankTable($foodbanks);
       ?>
      <!-- <form action="./add_service.php" method="post">
         <div>
            <div style="float: left"><strong><?php displayText($pageTitle);?></strong>
         <br>
         <div>
            <p>
               <label>
                  <strong>Select the type of service</strong>
               </label>
            <select id="serviceType" name="serviceType">
                <option value="0">--Select Service--</option>
                <option value="foodbank">Food Bank</option>
                <option value="foodpantry">Food Pantry</option>
                <option value="soupkitchen">Soup Kitchen</option>
                <option value="shelter">Shelter</option>
            </select>
            </p>
            <p>
               <button name="save" type="submit">Next</button>
            </p>
         </div>
     </form> -->
	</body>
</html>
