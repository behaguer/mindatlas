<?php 
require 'config.php'; // Config File.
require 'includes/loader.php'; // Private Loader

require 'template/header.php';

// Generate the courses from the given names
$courseNames = [
  "After Effects 2020 New Features",
  "The Six Morning Habits of High Performers",
  "Understanding the Impact of Deepfake Videos",
  "Delivering an Authentic Elevator Pitch",
  "Learning Personal Branding",
  "Guy Kawasaki on Turning Life Wisdom into Business Success",
  "Career Advice from Some of the Biggest Names in Business",
  "Excel: Advanced Formulas and Functions",
  "Expert Tips for Answering Common Interview Questions",
  "Strategic Thinking",
  "Excel: Lookup Functions in Depth",
  "Communicating with Confidence",
];

// used to boost the enrolments to a large number (over 100,000)
if (isset($_GET['pumpEnrolments']) && $_GET['pumpEnrolments'] == 'true') {

  // Clear the table first
  $_enrolments->truncateTable();

  // Setup the vars
  $totalCourses = count($courseNames);
  $users = $_users->getAllRows(); 
  $totalUsers = count($users);
  $courses = $_courses->getAllRows(); 
  $status = ['not started','in progress','completed'];

  // Insert 100000 enrolments the courses to the users
  for ($i = 0; $i < 100000; $i++){

    $randomUser = rand(1,$totalUsers);
    $randomCourse = rand(1,12);
    $statusID = rand(0,2);
    $chosenStatus = $status[$statusID];

    $stmt = $db->connect->prepare("INSERT INTO enrolments (userID, courseID, status) VALUES (:userID, :courseID, :status )");
    $stmt->bindParam(':userID', $randomUser);
    $stmt->bindParam(':courseID', $randomCourse);
    $stmt->bindParam(':status', $chosenStatus);

    $stmt->execute();
  
  }
}

if (isset($_GET['populateData']) && $_GET['populateData'] == 'true') {

  // use the factory to create a Faker\Generator instance
  $faker = Faker\Factory::create();

  // Generate 100 records for the user table
  $db = Database::getInstance();
  $stmt = $db->connect->prepare("INSERT INTO users (firstname, surname, email, phone) VALUES (:firstname, :surname, :email, :phone)");
  $stmt->bindParam(':firstname', $firstname);
  $stmt->bindParam(':surname', $surname);
  $stmt->bindParam(':email', $email);
  $stmt->bindParam(':phone', $phone);

  for ($i = 0; $i < 100; $i++) {
    $firstname = $faker->firstname();
    $surname = $faker->lastName();
    $email = $firstname.$surname."@".$faker->freeEmailDomain();
    $phone = $faker->phoneNumber();
    $stmt->execute();
  }

  foreach ($courseNames as $course) {
    
    $description = $faker->paragraph();

    $stmt = $db->connect->prepare("INSERT INTO courses (name, description) VALUES (:name, :description )");
    $stmt->bindParam(':name', $course);
    $stmt->bindParam(':description', $description);

    $stmt->execute();

  }

  // Apply the courses to the users
  $maxCourses = 5;
  $totalCourses = count($courseNames);

  $users = $_users->getAllRows(); 
  $courses = $_courses->getAllRows(); 
  $status = ['not started','in progress','completed'];

  foreach ($users as $user) {

    $numberOfCourses = rand(0,3);
    $usedCourses = array();

    // Apply the course enrolments
    for ($i = 0; $i < $numberOfCourses; $i++){

      $statusID = rand(0,2);
      $chosenStatus = $status[$statusID];
      $courseID = rand(1,12);

      while ( in_array($courseID,$usedCourses)) $courseID = rand(0,11);
  
      $stmt = $db->connect->prepare("INSERT INTO enrolments (userID, courseID, status) VALUES (:userID, :courseID, :status )");
      $stmt->bindParam(':userID', $user['ID']);
      $stmt->bindParam(':courseID', $courseID);
      $stmt->bindParam(':status', $chosenStatus);
  
      $stmt->execute();

      $usedCourses[] = $courseID;

    }

    unset($usedCourses);
    
  }

}

if (isset($_GET['clearData']) && $_GET['clearData'] == 'true') {
  $_users->truncateTable();
  $_courses->truncateTable();
  $_enrolments->truncateTable();
}


?>

<body>
<div class="body-wrapper uk-container">

  <?php require 'template/navbar.php'; ?>
  <?php require 'template/offcanvas.php'; ?>

  <h1>Settings</h1>

  <div class="uk-grid uk-grid-small uk-grid-match uk-child-width-1-3@m">
    <div>
      <div class="uk-card uk-card-default uk-card-body ">
        <h3 class="uk-card-title">Current Records</h3>
        <p>User Table: <?= $_users->getCount(); ?> rows</p>
        <p>Courses Table: <?= $_courses->getCount(); ?> rows</p>
        <p>Enrolments Table: <?= $_enrolments->getCount(); ?> rows</p>
      </div>
    </div>
    <div>
      <div class="uk-card uk-card-default uk-card-body ">
        <h3 class="uk-card-title">Add Records</h3>
        <p>To add records to the database click the Populate Data button below.</p>
        <form><button class="uk-button uk-button-primary" type="submit" name="populateData" value="true">Populate Data</button></form>
      </div>
    </div>
    <div>
      <div class="uk-card uk-card-default uk-card-body ">
        <h3 class="uk-card-title">Clear Records</h3>
        <p>To empty the records tables click the Clear Data button below.</p>
      <form><button class="uk-button uk-button-secondary" type="submit" name="clearData" value="true">Clear Data</button></form>
      </div>
    </div>
  </div>



  <hr />
  <h2>Stress Test</h2>
  <p>To fill the enrolment table to over 100,000 records. </p>
  <p>Please note: this is for demonstration purposes only! Duplicate user / course enrolments will occur.</p>
  <p><strong>This process is destructive and will clear the enrolments, then regenerate the data. It may take a couple of minutes to complete.</strong></p>
  <form><button class="uk-button uk-button-secondary" type="submit" name="pumpEnrolments" value="true" style="margin-right:10px;">Pump Enrolment Data</button><div style="display:none;" uk-spinner="ratio: 1"></div></form>

</div>

<script>
    jQuery('button[name="pumpEnrolments"]').on('click', function(e) {
      jQuery(this).attr("disabled", true);
      let spinner = jQuery(this).next();
      spinner.show();
      window.location = '/settings.php?pumpEnrolments=true';
    });
</script>


</body>
