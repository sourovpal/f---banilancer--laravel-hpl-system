var item_data;

$(document).ready(function(){
    var item = 0, po_from = '', po_to = '';

    $('#item').change(function(){
        item = $(this).val();
        getReports(item, po_from, po_to);
    });

    $('#po_from').change(function(){
        po_from = $(this).val();
        getReports(item, po_from, po_to);
    });

    $('#po_to').change(function(){
        po_to = $(this).val();
        getReports(item, po_from, po_to);
    });
});

function getReports(item, po_from, po_to) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getPurchaseOrderReports',
        method: 'post',
        dataType: 'json',
        data: {
            item: item,
            po_from: po_from,
            po_to: po_to
        },
        success: function(data) {
            html = '';
            for (var i = 0; i < data.length; i++) {
                html += `
                    <tr>
                        <td>`+ data[i]['po_no'] + `</td>
                        <td>`+ data[i]['user'] + `</td>
                        <td>`+ data[i]['po_date'] + `</td>
                        <td>`+ data[i]['supplier'] + `</td>
                        <td>`+ data[i]['item_code'] + `</td>
                        <td>`+ data[i]['specification'] + `</td>
                        <td>`+ data[i]['request_qty'] + `</td>
                        <td>`+ data[i]['unit'] + `</td>
                        <td>`+ data[i]['packing'] + `</td>
                        <td>`+ data[i]['unit_price'] + `</td>
                        <td>`+ data[i]['total_price'] + `</td>
                    </tr>
                `;
            }
            $('#report_table tbody').html(html);
        },
        error: function(error) {

        }
    });
}