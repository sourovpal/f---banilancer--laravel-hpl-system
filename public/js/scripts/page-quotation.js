$(document).ready(function(){
  var costcentre = 0, qn_from = '', qn_to = '';

  $("#addItemForm").validate({
    rules: {
      itemname: {
        required: true,
        // minlength: 5
      },
      itemspecification: {
        required: true,
        // minlength: 5
      },
      itemunit: {
        required: true,
        // minlength: 5
      },
      itempack: {
        required: true,
        // minlength: 5
      },
      itemprice: {
          required: true,
          min: 1
      },
      itemqty: {
          required: true,
          digits: true,
          min: 1
      },
      remarks: {
          required: true,
        //   minlength: 5
      }
    },
    errorElement: 'div'
  });
  
  $('#cc_error').hide();

  $('#old_costcenter').change(function(){
      $('#costcentre').val($(this).val());
      $('#modal-costcentre').val($(this).val());
      $('#cc_error').hide();
  });

  itemsTable = $("#item_table").DataTable({
    responsive: true,
    'columnDefs': [{
      "orderable": false
    }]
  });

  $('#costcentre').change(function(){
    costcentre = $(this).val();
    getReports(costcentre, qn_from, qn_to);
  });

  $('#qn_from').change(function(){
    qn_from = $(this).val();
    getReports(costcentre, qn_from, qn_to);
  });

  $('#qn_to').change(function(){
    qn_to = $(this).val();
    getReports(costcentre, qn_from, qn_to);
  });
  
  $('#quotation_create_btn').click(function(){
      if ($('#costcentre').val() != '0') $('#quotationRegisterForm').submit();
      else $('#cc_error').show();
  });
});

function getReports(costcentre, qn_from, qn_to) {
  $.ajax({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      url: '/getQuotationReports',
      method: 'post',
      dataType: 'json',
      data: {
          costcentre: costcentre,
          qn_from: qn_from,
          qn_to: qn_to
      },
      success: function(data) {
          html = '';
          for (var i = 0; i < data.length; i++) {
              html += `
                  <tr>
                      <td>` + data[i]['qn_no'] + `</td>
                      <td>` + data[i]['user'] + `</td>
                      <td>` + data[i]['qn_date'] + `</td>
                      <td>` + data[i]['item_name'] + `</td>
                      <td>` + data[i]['specification'] + `</td>
                      <td>` + data[i]['request_qty'] + `</td>
                      <td>` + data[i]['unit'] + `</td>
                      <td>` + data[i]['pack'] + `</td>
                      <td>` + data[i]['price'] + `</td>
                      <td>` + data[i]['total_price'] + `</td>
                  </tr>
              `;
          }
          $('#item_table tbody').html(html);
      },
      error: function(error) {
          alert('ajax failed')
      }
  });
}