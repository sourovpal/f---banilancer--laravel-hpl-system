var item_data;
var onLoad_itemData;
$(document).ready(function () {
    localStorage.removeItem('key');

    onloadItem();
    var category = 0, item_id = 0, specification = "", item_name = "";
    var items = "";
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

    // $('#search_id').keyup(function () {
    //     item_id = $(this).val();
    //     showLoader();
    //     getItems(category, item_id, item_name, specification);
    // });

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
        getItems(category, item_id, item_name, specification);
    });

    getAllItems();

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

    $(document).on("change", ".table_input", function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).attr('item_id')) {
                item_data[i]['qty'] = $(this).val();
                break;
            }
        }
    });

    $(document).on("change", ".table_input_remark", function () {
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == $(this).attr('item_remark')) {
                item_data[i]['remark'] = $(this).val();
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
});

function saveItems() {

    let isVal = false;
    const qty = [];
    const itemId = [];
    const remark = [];

    $('#saleOrderTable .item_row').each(function () {
        var _qty = $(this).find('.item_qty input').val();
        var _itemId = $(this).data('item-id');
        var _remark = $(this).find('.item_remark input').val();
        qty.push(_qty);
        itemId.push(_itemId);
        remark.push(_remark);
        if(_qty <= 0){
            isVal = true;
        }
    });

    const _costcenter = $("#costcentre").val();
    const _approver = $("#approver").val();
    const _remove_so_item_id = $("#remove_so_item_id").val();
    const _remarks = $("#remarks").val();

    // console.log(qty, itemId, remark, _costcenter, _approver, _remove_so_item_id, get_selected_items());
    if (_costcenter == '0'){
        alert('Please Select a Cost Center.');
    }
    else if (isVal){
        alert('Quantity must be a positive number');
    }
    else if
        (_approver == '0') {alert('Please Select a Approver.');}
    else {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: '/saveItems',
            method: 'post',
            dataType: 'json',
            data: {
                remarks: _remarks,
                approver: _approver,
                costcentre: _costcenter,
                so_id: $('#edit_so_id').val(),
                dn_id: $('#edit_dn_id').val(),
                qty: qty,
                itemId: itemId,
                remark: remark,
                remove_so_item: _remove_so_item_id,
                items: get_selected_items(),
            },
            success: function (data) {
                localStorage.removeItem('key');
                if (data['result'] == 'success') $('#salesorderUpdateForm').submit();
                else alert('Sales Order Items Create Failed');
            },
            error: function (error) {

            }
        });
    }
    return 0;
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

// const itemId = [];

function showItems(item_data) {
    var html = "";
    onloadItem();
    var value = localStorage.getItem('key');
    var array = [];
    var result1 = [];
    if (value){
        array = value.split(',');
    }
    if (array.length > 0) {

        // Check each element in arr1
        for (let i = 0; i < onLoad_itemData.length; i++) {
            let isDuplicate = false;
            for (let j = 0; j < array.length; j++) {
                console.log(onLoad_itemData[i] === Number(array[j]),onLoad_itemData[i] , Number(array[j]))
                if (onLoad_itemData[i]['id']  === Number(array[j])) {
                    isDuplicate = true;
                    break;
                }
            }
            // If the element is not found in arr2, add to result1
            if (!isDuplicate) {
                html += `
                        <tr class="item_row" data-item-id="` + onLoad_itemData[i]['id'] + `">
                            <td>` + onLoad_itemData[i]['code'] + `</td>
                            <td>` + onLoad_itemData[i]['name'] + `</td>
                            <td>` + onLoad_itemData[i]['specification'] + `</td>
                            <td>` + onLoad_itemData[i]['unit'] + `</td>
                            <td>` + onLoad_itemData[i]['pack'] + `</td>
                            <td><input type="number" item_id="` + onLoad_itemData[i]['id'] + `" class="table_input" value="` + onLoad_itemData[i]['qty'] + `"></td>
                            <td><input type="text" item_remark="` + onLoad_itemData[i]['id'] + `" class="table_input_remark" value="` + onLoad_itemData[i]['remark'] + `"></td>
                            <td><a href="#" class="item_cancel modal-trigger" data-item-id="` + onLoad_itemData[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                        </tr>
                    `;
                result1.push(onLoad_itemData[i]);
            }
        }
    } else {
        for (var i = 0; i < onLoad_itemData.length; i++) {
            html += `
                <tr class="item_row" data-item-id="` + onLoad_itemData[i]['id'] + `">
                    <td>` + onLoad_itemData[i]['code'] + `</td>
                    <td>` + onLoad_itemData[i]['name'] + `</td>
                    <td>` + onLoad_itemData[i]['specification'] + `</td>
                    <td>` + onLoad_itemData[i]['unit'] + `</td>
                    <td>` + onLoad_itemData[i]['pack'] + `</td>
                    <td><input type="number" item_id="` + onLoad_itemData[i]['id'] + `" class="table_input" value="` + onLoad_itemData[i]['qty'] + `"></td>
                    <td><input type="text" item_remark="` + onLoad_itemData[i]['id'] + `" class="table_input_remark" value="` + onLoad_itemData[i]['remark'] + `"></td>
                    <td><a href="#" class="item_cancel modal-trigger" data-item-id="` + onLoad_itemData[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
        }
    }
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
                    <td><input type="text" item_id="` + item_data[i]['id'] + `" class="table_input" value="` + item_data[i]['qty'] + `"></td>
                    <td><a href="#" class="item_cancel modal-trigger" data-item-id="` + item_data[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
            else html += `
                <tr class="item_row" data-item-id="` + item_data[i]['id'] + `">
                    <td>` + item_data[i]['code'] + `</td>
                    <td>` + item_data[i]['name'] + `</td>
                    <td>` + item_data[i]['specification'] + `</td>
                    <td>` + item_data[i]['unit'] + `</td>
                    <td>` + item_data[i]['pack'] + `</td>
                    <td><input type="number" item_id="` + item_data[i]['id'] + `" class="table_input" value="` + item_data[i]['qty'] + `"></td>
                    <td><input type="text" item_remark="` + item_data[i]['id'] + `" class="table_input_remark" value="` + item_data[i]['remark'] + `"></td>
                    <td><a href="#" class="item_cancel modal-trigger" data-item-id="` + item_data[i]['id'] + `"><i class="material-icons">cancel</i></a></td>
                </tr>
            `;
        }
    }

    $('#item_table tbody').empty();
    $('#item_table tbody').append(html);

    itemsTable = $("#item_table").DataTable({
        responsive: true,
        'columnDefs': [{
            "orderable": false
        }]
    });

}

const remove_item_storage = [];
$(document).on('click', '.item_cancel', function (event) {
    event.preventDefault(); // Prevent the default action
    var itemId = $(this).data('item-id');
    if (confirm('Are you sure that you will delete this item?')) {
        //action confirmed
        remove_item_storage.push(itemId);
        localStorage.setItem('key', remove_item_storage);
        $('tr[data-item-id="' + itemId + '"]').remove();
        for (var i = 0; i < item_data.length; i++) {
            if (item_data[i]['id'] == itemId) {
                item_data[i]['qty'] = 0;
                break;
            }
        }
        // showItems(item_data);
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
            var html = "";
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

function savePOItems() {

    let isVal = false;
    const qty = [];
    const itemId = [];
    const remark = [];

    $('#purchaseOrderTable .item_row').each(function () {
        var _itemId = $(this).data('item-id');
        var _qty = $(this).find('.item_qty input').val();
        var _remark = $(this).find('.item_remark input').val();
        qty.push(_qty);
        itemId.push(_itemId);
        remark.push(_remark);
        if(_qty <= 0){
            isVal = true;
        }
    });


    const _status = $("#status").val();
    const _remarks = $("#remarks").val();
    const _supplier = $("#supplier").val();
    const _payment_term = $("#payment_term").val();
    const _remove_po_item_id = $("#remove_po_item_id").val();

    var all_qty = 0;
    for (var i = 0; i < item_data.length; i++) {
        all_qty += item_data[i]['qty'];
    }
    console.log(_supplier,qty,itemId,remark,_remarks,_payment_term,_status,_remove_po_item_id,get_selected_items(),"-------",all_qty);


    if(_supplier === '0'){
        alert('Please Select a Suppliers.');
    }
    else if (isVal){
        alert('Quantity must be a positive number');
    }
    else {
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/savePOItems',
        method: 'post',
        dataType: 'json',
        data: {
            status:_status,
            remarks: _remarks,
            supplier: _supplier,
            po_id: $('#po_id').val(),
            payment_term:_payment_term,

                qty: qty,
                itemId: itemId,
                remark: remark,
                remove_po_item: _remove_po_item_id,
                items: get_selected_items(),
            },
            success: function (data) {
                localStorage.removeItem('key');
                if (data['result'] == 'success') $('#purchaseorderRegisterForm').submit();
                else alert('Purchase Order Items Create Failed');
            },
            error: function (error) {

            }
        });
    }
    return 0;
}

function onloadItem() {
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    if ($('#po_id').val()) {
        $.ajax({
            url: '/po_items',
            type: 'POST',
            data: {_token: CSRF_TOKEN, data: $("#po_id").val()},
            dataType: 'JSON',
            success: function (data) {
                onLoad_itemData = data.items;
                // console.log('done', data.items);
            }
        });
    }
    if ($("#edit_so_id").val()) {
        $.ajax({
            url: '/so_items',
            type: 'POST',
            data: {_token: CSRF_TOKEN, data: $("#edit_so_id").val()},
            dataType: 'JSON',
            success: function (data) {
                onLoad_itemData = data.items;
                console.log('done', data.items);
            }
        });
    }


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
            supplier: $('#supplier').val(),
            status: $('#status').val()
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