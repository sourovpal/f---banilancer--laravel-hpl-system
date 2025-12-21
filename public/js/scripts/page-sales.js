var item_data;

$(document).ready(function () {

    var category = 0, item_id = 0, specification = "", item_name = "";
    var items = "";
    var costcentre = 0, item = 0, so_from = '', so_to = '', dn_from = '', dn_to = '';

    $('.nested_table_wrap').hide();
    $('.nested_table_hide').hide();

    function format(data) {
        var childTable = '<table class="child-table" border="1">' +
            '<thead>' +
            '<tr>' +
            '<th>Title</th>' +
            '<th>Specification</th>' +
            '<th>Unit</th>' +
            '<th>Pack</th>' +
            '<th>Qty</th>' +
            '<th>Price</th>' +
            '<th>Remarks</th>' +
            '</tr>' +
            '</thead>' +
            '<tbody>';
        if (data.length > 0) {
            for (let i = 0; i < data.length; i++) {
                childTable += '<tr>' +
                    '<td>' + data[i]['name'] + '</td>' +
                    '<td>' + data[i]['specification'] + '</td>' +
                    '<td>' + data[i]['unit'] + '</td>' +
                    '<td>' + data[i]['pack'] + '</td>' +
                    '<td>' + data[i]['qty'] + '</td>' +
                    '<td>' + data[i]['price'] + '</td>' +
                    '<td>' + data[i]['remarks'] + '</td>' +
                    '</tr>';
            }
        } else {
            childTable +=
                '<tr>' +
                '<td colspan="8"> No Record Found </td>' +
                '</tr>';
        }

        childTable +=
            '</tbody>' +
            '</table>';
        return childTable;
    }

    $('.nested_table_show').click(function () {
        let code = $(this).data('code');
        let _token = $('meta[name="csrf-token"]').attr('content');

        $(this).parent().parent().next().fadeIn();
        // Get Table data
        var table = $('#users-list-datatable').DataTable();
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        $.ajax({
            type: "POST",
            url: "/quotation-list-render",
            data: { _token: _token, code: code },
            success: function (data) {
                if (!row.child.isShown()) {
                    row.child(format(data)).show();
                    tr.addClass('shown');
                }
            }
        });
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
        $('#total_price').val(Number($(this).val()) * Number($('#unitprice').val()));
    });

    $('#unitprice').change(function () {
        $('.totalprice').html(Number($(this).val()) * Number($('#qty').val()));
        $('#total_price').val(Number($(this).val()) * Number($('#qty').val()));
    });

    $('#category').change(function () {
        category = $(this).val();
        showLoader();
        getItems(category, item_id, item_name, specification);
    });

    $(document).ready(function () {
        // 当用户在 #search_id 输入框中输入时触发
        $('#search_id').on('input', function () {
            var itemId = $(this).val();  // 获取输入框的值
            if (itemId.length > 0) {     // 可以设置一个条件，例如至少输入一定数量的字符
                // 假设其他参数也需要从某处获取或定义默认值
                var category = "";
                var name = "";  // 根据需要获取或设置
                var specification = "";  // 根据需要获取或设置

                getItems(category, itemId, name, specification);
            }
        });
    });


    //$('#search_id').change(function () {
    //    item_id = $(this).val();
    //    getItems(category, item_id, item_name, specification);
    //});

    $('#search_name').keyup(function () {
        item_name = $(this).val();
        showLoader();
        getItems(category, item_id, item_name, specification);
    });

    $('#search_description').keyup(function () {
        specification = $(this).val();
        showLoader();
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
        showLoader();
        getItems(category, item_id, item_name, specification);
    });

    getAllItems();

    $('#save_all').click(function () {
        saveItems();
    });

    $('#create_po').click(function (e) {
        e.preventDefault();
        savePOItems();
    });

    $('#create_gr').click(function (e) {
        e.preventDefault();
        saveGRItems();
    });

    $('.sales_order_approve').click(function () {
        $('#so_id').val($(this).attr('so_id'));
        $('#appruser_id').val($(this).attr('appruser_id'));
    });

    $('#approve_btn').click(function () {
        $(this).prop('disabled', true);
        $(this).text('Submitting');
        if ($('#user_id').val() == $('#appruser_id').val()) {
            approveSalesorder($('#so_id').val());
        }
        else {
            $(this).prop('disabled', false);
            $(this).text('Approve');
            alert('Sorry, You cannot approve this Sales Order because you are not appointed for this Sales Order.');
        }

        $('.modal-action.modal-close').click();
    });

    $('#reject_btn').click(function () {
        $(this).prop('disabled', true);
        $(this).text('Submitting');
        if ($('#user_id').val() == $('#appruser_id').val()) {
            rejectSalesorder($('#so_id').val());
        }
        else {
            $(this).prop('disabled', false);
            $(this).text('Reject');
            alert('Sorry, You cannot reject this Sales Order because you are not appointed for this Sales Order.');
        }
    });

    $('#costcentre').change(function () {
        costcentre = $(this).val();
        getReports(costcentre, item, so_from, so_to, dn_from, dn_to);
    });

    $('#item').change(function () {
        item = $(this).val();
        getReports(costcentre, item, so_from, so_to, dn_from, dn_to);
    });

    $('#so_from').change(function () {
        so_from = $(this).val();
        getReports(costcentre, item, so_from, so_to, dn_from, dn_to);
    });

    $('#so_to').change(function () {
        so_to = $(this).val();
        getReports(costcentre, item, so_from, so_to, dn_from, dn_to);
    });

    $('#dn_from').change(function () {
        dn_from = $(this).val();
        getReports(costcentre, item, so_from, so_to, dn_from, dn_to);
    });

    $('#dn_to').change(function () {
        dn_to = $(this).val();
        getReports(costcentre, item, so_from, so_to, dn_from, dn_to);
    });

    $('#request_qty').change(function () {
        $('#total_price').val($(this).val() * $('#unit_price').val());
        $('#total_price_display').html($(this).val() * $('#unit_price').val());
    });

    $('#unit_price').change(function () {
        $('#total_price').val($(this).val() * $('#request_qty').val());
        $('#total_price_display').html($(this).val() * $('#request_qty').val());
    });
});

function getReports(costcentre, item, so_from, so_to, dn_from, dn_to) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/getSalesOrderReports',
        method: 'post',
        dataType: 'json',
        data: {
            costcentre: costcentre,
            item: item,
            so_from: so_from,
            so_to: so_to,
            dn_from: dn_from,
            dn_to: dn_to
        },
        success: function (data) {
            html = '';
            for (var i = 0; i < data.length; i++) {
                html += `
                    <tr>
                        <td>` + data[i]['order_no'] + `</td>
                        <td>` + data[i]['user'] + `</td>
                        <td>` + data[i]['costcentre'] + `</td>
                        <td>` + data[i]['order_date'] + `</td>
                        <td>` + data[i]['dn_no'] + `</td>
                        <td>` + data[i]['dn_date'] + `</td>
                        <td>` + data[i]['item_code'] + `</td>
                        <td>` + data[i]['specification'] + `</td>
                        <td>` + data[i]['request_qty'] + `</td>
                        <td>` + data[i]['unit'] + `</td>
                        <td>` + data[i]['packing'] + `</td>
                        <td>` + data[i]['unit_price'] + `</td>
                        <td>` + data[i]['total_price'] + `</td>
                        <td>` + data[i]['approver'] + `</td>
                        <td>` + data[i]['approve_date'] + `</td>
                        <td><a href="/special-sales-order-handling/` + data[i]['id'] + `"><i class="material-icons">edit</i></a></td>
                    </tr>
                `;
            }
            $('#report_table tbody').html(html);
        },
        error: function (error) {

        }
    });
}

function rejectSalesorder(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/rejectSO',
        method: 'post',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            window.location.href = "/current-sales-order-list";
        },
        error: function (error) {

        }
    });
}

function approveSalesorder(id) {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/approveSO',
        method: 'post',
        dataType: 'json',
        data: {
            id: id
        },
        success: function (data) {
            window.location.href = "/current-sales-order-list";
        },
        error: function (error) {

        }
    });
}

function saveItems() {
    var all_qty = 0;
    for (var i = 0; i < item_data.length; i++) {
        all_qty += item_data[i]['qty'];
    }
    if (!all_qty) alert('Please Select a Item.');
    else if ($('#costcentre').val() == '0') alert('Please Select a Cost Center.');
    else if ($('#approver').val() == '0') alert('Please Select a Approver.');
    else {
        $("#save_all").text('Creating..');
        $("#save_all").prop('disabled', true);

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/salesorder_create',
            method: 'post',
            dataType: 'json',
            data: {
                items: get_selected_items(),
                so_id: $('#so_id').val(),
                dn_id: $('#dn_id').val(),
                costcentre: $('#costcentre').val(),
                approver: $('#approver').val(),
                remarks: $('#remarks').val(),
            },
            success: function (data) {
                $("#save_all").text('Create');
                $("#save_all").prop('disabled', false);
                if (data['result'] == 'success') {
                    window.location.href = "/salesorder_create_process";
                }
            },
            error: function (error) {

            }
        });
    }

}

function savePOItems() {
    var all_qty = 0;
    for (var i = 0; i < item_data.length; i++) {
        all_qty += item_data[i]['qty'];
    }
    console.log(all_qty);
    if (!all_qty) alert('Please Select a Item.');
    else if ($('#supplier').val() == '0') alert('Please Selelct a Supplier');
    else {
        $("#create_po").text('Creating..');
        $("#create_po").prop('disabled', true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/purchaseorder_create',
            method: 'post',
            dataType: 'json',
            data: {
                items: get_selected_items(),
                supplier: $('#supplier').val(),
                remarks: $('#remarks').val(),
                payment_term: $('#payment_term').val(),
            },
            success: function (data) {
                $("#create_po").text('Create');
                $("#create_po").prop('disabled', false);
                if (data['result'] == 'success') {
                    window.location.href = "/current-purchase-order-list";
                }
            },
            error: function (error) {

            }
        });
    }

}

function saveGRItems() {
    var all_qty = 0;
    for (var i = 0; i < item_data.length; i++) {
        all_qty += item_data[i]['qty'];
    }
    if (!all_qty) alert('Please Select a Item.');
    else if ($('#supplier').val() == '0') alert('Please Select a Supplier');
    else {
        $("#create_gr").text('Creating..');
        $("#create_gr").prop('disabled', true);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/create-good-receive-record',
            method: 'post',
            dataType: 'json',
            data: {
                items: get_selected_items(),
                supplier: $('#supplier').val(),
                remarks: $('#remarks').val(),
            },
            success: function (data) {
                $("#create_gr").text('Create');
                $("#create_gr").prop('disabled', false);
                if (data['result'] == 'success') {
                    window.location.href = "/current-good-receive-list";
                }
            },
            error: function (error) {

            }
        });
    }


}

function showLoader() {
    $("#modal-content-loader").addClass("model-loading");
    document.getElementById("loader").style.display = "block";
}

// Hide the loader
function hideLoader() {
    $("#modal-content-loader").removeClass("model-loading");
    document.getElementById("loader").style.display = "none";
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
                <tr class="item_row" data-item-id="` + item_data[i]['id'] + `">
                    <td>` + item_data[i]['code'] + `</td>
                    <td>` + item_data[i]['name'] + `</td>
                    <td>` + item_data[i]['specification'] + `</td>
                    <td>` + item_data[i]['unit'] + `</td>
                    <td>` + item_data[i]['pack'] + `</td>
                    <td><input type="number" item_id="` + item_data[i]['id'] + `" class="table_input_cost" value="` + item_data[i]['price'] + `"></td>
                    <td><input type="number" item_id="` + item_data[i]['id'] + `" class="table_input" value="` + item_data[i]['qty'] + `"></td>
                    <td><input type="text" item_id="` + item_data[i]['id'] + `" class="table_input" value="${item_data[i]['remark'] !== null ? item_data[i]['remark'] : ''}"></td>
                    <td></td>
                    <td><a href="" class="item_cancel modal-trigger" data-item-id="` + item_data[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
            else html += `
                <tr class="item_row" data-item-id="` + item_data[i]['id'] + `">
                    <td>` + item_data[i]['code'] + `</td>
                    <td>` + item_data[i]['name'] + `</td>
                    <td>` + item_data[i]['specification'] + `</td>
                    <td>` + item_data[i]['unit'] + `</td>
                    <td>` + item_data[i]['pack'] + `</td>
                    <td>` + item_data[i]['price'] + `</td>
                    <td><input type="number" item_qty="` + item_data[i]['id'] + `"   class="table_input_qty" value="` + item_data[i]['qty'] + `"></td>
					<td><input type="text" item_remark="` + item_data[i]['id'] + `"  class="table_input" value="${item_data[i]['remark'] !== null ? item_data[i]['remark'] : ''}"></td>
                    <td></td>
                    <td><a href="" class="item_cancel modal-trigger" data-item-id="` + item_data[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
        }
    }
    $('#item_table tbody').html(html);
    removePrice();

    // Attach click event using event delegation

    itemsTable = $("#item_table").DataTable({
        responsive: true,
        'columnDefs': [{
            "orderable": false
        }]
    });

    $('.table_input').change(function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).attr('item_id')) {
                item_data[i]['qty'] = $(this).val();
                break;
            }
        }
    });



    $('.table_input_cost').change(function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).attr('item_id')) {
                item_data[i]['price'] = $(this).val();
                break;
            }
        }
    });

    // $('.item_cancel').click(function () {
    //     $('#confirm_modal').find('#item_id_cancel').val($(this).attr('item_id'));
    // });

    // $('#yes_btn').click(function () {
    //     for (var i = 0; i < item_data.length; i++) {
    //         if (item_data[i]['id'] == $(this).parent().parent().find('#item_id_cancel').val()) {
    //             item_data[i]['qty'] = 0;
    //             break;
    //         }
    //     }
    //     $('#cancel_btn').click();
    //     showItems(item_data);
    // });
}

$(document).on('click', '.item_cancel', function (event) {
    event.preventDefault(); // Prevent the default action
    var itemId = $(this).data('item-id');
    if (confirm('Are you sure that you will delete this item?')) {
        //action confirmed
        $('tr[data-item-id="' + itemId + '"]').remove();
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == itemId) {
                item_data[i]['qty'] = 0;
                break;
            }
        }
        showItems(item_data);
        console.log('Ok is clicked.', item_data);
    } else {
        //action cancelled
        console.log('Cancel is clicked.');
    }
});

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
            item_code: id,
            name: name,
            specification: specification
        },
        success: function (data) {
            var html = "";
            // 先按照 code 属性对 data 进行排序
            data.sort(function (a, b) {
                if (a.code < b.code) {
                    return -1; // 如果 a.code 在字典顺序上位于 b.code 之前，返回 -1
                }
                if (a.code > b.code) {
                    return 1; // 如果 a.code 在字典顺序上位于 b.code 之后，返回 1
                }
                return 0; // 如果 a.code 和 b.code 相等，返回 0
            });

            for (var i = 0; i < data.length; i++) {
                html += `
					<tr class="data_row">
						<td>
							<label>
								<input type="checkbox" class="row_check" item_id="` + data[i]['id'] + `" />
								<span></span>
							</label>
						</td>`;
                if (data[i]['image']) {
                    html += `<td class="text-center"><img class="item_image" src="../upload/item/` + data[i]['image'] + `" alt=""></td>`;
                } else {
                    html += `<td class="text-center"><img class="item_image" src="../images/notFound.jpg" alt=""></td>`;
                }

                html += `<td>` + data[i]['code'] + `</td>
						<td>` + data[i]['name'] + `</td>
						<td>` + data[i]['specification'] + `</td>
						<td>` + data[i]['pack'] + `</td>
					</tr>
				`;
            }
            $('#modal_item tbody').html(html);
            hideLoader();
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
$(document).on("change", ".table_input", function () {
    for (var i = 0; i < item_data.length; i++) {
        if (item_data[i]['id'] == $(this).attr('item_remark')) {
            item_data[i]['remark'] = $(this).val();
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                // /* the route pointing to the post function */
                // url: '/item_remark_update',
                // type: 'POST',
                // /* send the csrf-token and the input to the controller */
                // data: { _token: CSRF_TOKEN, itemRemark: item_data[i]['remark'], id: item_data[i]['id'] },
                // dataType: 'JSON',
                // /* remind that 'data' is the response of the AjaxController */
                // success: function (data) {
                //     console.log('done')
                // }
            });
        }
    }
});

$(document).on("change", ".table_input_qty", function () {
    for (var i = 0; i < item_data.length; i++) {
        if (item_data[i]['id'] == $(this).attr('item_qty')) {
            item_data[i]['qty'] = $(this).val();
        }
    }
});

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
    console.log("----------------------------------------------", selected_items);
    return selected_items;
}