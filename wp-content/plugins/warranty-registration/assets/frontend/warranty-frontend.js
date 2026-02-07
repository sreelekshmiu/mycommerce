jQuery(function ($) {

  $('#load_order').on('click', function () {

    const orderId = $('#order_id').val();
    if (!orderId) {
      alert('Enter Order ID');
      return;
    }

    $.post(warrantyData.ajax_url, {
      action: 'load_warranty_order',
      order_id: orderId,
      _ajax_nonce: warrantyData.nonce
    }, function (res) {

      if (!res.success) {
        alert(res.data);
        return;
      }

      $('input[name="email"]').val(res.data.email);
      $('input[name="phone"]').val(res.data.phone);
      $('#purchase_date').val(res.data.purchase_date);

      const tbody = $('#products_table tbody');
      tbody.empty();

      res.data.items.forEach((p, i) => {
        tbody.append(`
          <tr>
            <td>
              <input type="text" name="products[${i}][model]" value="${p.model}" required>
            </td>
            <td>
              <input type="text" name="products[${i}][size]" value="${p.size}" required>
            </td>
            <td>
              <input type="number" name="products[${i}][qty]" value="1" min="1">
            </td>
          </tr>
        `);
      });

    });

  });

});