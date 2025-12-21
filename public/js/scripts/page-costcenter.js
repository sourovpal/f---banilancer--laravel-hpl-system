$(document).ready(function(){
    $("#costcenterRegisterForm, #costcenterUpdateForm").validate({
        rules: {
            costcentername: {
                required: true,
                // minlength: 5
            },
            costcentercode: {
                required: true,
                // minlength: 5
            },
            department: {
                required: true,
                // minlength: 5
            }
        },
        errorElement: 'div'
    });

    itemsTable = $("#costcenter-list-datatable").DataTable({
        responsive: true,
        'columnDefs': [{
          "orderable": false
        }]
    });
});