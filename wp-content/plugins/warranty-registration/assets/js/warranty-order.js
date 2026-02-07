document.addEventListener('DOMContentLoaded', function () {

    const loadBtn =
        document.getElementById('load_order') ||
        document.getElementById('load_warranty_order');

    if (!loadBtn) return;

    loadBtn.addEventListener('click', function () {

        const orderInput =
            document.getElementById('order_id') ||
            document.getElementById('warranty_order_id');

        if (!orderInput || !orderInput.value) {
            alert('Please enter Order ID');
            return;
        }

        const formData = new FormData();
        formData.append('action', 'load_warranty_order');
        formData.append('order_id', orderInput.value);

        fetch(warrantyData.ajax_url, {
            method: 'POST',
            credentials: 'same-origin',
            body: formData
        })
        .then(r => r.json())
        .then(res => {

            if (!res.success) {
                alert(res.data || 'Order not found');
                return;
            }

            // Populate customer fields
            const emailField = document.querySelector('input[name="email"]');
            if (emailField) emailField.value = res.data.email || '';

            const phoneField = document.querySelector('input[name="phone"]');
            if (phoneField) phoneField.value = res.data.phone || '';

            const purchaseDateField = document.querySelector('input[name="purchase_date"]');
            if (purchaseDateField) purchaseDateField.value = res.data.purchase_date || '';

            // Products table
            const tbody =
                document.querySelector('#warranty-products') ||
                document.querySelector('#products_table tbody');

            if (!tbody) return;

            tbody.innerHTML = '';

            res.data.items.forEach(item => {
                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td>
                            <input type="text" name="products[][model]" value="${item.model}" />
                        </td>
                        <td>
                            <input type="text" name="products[][size]" value="${item.size}" />
                        </td>
                    </tr>
                `);
            });
        })
        .catch(err => {
            console.error(err);
            alert('AJAX error');
        });

    });

});