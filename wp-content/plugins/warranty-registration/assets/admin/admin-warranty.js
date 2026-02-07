jQuery(function ($) {

  $('#load_warranty_order').on('click', function () {

    const orderId = $('#warranty_order_id').val();
    if (!orderId) {
      alert('Enter Order ID');
      return;
    }

    $.post(ajaxurl, {
      action: 'load_warranty_order',
      order_id: orderId
    }, function (res) {

      if (!res || !res.success) {
        alert(res?.data || 'Order load failed');
        return;
      }

      // Fill warranty fields
      $('input[name="email"]').val(res.data.email);
      $('input[name="phone"]').val(res.data.phone);
      $('input[name="purchase_date"]').val(res.data.purchase_date);

      const tbody = $('#warranty-products');
      tbody.empty();

      res.data.items.forEach(item => {
        tbody.append(`
          <tr>
            <td>
              <input type="text" name="products[][model]" value="${item.model}">
            </td>
            <td>
              <input type="text" name="products[][size]" value="${item.size}">
            </td>
          </tr>
        `);
      });

    });

  });

});