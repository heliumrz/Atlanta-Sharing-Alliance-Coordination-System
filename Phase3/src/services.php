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
      <title><?php displayText($pageTitle);?></title>
      <script type="text/javascript">
      </script>
          
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
            </div>
       <h2>Client Services:</h2>
       <?php
       if (!empty($_SESSION['message'])){
           echo "<p>".$_SESSION['message']."</p>";
       }
       ?>
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
       <h2>Add New Services</h2>
       <form action="./create_service.php" method="post">
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
            <button name="save" type="submit">Next</button>
       </form>
       <h2>Reset Shelter bunk Status</h2>
       <p> Clicking this will reset Shelter capacity.</p>
        <form action="./services.php" method="post">
            <button name="reset" type="submit">Reset Bunk status</button>
        </form>
</body>
</html>
