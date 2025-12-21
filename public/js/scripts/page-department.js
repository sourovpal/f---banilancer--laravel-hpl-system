$(document).ready(function(){
    $("#departmentRegisterForm, #departmentUpdateForm").validate({
        rules: {
            departmentcode: {
                required: true,
                // minlength: 5
            },
            departmentname: {
                required: true,
                // minlength: 5
            },
            floor: {
                required: true,
                // minlength: 5
            },
            build: {
                required: true,
                // minlength: 5
            }
        },
        errorElement: 'div'
    });

    itemsTable = $("#department-list-datatable").DataTable({
        responsive: true,
        'columnDefs': [{
          "orderable": false
        }]
    });
});