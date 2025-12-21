var item_data;
var qId;

$(document).ready(function () {
    var category = 0, item_id = 0, specification = "", item_name = "";
    var items = "";

    qId = $('#quotationRegisterForm').data('q_id');

    getQuotationItems(qId);

    $('.nested_table_wrap').hide();
    $('.nested_table_hide').hide();

    $('.nested_table_show').click(function () {
        $(this).parent().parent().next().fadeIn();
        $(this).parent().find('.nested_table_show').hide();
        $(this).parent().find('.nested_table_hide').show();
    });

    $('.nested_table_hide').click(function () {
        $(this).parent().parent().next().fadeOut();
        $(this).parent().find('.nested_table_show').show();
        $(this).parent().find('.nested_table_hide').hide();
    });

    $('#qty').change(function () {
        $('.totalprice').html(Number($(this).val()) * Number($('#unitprice').val()));
    });

    $('#unitprice').change(function () {
        $('.totalprice').html(Number($(this).val()) * Number($('#qty').val()));
    });

    $('#category').change(function () {
        category = $(this).val();
        getItems(category, item_id, item_name, specification);
    });

    $('#search_id').change(function () {
        item_id = $(this).val();
        getItems(category, item_id, item_name, specification);
    });

    $('#search_name').change(function () {
        item_name = $(this).val();
        getItems(category, item_id, item_name, specification);
    });

    $('#search_description').change(function () {
        specification = $(this).val();
        getItems(category, item_id, item_name, specification);
    });

    $('#add_item_btn').click(function () {
        var item_objs = document.getElementsByClassName('row_check');
        for (var i = 0; i < item_objs.length; i++) {
            if (item_objs[i].checked) {
                items += item_objs[i].getAttribute('item_id') + ', ';
                for (var j = 0; j < item_data.length; j++) {
                    if (item_data[j]['id'] == item_objs[i].getAttribute('item_id')) {
                        item_data[j]['qty']++;
                    }
                }
            }
        }

        showItems(item_data);

        category = 0, item_id = 0, specification = "", item_name = "", items = "";
    });

    $('#item_modal_display').click(function () {
        getItems(category, item_id, item_name, specification);
    });

    // getAllItems();

    $('#save_all').click(function () {
        saveItems();
    });

    $('#update_po').click(function () {
        savePOItems();
    });

    $('#update_gr').click(function (e) {
        e.preventDefault();
        saveGRItems();
    });

    $(document).on("keyup", ".table_input", function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).attr('item_qty')) {
                item_data[i]['qty'] = $(this).val();
                var data = {
                    itemId: item_data[i]['id'],
                    qn_id: item_data[i]['qn_id'],
                    remarks: item_data[i]['remarks'],
                    qty: item_data[i]['qty'],
                    price: item_data[i]['price'],
                };
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                // $.ajax({
                //     /* the route pointing to the post function */
                //     url: '/quotationItemUpdate',
                //     type: 'POST',
                //     /* send the csrf-token and the input to the controller */
                //     data: {_token: CSRF_TOKEN, data: data},
                //     dataType: 'JSON',
                //     /* remind that 'data' is the response of the AjaxController */
                //     success: function (data) {
                //         console.log('done')
                //     }
                // });
                break;
            }
            if (item_data[i]['id'] == $(this).attr('quo_item_remark')) {
                item_data[i]['remarks'] = $(this).val();
                var data = {
                    itemId: item_data[i]['id'],
                    qn_id: item_data[i]['qn_id'],
                    remarks: item_data[i]['remarks'],
                    qty: item_data[i]['qty'],
                    price: item_data[i]['price'],
                };
                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                // $.ajax({
                //     /* the route pointing to the post function */
                //     url: '/quotationItemUpdate',
                //     type: 'POST',
                //     /* send the csrf-token and the input to the controller */
                //     data: {_token: CSRF_TOKEN, data: data},
                //     dataType: 'JSON',
                //     /* remind that 'data' is the response of the AjaxController */
                //     success: function (data) {
                //         console.log('done')
                //     }
                // });
                break;
            }
        }
    });

    $(document).on("keyup", '.table_input_cost', function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).attr('item_id')) {
                item_data[i]['price'] = $(this).val();
                var data = {
                    itemId: item_data[i]['id'],
                    qn_id: item_data[i]['qn_id'],
                    remarks: item_data[i]['remarks'],
                    qty: item_data[i]['qty'],
                    price: item_data[i]['price'],
                };

                var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                // $.ajax({
                //     /* the route pointing to the post function */
                //     url: '/quotationItemUpdate',
                //     type: 'POST',
                //     /* send the csrf-token and the input to the controller */
                //     data: {_token: CSRF_TOKEN, data: data},
                //     dataType: 'JSON',
                //     /* remind that 'data' is the response of the AjaxController */
                //     success: function (data) {
                //         console.log('done')
                //     }
                // });
                break;
            }
        }
    });
});

function saveItems() {

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/saveItemsQuotation',
        method: 'post',
        dataType: 'json',
        data: {
            qn_id: qId,
            items: get_selected_items(),
        },
        success: function (data) {
            if (data['result'] == 'success') $('#quotationRegisterForm').submit();
            else alert('Sales Order Items Create Failed');
        },
        error: function (error) {

        }
    });
}

function getAllItems() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getItems',
        method: 'get',
        dataType: 'json',
        data: {
            category: 0,
            id: 0,
            name: '',
            specification: ''
        },
        success: function (data) {
            item_data = data;
            for (var i = 0; i < data.length; i++) {
                item_data[i]['qty'] = 0;
                $('.item_row').each(function () {
                    if ($(this).find('.item_id').html() == data[i]['id']) item_data[i]['qty'] = $(this).find('.table_input').val();
                });
            }
        },
        error: function (error) {

        }
    });
}

function showItems(item_data) {
    var html = "";
    for (var i = 0; i < item_data.length; i++) {
        if (item_data[i]['qty']) {
            if (document.getElementsByClassName('card-title')[1].innerHTML == 'Good Receive Remarks:') html += `
                <tr class="item_row">
                    <td>` + item_data[i]['code'] + `</td>
                    <td>` + item_data[i]['name'] + `</td>
                    <td>` + item_data[i]['specification'] + `</td>
                    <td>` + item_data[i]['unit'] + `</td>
                    <td>` + item_data[i]['pack'] + `</td>
                    <td><input type="text" item_id="` + item_data[i]['id'] + `" class="table_input_cost" value="` + item_data[i]['price'] + `"></td>
                    <td><input type="text" item_id="` + item_data[i]['id'] + `" class="table_input" value="` + item_data[i]['qty'] + `"></td>
                    <td><a href="#confirm_modal" class="item_cancel modal-trigger" item_id="` + item_data[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
            else html += `
                <tr class="item_row">
                    <td>` + item_data[i]['code'] + `</td>
                    <td>` + item_data[i]['name'] + `</td>
                    <td>` + item_data[i]['specification'] + `</td>
                    <td>` + item_data[i]['unit'] + `</td>
                    <td>` + item_data[i]['pack'] + `</td>
                    <td>` + item_data[i]['price'] + `</td>
                    <td><input type="text" item_id="` + item_data[i]['id'] + `" class="table_input" value="` + item_data[i]['qty'] + `"></td>
                    <td><a href="#confirm_modal" class="item_cancel modal-trigger" item_id="` + item_data[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
        }
    }
    $('#item_table tbody').html(html);

    itemsTable = $("#item_table").DataTable({
        responsive: true,
        'columnDefs': [{
            "orderable": false
        }]
    });

    $('.item_cancel').click(function () {
        $('#confirm_modal').find('#item_id_cancel').val($(this).attr('item_id'));
    });

    $('#yes_btn').click(function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).parent().parent().find('#item_id_cancel').val()) {
                item_data[i]['qty'] = 0;
                break;
            }
        }
        $('#cancel_btn').click();
        showItems(item_data);
    });
}

function getItems(category, id, name, specification) {
    $(".select-all").prop('checked', false);

    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getItems',
        method: 'get',
        dataType: 'json',
        data: {
            category: category,
            id: id,
            name: name,
            specification: specification
        },
        success: function (data) {
            var html = "";
            for (var i = 0; i < data.length; i++) {
                html += `
                    <tr class="data_row">
                        <td>
                            <label>
                                <input type="checkbox" class="row_check" item_id="` + data[i]['id'] + `" />
                                <span></span>
                            </label>
                        </td>
                        <td class="text-center"><img class="item_image" src="../upload/item/` + data[i]['image'] + `" alt=""></td>
                        <td>` + data[i]['code'] + `</td>
                        <td>` + data[i]['name'] + `</td>
                        <td>` + data[i]['specification'] + `</td>
                        <td>` + data[i]['pack'] + `</td>
                    </tr>
                `;
            }
            $('#modal_item tbody').html(html);

            itemsTable = $("#modal_item").DataTable({
                responsive: true,
                'columnDefs': [{
                    "orderable": false
                }]
            });

            $('#modal_item .data_row').click(function () {
                var obj = $(this).find('.row_check');
                if (obj.prop("checked")) obj.prop("checked", false);
                else obj.prop("checked", true);
            });
        },
        error: function (error) {

        }
    });
}

function savePOItems() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/savePOItems',
        method: 'post',
        dataType: 'json',
        data: {
            items: get_selected_items(),
            po_id: $('#po_id').val(),
            supplier: $('#supplier').val()
        },
        success: function (data) {
            if (data['result'] == 'success') $('#purchaseorderRegisterForm').submit();
            else alert('Purchase Order Items Create Failed');
        },
        error: function (error) {

        }
    });
}

function saveGRItems() {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/saveGRItems',
        method: 'post',
        dataType: 'json',
        data: {
            items: get_selected_items(),
            gr_id: $('#gr_id').val(),
            supplier: $('#supplier').val()
        },
        success: function (data) {
            if (data['result'] == 'success') $('#grUpdateForm').submit();
            else alert('Good Order Items Create Failed');
        },
        error: function (error) {

        }
    });
}

$(document).ready(function () {
    $('.select-all').click(function () {
        if ($(this).is(':checked')) {
            $(".row_check").prop('checked', true);
        } else {
            $(".row_check").prop('checked', false);
        }
    });
});

function get_selected_items() {
    var selected_items = [];
    for (var i = 0; i < item_data.length; i++) {
        if (item_data[i]['qty'] > 0) {
            selected_items.push(item_data[i]);
        }
    }
    return selected_items;
}

function getQuotationItems(qId) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getQuotationItems?q_id=' + qId,
        method: 'get',
        dataType: 'json',
        // data: {
        //     q_id: qId
        // },
        success: function (data) {
            item_data = data;
            // for (var i = 0; i < data.length; i++) {
            //     item_data[i]['qty'] = 0;
            //     $('.item_row').each(function(){
            //         if ($(this).find('.item_id').html() == data[i]['id']) item_data[i]['qty'] = $(this).find('.table_input').val();
            //     });
            // }
        },
        error: function (error) {

        }
    });
}