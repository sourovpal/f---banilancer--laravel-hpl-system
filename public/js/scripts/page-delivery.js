var item_data;

$(document).ready(function(){
    var costcentre = 0, item = 0, sign_from = '', sign_to = '', dn_from = '', dn_to = '';
    
    $('.nested_table_wrap').hide();
    $('.nested_table_hide').hide();

    $('.nested_table_show').click(function(){
        $(this).parent().parent().next().fadeIn();
        $(this).parent().find('.nested_table_show').hide();
        $(this).parent().find('.nested_table_hide').show();
    });

    $('.nested_table_hide').click(function(){
        $(this).parent().parent().next().fadeOut();
        $(this).parent().find('.nested_table_show').show();
        $(this).parent().find('.nested_table_hide').hide();
    });

    $('#costcentre').change(function(){
        costcentre = $(this).val();
        getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to);
    });

    $('#item').change(function(){
        item = $(this).val();
        getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to);
    });

    $('#dn_from').change(function(){
        dn_from = $(this).val();
        getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to);
    });

    $('#dn_to').change(function(){
        dn_to = $(this).val();
        getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to);
    });

    $('#sign_from').change(function(){
        sign_from = $(this).val();
        getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to);
    });

    $('#sign_to').change(function(){
        sign_to = $(this).val();
        getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to);
    });

    $('#request_qty').change(function(){
        $('#total_price').val($(this).val() * $('#unit_price').val());
        $('#total_price_display').html($(this).val() * $('#unit_price').val());
    });

    $('#unit_price').change(function(){
        $('#total_price').val($(this).val() * $('#request_qty').val());
        $('#total_price_display').html($(this).val() * $('#request_qty').val());
    });
});

function getReports(costcentre, item, dn_from, dn_to, sign_from, sign_to) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getDeliveryNoteReports',
        method: 'post',
        dataType: 'json',
        data: {
            costcentre: costcentre,
            item: item,
            dn_from: dn_from,
            dn_to: dn_to,
            sign_from: sign_from,
            sign_to: sign_to
        },
        success: function(data) {
            html = '';
            for (var i = 0; i < data.length; i++) {
                html += `
                    <tr>
                        <td>` + data[i]['note_no'] + `</td>
                        <td>` + data[i]['user'] + `</td>
                        <td>` + data[i]['costcentre'] + `</td>
                        <td>` + data[i]['dn_date'] + `</td>
                        <td>` + data[i]['so_no'] + `</td>
                        <td>` + data[i]['sign_date'] + `</td>
                        <td>` + data[i]['item_code'] + `</td>
                        <td>` + data[i]['specification'] + `</td>
                        <td>` + data[i]['request_qty'] + `</td>
                        <td>` + data[i]['unit'] + `</td>
                        <td>` + data[i]['packing'] + `</td>
                        <td>` + data[i]['unit_price'] + `</td>
                        <td>` + data[i]['total_price'] + `</td>
                        <td><a href="/special-delivery-note-handling/` + data[i]['id'] + `"><i class="material-icons">edit</i></a></td>
                    </tr>
                `;
            }
            $('#note_report_table tbody').html('html');
        },
        error: function(error) {
            alert('ajax failed')
        }
    });
}