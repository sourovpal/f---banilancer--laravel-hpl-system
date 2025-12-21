$(document).ready(function () {
  $('.users-list-datatable').find('table').unwrap();
  // variable declaration
  var usersTable;
  var usersDataArray = [];
  // datatable initialization
  if ($(".users-list-datatable").length > 0) {
    $('#users-list-datatable').find('table').unwrap();
    usersTable = $(".users-list-datatable").DataTable({
      // responsive: true,
      "iDisplayLength": localStorage.getItem("setPagination")  > 0 ? localStorage.getItem("setPagination") : 10, // Set the default pagination size to 25
      'columnDefs': [{
        "orderable": false
      }]
    });
    $('.nested_table_wrap').hide();
  }
  // on click selected users data from table(page named page-users-list)
  // to store into local storage to get rendered on second page named page-users-view
  $(document).on("click", ".users-list-datatable tr", function () {
    $(this).find("td").each(function () {
      usersDataArray.push($(this).text().trim())
    })

    localStorage.setItem("usersId", usersDataArray[1]);
    localStorage.setItem("usersUsername", usersDataArray[2]);
    localStorage.setItem("usersName", usersDataArray[3]);
    localStorage.setItem("usersVerified", usersDataArray[5]);
    localStorage.setItem("usersRole", usersDataArray[6]);
    localStorage.setItem("usersStatus", usersDataArray[7]);
  })
  // render stored local storage data on page named page-users-view
  if (localStorage.usersId !== undefined) {
    $(".users-view-id").html(localStorage.getItem("usersId"));
    $(".users-view-username").html(localStorage.getItem("usersUsername"));
    $(".users-view-name").html(localStorage.getItem("usersName"));
    $(".users-view-verified").html(localStorage.getItem("usersVerified"));
    $(".users-view-role").html(localStorage.getItem("usersRole"));
    $(".users-view-status").html(localStorage.getItem("usersStatus"));
    // update badge color on status change
    if ($(".users-view-status").text() === "Banned") {
      $(".users-view-status").toggleClass("badge-light-success badge-light-danger")
    }
    // update badge color on status change
    if ($(".users-view-status").text() === "Close") {
      $(".users-view-status").toggleClass("badge-light-success badge-light-warning")
    }
  }
  // page users list verified filter
  $("#users-list-verified").on("change", function () {
    var usersVerifiedSelect = $("#users-list-verified").val();
    usersTable.search(usersVerifiedSelect).draw();
  });
  // page users list role filter
  $("#users-list-role").on("change", function () {
    var usersRoleSelect = $("#users-list-role").val();
    // console.log(usersRoleSelect);
    usersTable.search(usersRoleSelect).draw();
  });
  // page users list status filter
  $("#users-list-status").on("change", function () {
    var usersStatusSelect = $("#users-list-status").val();
    // console.log(usersStatusSelect);
    usersTable.search(usersStatusSelect).draw();
  });
  // users language select
  if ($("#users-language-select2").length > 0) {
    $("#users-language-select2").select2({
      dropdownAutoWidth: true,
      width: '100%'
    });
  }
  // users music select
  if ($("#users-music-select2").length > 0) {
    $("#users-music-select2").select2({
      dropdownAutoWidth: true,
      width: '100%'
    });
  }
  // users movies select
  if ($("#users-movies-select2").length > 0) {
    $("#users-movies-select2").select2({
      dropdownAutoWidth: true,
      width: '100%'
    });
  }

  // Input, Select, Textarea validations except submit button validation initialization
  if ($(".users-edit").length > 0) {

  }

  $('#role_select').change(function () {
    $('.userForm').hide();
    if ($(this).val() == 'master') $("#masterUserRegisterForm").show();
    else if ($(this).val() == 'internal') $("#internalUserRegisterForm").show();
    else $("#externalUserRegisterForm").show();
  });

  $('.indigo').click(function (e) {
    e.preventDefault();
    var form = $('#' + $(this).attr('form_name'));
    if (form.find('#password').val() == form.find('#confirm_password').val()) {
      form.submit();
    }
    else {
      alert('Please Confirm Password Correctly Again!');
    }
  });

  $("#masterUserRegisterForm").validate({
    rules: {
      username: {
        required: true,
        // minlength: 5
      },
      useremail: {
        required: true,
        email: true
      },
      usercode: {
        required: true,
        // minlength: 5
      },
      userid_master: {
        required: true,
        // minlength: 5
      },
      systemname: {
        required: true,
        // minlength: 5
      },
      systemcode: {
        required: true,
        // minlength: 5
      },
      // password: {
      //   required: true,
      //   minlength: 8
      // },
      // confirm_password: {
      //   required: true,
      //   minlength: 8,
      // }
    },
    errorElement: 'div'
  });

  $("#internalUserRegisterForm").validate({
    rules: {
      username: {
        required: true,
        // minlength: 5
      },
      usercode: {
        required: true,
        // minlength: 5
      },
      useremail: {
        required: true,
        email: true
      },
      // password: {
      //   required: true,
      //   // minlength: 8
      // },
      // confirm_password: {
      //   required: true,
      //   // minlength: 8
      // }
    },
    errorElement: 'div'
  });

  $("#externalUserRegisterForm").validate({
    rules: {
      username: {
        required: true,
        // minlength: 5
      },
      usercode: {
        required: true,
        // minlength: 5
      },
      useremail: {
        required: true,
        email: true
      },
      telephone: {
        required: true
        // minlength: 11,
        // maxlength: 11
      },
      // password: {
      //   required: true,
      //   minlength: 8
      // },
      // confirm_password: {
      //   required: true,
      //   minlength: 8
      // }
    },
    errorElement: 'div'
  });

  $('.userForm').hide();
  if ($('#role_select').val() == 'master') $("#masterUserRegisterForm").show();
  else if ($('#role_select').val() == 'internal') $("#internalUserRegisterForm").show();
  else $("#externalUserRegisterForm").show();

  $('.delete_user').click(function () {
    $('#confirm_modal').find('#user_id_cancel').val($(this).attr('user_id'));
  });

  $('#yes_btn').click(function () {
    $('#cancel_btn').click();
    window.location.href = "page-users-delete/" + $(this).parent().parent().find('#user_id_cancel').val();
  });
});