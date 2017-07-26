<?php
include 'lib.php';

$pageTitle = "Services Directory";

if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['message']="";
$siteId = $_SESSION['siteId'];
logout(isset($_POST['logout']));
goToUserHome(isset($_POST['userHome']));
goToClientSearch(isset($_POST['cancel']));

if (!empty($_GET['delete']) && !empty($_GET['type'])) {
    $facilityId_del = $_GET['delete'];
    $facility_del_type = $_GET['type'];
    //first make sure we have more than one facility
    if (getCountOfFacilitiesForSite($siteId) > 1) {
        if (deleteService($facilityId_del)){
            $_SESSION['message'] = "The Facility with ID ".$facilityId_del." is removed.\n";
        } else {
            $_SESSION['message'] = "There was a problem with removing Facility with ID ".$facilityId_del.". Try again later.\n";
        }
    } else {
        $_SESSION['message'] = "This Site has only one Facility. You cannot remove it.\n";
        // do not delete. Site needs at least one facility
    }
}
function getShelterForSite($siteId) {
    $sql = "SELECT * FROM sitetoservice, shelter WHERE sitetoservice.SiteId='".
        $siteId."' AND sitetoservice.FacilityId = shelter.FacilityId";
    $result = executeSql($sql);
    return $result;
}
function resetShelterCount($maleCap, $femaleCap, $mixedCap,$FacilityId) {
    $sql = "UPDATE Shelter SET BunkCountMale=".$maleCap.", BunkCountFemale=".$femaleCap.", BunkCountMixed=".$mixedCap." WHERE FacilityId=".$FacilityId;
    return executeSql($sql);
}

function displayCreateServiceOption(){
    $siteId = $_SESSION['siteId'];
    $svc = array("foodbank"=>"Food Bank", "shelter"=>"Shelter", "foodpantry"=>"Food Pantry", "soupkitchen"=>"Soup Kitchen");
    $count = 0;
    foreach($svc as $service => $label) {
        $result = null;
        if ($service == "foodbank") {
           $result = getFoodBankForSite($siteId);
        } else {
            $sql = "SELECT * FROM clientservice where SiteId=".$siteId." AND FacilityType='".$service."'";
            $result = executeSql($sql);
        }
        if ($result->num_rows < 1) {
            echo'<option value="'.$service.'">'.$label.'</option>';
            $count++;
        }
    }
    echo "</select>";
    if ($count == 0) {
        $_SESSION['option_message'] = "<p>This site has Max number of services. Creating a new Service is not allowed.</p>";
    } else {
        $_SESSION['option_message'] = "";
        echo "&nbsp;&nbsp;&nbsp; <button name='save' type='submit'>Next</button>";
    }
}
if (isset($_POST['reset'])){
    //get shelter for this site
    $result = getShelterForSite($siteId);
    //get capacity for the shelter
    while ($row = $result->fetch_assoc()){
        $FacilityId = $row['FacilityId'];
        $maleCap = $row['BunkCapacityMale'];
        $femaleCap = $row['BunkCapacityFemale'];
        $mixedCap = $row['BunkCapacityMixed'];
    }
        //update shelter with those values
    resetShelterCount($maleCap, $femaleCap, $mixedCap,$FacilityId);
    $_SESSION['message'] = "The Shelter Bunk count is reset to original capacity.";
}

?>
<html>
   <head>
       <?php 
          displayTitle($pageTitle);
          displayCss();
       ?>
      <script type="text/javascript">
      </script>
          
   </head>
   <?php displayBodyHeading(); ?>
       <div>
           <div style="float: right">
           <form action="./login.php">
               <input type="submit" value="Logout" />
           </form>
           </div>
            <div style="float: right">
            <form action="./user_home.php">
                <input type="submit" value="User Home" />
            </form>
            </div>
            
       <h1>Services:</h1>
       <?php
       if (!empty($_SESSION['message'])){
           echo "<p>".$_SESSION['message']."</p>";
       }
       ?>
     </div>
       <?php
       // $services = getClientServicesForSite($siteId);
       // displayServicesTable($services);
       
       echo "<h2>Soup Kitchen:</h2>";
       displaySoupKitchenTable($siteId);
       
       echo "<h2>Shelter:</h2>";
       displayShelterTable($siteId);
       
       echo "<h2>Food Pantry:</h2>";
       displayFoodPantryTable($siteId);
       
       echo "<h2>Food Bank:</h2>";
       $foodbank = getFoodBankForSite($siteId);
       displayFoodbankTable($foodbank);
       ?>
       
       <h2>Add New Services</h2>
       <form action="./create_service.php" method="post">
             <p>
                <label>
                   <strong>Select the type of service</strong>
                </label> 
           <select id="serviceType" name="serviceType">
             <option value="0">--Select Service--</option>
             <?php
             displayCreateServiceOption();
             ?>
             </p>
             <?php
             echo $_SESSION['option_message'];
             ?>
       </form>
       
       <?php
       $services = getClientServicesForSite($siteId, "shelter");
       if ($services->num_rows > 0){
           echo '<h2>Reset Shelter bunk Status</h2>
           <p> Clicking this will reset Shelter capacity.</p>
            <form action="./services.php" method="post">
                <button name="reset" type="submit">Reset Bunk status</button>
            </form>';
       }
       ?>
</body>
</html>
