$(document).ready(function(){
    $("#supplierRegisterForm, #supplierUpdateForm").validate({
        rules: {
            suppliercode: {
                required: true,
                // minlength: 5
            },
            englishname: {
                required: true,
                // minlength: 5
            },
            englishaddress: {
                required: true,
                // minlength: 5
            },
            telephone1: {
                required: true,
                // minlength: 5
            },
            suppliercontact: {
                required: true,
                // minlength: 5
            },
            suppliermobile: {
                required: true,
                // minlength: 5
            },
            supplieremail: {
                required: true,
                // minlength: 5
            },
            chinaname: {
                required: true,
                // minlength: 5
            },
            chinaaddress: {
                required: true,
                // minlength: 5
            },
            telephone2: {
                required: true,
                // minlength: 5
            },
            supplierfax: {
                required: true,
                // minlength: 5
            },
            supplierremarks: {
                required: true,
                // minlength: 5
            }
        },
        errorElement: 'div'
    });
});