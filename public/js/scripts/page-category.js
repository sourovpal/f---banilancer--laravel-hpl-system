$(document).ready(function(){
    $("#categoryRegisterForm, #categoryUpdateForm").validate({
        rules: {
            categorycode: {
                required: true,
                // minlength: 5
            },
            categoryname: {
                required: true,
                // minlength: 5
            }
        },
        errorElement: 'div'
    });
});