<?php 
require $_SERVER['DOCUMENT_ROOT'].'/config.php'; // Config File.
require $_SERVER['DOCUMENT_ROOT'].'/includes/loader.php'; // Private Loader

require 'template/header.php';


// Check for search
if (isset($_GET['s'])) {
  $users = $_users->searchUsers();
  $searched = "Displaying results for the search: <b>".htmlentities($_GET['s'])."</b>";
} else {
  $users = $_users->getAllRows();
}


?>

<body>
<div class="body-wrapper uk-container">

  <?php require 'template/navbar.php'; ?>
  <?php require 'template/offcanvas.php'; ?>
  <?php require 'template/search.php'; ?>

  <?php if (isset($searched)) echo $searched ?>
  <div class="uk-overflow-auto" style="box-shadow: 4px 1px 20px 1px #d5d5d5;padding: 20px;">
    <table class="uk-table uk-margin-top">
      <caption>Users</caption>
      <thead>
          <tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Surname</th>
              <th>Email</th>
              <th>Phone</th>
              <th>Actions</th>
          </tr>
      </thead>
      <tbody>

        <?php foreach ($users as $user) : ?>

          <tr>
              <td><?= $user['ID'] ?></td>
              <td><?= $user['firstname'] ?></td>
              <td><?= $user['surname'] ?></td>
              <td><?= $user['email'] ?></td>
              <td><?= $user['phone'] ?></td>
              <td><span class="viewDetail" uk-icon="icon: info" data-id="<?= $user['ID'] ?>"></span> <div style="display:none;" uk-spinner="ratio: .5"></div> </td>
          </tr>
        <?php endforeach; ?>

      </tbody>
    </table>
  </div>
  

<!-- This is the modal -->
<div id="modal-detail" uk-modal>
    <div class="uk-modal-dialog uk-modal-body">
        <h2 class="username" class="uk-modal-title"></h2>
        <p class="detail"></p>
        <div class="enrolments">

        </div>
        <p class="uk-text-right">
            <button class="uk-button uk-button-default uk-modal-close" type="button">Cancel</button>
        </p>
    </div>
</div>
</div>

<script>

  <?php if (isset($_GET['id']) && $_GET['id'] > 0) : ?>
    jQuery(document).ready(function() {
      jQuery('.viewDetail[data-id="<?= $_GET['id'] ?>"]').trigger('click');
    })
  <?php endif; ?>

  var modal = UIkit.modal('#modal-detail');

  jQuery('.viewDetail').on('click', function(e) {
    let id = jQuery(this).data('id');
    let spinner = jQuery(this).next();
    spinner.show();
    jQuery.post('/api/get_user_detail.php', {"id":id}, function(res) {

      detail = JSON.parse(res);

      jQuery("#modal-detail .username").html(detail.user.firstname+" "+detail.user.surname);
      jQuery("#modal-detail .detail").html(detail.user.email+" - "+detail.user.phone);

      jQuery("#modal-detail .enrolments table").remove();
      jQuery("#modal-detail .enrolments").append("<table id='enrolmentsTable' class='uk-table uk-table-divider uk-table-striped'><thead><th>Course</th><th style='width: 130px;'>Status</th></thead><tbody></tbody></table");
      
      detail.enrolments.forEach((course) => {
       jQuery("#modal-detail #enrolmentsTable tbody").append("<tr><td><a href='/courses.php?id="+course.courseID+"'>"+course.name+"</a></td><td> "+capitalizeFirstLetter(course.status)+"</td>");
      })

      modal.toggle();
      spinner.hide();

      // Set the history state this will help with the back button presses
      const url = new URL(window.location);
      url.searchParams.set('id', id);
      window.history.pushState({}, '', url);

    });

  })

</script>

</body>
