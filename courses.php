<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php'; // Config File.
require $_SERVER['DOCUMENT_ROOT'].'/includes/loader.php'; // Private Loader

require 'template/header.php';


// Check for search
if (isset($_GET['s'])) {
  $courses = $_courses->searchCourses();
  $searched = "Displaying results for the search: <b>".htmlentities($_GET['s'])."<b>";
} else {
  $courses = $_courses->getAllRows();
}


?>

<body>
<div class="body-wrapper uk-container">

  <?php require 'template/navbar.php'; ?>
  <?php require 'template/offcanvas.php'; ?>
  <?php require 'template/search.php'; ?>

  <?php if (isset($searched)) echo $searched ?>

  <div class="uk-overflow-auto" style="box-shadow: 4px 1px 20px 1px #d5d5d5;padding: 20px;">
    <table id="courseTable" class="uk-table uk-margin-top">
      <caption>Courses</caption>
      <thead>
          <tr>
              <th>ID</th>
              <th>Name</th>
              <th>View</th>
              <th style="width:100px">Report</th>
              <th style="width:80px"></th>
          </tr>
      </thead>
      <tbody>

        <?php foreach ($courses as $course) : ?>

          <tr>
              <td><?= $course['ID'] ?></td>
              <td class="name"><?= $course['name'] ?></td>
              <td><span class="viewDetail" uk-icon="icon: info" data-id="<?= $course['ID'] ?>" uk-tooltip="View Details"></span> </td>
              <td>
                <span class="viewEnrolments success" uk-icon="icon: album" data-id="<?= $course['ID'] ?>" data-status="completed" uk-tooltip="View Completed"></span>
                <span class="viewEnrolments warning" uk-icon="icon: album" data-id="<?= $course['ID'] ?>" data-status="in progress" uk-tooltip="View In Progress"></span>
                <span class="viewEnrolments" uk-icon="icon: album" data-id="<?= $course['ID'] ?>" data-status="not started" uk-tooltip="View Not Started"></span> 
              </td>
              <td><div class="spinner" style="display:none;" uk-spinner="ratio: .5"></div> </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table>

  </div>
  
<!-- This is the modal -->
<div id="modal-detail" uk-modal>
    <div class="uk-modal-dialog uk-modal-body" style="overflow:hidden">
        <h2 class="name" class="uk-modal-title"></h2>
        <p class="detail"></p>
        <div id="course_chart" style="width: auto; height: 200px;"></div>
        <div class="enrolments"></div>
        <p class="uk-text-right">
        <?php if (isset($_GET['id']) && $_GET['id'] > 0) : ?>
          <button onClick="history.back()" class="uk-button uk-button-primary" type="button">Back to User</button>
        <?php endif; ?>
          <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        </p>
    </div>
</div>
</div>

<script type="text/javascript">
  google.charts.load('current', {'packages':['corechart']});

  var modal = UIkit.modal('#modal-detail');

  <?php if (isset($_GET['id']) && $_GET['id'] > 0) : ?>
    jQuery(document).ready(function() {
      jQuery('.viewDetail[data-id="<?= $_GET['id'] ?>"]').trigger('click');
    })
 <?php endif; ?>


  jQuery('.viewDetail').on('click', function(e) {
    let id = jQuery(this).data('id');
    let spinner = jQuery(this).parent().parent().find('.spinner');
    
    spinner.show();
    jQuery.post('/api/get_course_detail.php', {"id":id}, function(res) {
      detail = JSON.parse(res);
      jQuery("#modal-detail .name").html(detail.name);
      jQuery("#modal-detail .detail").html(detail.description);
      jQuery("#modal-detail .detail").show();
      jQuery("#modal-detail .enrolments table").remove();
      jQuery("#modal-detail #course_chart").show();

      function drawChart() {

        var data = new google.visualization.DataTable();
          data.addColumn('string', 'Course');
          data.addColumn('number', 'Status');
          data.addRows([
            ['Not Started', parseInt(detail["not started"])],
            ['In Progress', parseInt(detail["in progress"])],
            ['Completed', parseInt(detail["completed"])]
          ]);

        var options = {
          title: 'Course Enrolment Status',
          pieSliceText: 'value',
          pieSliceTextStyle: {
            color: 'black',
          },
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('course_chart'));
        chart.draw(data, options);
      }

      google.charts.setOnLoadCallback(drawChart);

      modal.toggle();
      spinner.hide();
    });

  })

  jQuery('.viewEnrolments').on('click', function(e) {

    let id = jQuery(this).data('id');
    let status = jQuery(this).data('status');; 
    let spinner = jQuery(this).parent().parent().find('.spinner');
    let name = jQuery(this).parent().parent().find('.name').text();
    
    spinner.show();
    jQuery.post('/api/get_enrolments.php', {"id":id,"status":status}, function(res) {
      detail = JSON.parse(res);
      jQuery("#modal-detail .name").html(name);
      jQuery("#modal-detail .detail").hide();
      jQuery("#modal-detail #course_chart").hide();

      jQuery("#modal-detail .enrolments table").remove();
      jQuery("#modal-detail .enrolments").append("<table id='enrolmentsTable' class='uk-table uk-table-divider uk-table-striped'><thead><th>Firsname</th><th>Surname</th><th style='width: 130px;'>Status</th></thead><tbody></tbody></table");
      
      detail.forEach((enrolment) => {
       jQuery("#modal-detail #enrolmentsTable tbody").append("<tr><td><a href='/index.php?id="+enrolment.userID+"'>"+enrolment.firstname+"</a></td><td>"+enrolment.surname+"</td><td> "+capitalizeFirstLetter(enrolment.status)+"</td>");
      })

      modal.toggle();
      spinner.hide();
    });

  })

</script>

</body>
