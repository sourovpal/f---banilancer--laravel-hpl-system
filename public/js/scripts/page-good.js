var item_data;

$(document).ready(function(){
    var item = 0, gr_from = '', gr_to = '';

    $('#item').change(function(){
        item = $(this).val();
        getReports(item, gr_from, gr_to);
    });

    $('#gr_from').change(function(){
        gr_from = $(this).val();
        getReports(item, gr_from, gr_to);
    });

    $('#gr_to').change(function(){
        gr_to = $(this).val();
        getReports(item, gr_from, gr_to);
    });

    $('#request_qty').change(function(){
        $('#total_price_display').html(Number($(this).val()) * Number($('#unit_price').val()));
        $('#total_price').val(Number($(this).val()) * Number($('#unit_price').val()));
    });

    $('#unit_price').change(function(){
        $('#total_price_display').html(Number($(this).val()) * Number($('#request_qty').val()));
        $('#total_price').val(Number($(this).val()) * Number($('#request_qty').val()));
    });
	
	$('#goodreceiveRegisterForm').on('submit', function(e) {
    e.preventDefault();
    var formData = $(this).serializeArray();
    $.ajax({
        url: '/goodreceive_register',
        method: 'POST',
        data: formData,
        success: function(response) {
            console.log(response);
        },
        error: function(error) {
            console.log(error);
        }
    });
});
	
});



function getReports(item, gr_from, gr_to) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getGoodReceiveReports',
        method: 'post',
        dataType: 'json',
        data: {
            item: item,
            gr_from: gr_from,
            gr_to: gr_to
        },
        success: function(data) {
            html = '';
            for (var i = 0; i < data.length; i++) {
                html += `
                    <tr>
                        <td>`+ data[i]['gr_no'] + `</td>
                        <td>`+ data[i]['user'] + `</td>
                        <td>`+ data[i]['gr_date'] + `</td>
                        <td>`+ data[i]['supplier'] + `</td>
                        <td>`+ data[i]['item_code'] + `</td>
                        <td>`+ data[i]['specification'] + `</td>
                        <td>`+ data[i]['request_qty'] + `</td>
                        <td>`+ data[i]['unit'] + `</td>
                        <td>`+ data[i]['packing'] + `</td>
                        <td>`+ data[i]['unit_price'] + `</td>
                        <td>`+ data[i]['total_price'] + `</td>
                        <td><a href="/special-good-receive-handling/` + data[i]['id'] + `"><i class="material-icons">edit</i></a></td>
                    </tr>
                `;
            }
            $('#report_table tbody').html(html);
        },
        error: function(error) {

        }
    });
}