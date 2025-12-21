$(document).ready(function(){
    $("#internalCompanyInformationForm").validate({
        rules: {
            name: {
                required: true,
                // minlength: 5
            },
            add1: {
                required: true,
                // minlength: 5
            },
            add2: {
                required: true,
                // minlength: 5
            },
            add2: {
                required: true,
                // minlength: 5
            },
            tel: {
                required: true,
                // minlength: 5
            },
            fax: {
                required: true,
                // minlength: 5
            }
        },
        errorElement: 'div'
    });

    $("#externalCompanyInformationForm").validate({
        rules: {
            name: {
                required: true,
                // minlength: 5
            },
            add1: {
                required: true,
                // minlength: 5
            },
            add2: {
                required: true,
                // minlength: 5
            },
            add2: {
                required: true,
                // minlength: 5
            },
            tel: {
                required: true,
                // minlength: 5
            },
            fax: {
                required: true,
                // minlength: 5
            }
        },
        errorElement: 'div'
    });
});