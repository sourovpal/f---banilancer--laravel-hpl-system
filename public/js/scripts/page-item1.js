alert()
$(document).ready(function(){
alert()
    $("#itemRegisterForm, #itemUpdateForm").validate({
        rules: {
            itemcode: {
                required: true,
                // minlength: 5
            },
            itemunit: {
                required: true,
                // minlength: 5
            },
            itemprice: {
                required: true,
                min: 0
            },
            itemmin: {
                required: true,
                digits: true,
                min: 0
            },
            itemname: {
                required: true,
                // minlength: 5
            },
            itempack: {
                required: true,
                // minlength: 5
            }
             itemlocation: {
                 required: true
             },
            itemspecification: {
                 required: true
             }
        },
        errorElement: 'div'
    });
});