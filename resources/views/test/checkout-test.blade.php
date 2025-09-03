<!DOCTYPE html>
<html>
<head>
    <title>Checkout Test</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, textarea, select { width: 300px; padding: 8px; border: 1px solid #ccc; }
        button { padding: 10px 20px; background: #007cba; color: white; border: none; cursor: pointer; }
        .error { color: red; margin-top: 5px; }
        .success { color: green; margin-top: 5px; }
    </style>
</head>
<body>
    <h1>Checkout Test Form</h1>
    
    <div id="result"></div>
    
    <form id="test-form">
        <div class="form-group">
            <label>Nama:</label>
            <input type="text" name="name" value="Test User" required>
        </div>
        
        <div class="form-group">
            <label>Email:</label>
            <input type="email" name="email" value="test@example.com" required>
        </div>
        
        <div class="form-group">
            <label>Telepon:</label>
            <input type="text" name="phone" value="08123456789" required>
        </div>
        
        <div class="form-group">
            <label>Alamat:</label>
            <textarea name="address" required>Jl. Test No. 123</textarea>
        </div>
        
        <div class="form-group">
            <label>Kota:</label>
            <input type="text" name="city" value="Jakarta" required>
        </div>
        
        <div class="form-group">
            <label>Provinsi:</label>
            <input type="text" name="province" value="DKI Jakarta" required>
        </div>
        
        <div class="form-group">
            <label>Kode Pos:</label>
            <input type="text" name="postal_code" value="12345" required>
        </div>
        
        <div class="form-group">
            <label>Ekspedisi:</label>
            <select name="shipping_expedition" required>
                <option value="">Pilih Ekspedisi</option>
                <option value="jne_reg">JNE REG</option>
                <option value="jne_yes">JNE YES</option>
                <option value="jnt_reg">J&T REG</option>
            </select>
        </div>
        
        <div class="form-group">
            <label>Ongkos Kirim:</label>
            <input type="number" name="shipping_cost" value="15000">
        </div>
        
        <div class="form-group">
            <label>Biaya COD:</label>
            <input type="number" name="cod_fee" value="0">
        </div>
        
        <div class="form-group">
            <label>Metode Pembayaran:</label>
            <input type="text" name="payment_method" value="midtrans">
        </div>
        
        <div class="form-group">
            <label>Catatan:</label>
            <textarea name="notes">Test order</textarea>
        </div>
        
        <button type="button" onclick="testValidation()">Test Validation</button>
        <button type="button" onclick="testCheckout()">Test Full Checkout</button>
    </form>

    <script>
        function testValidation() {
            const form = document.getElementById('test-form');
            const formData = new FormData(form);
            
            fetch('/checkout-debug-test', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('result').innerHTML = 
                    '<div class="' + (data.success ? 'success' : 'error') + '">' +
                    '<h3>Validation Test Result:</h3>' +
                    '<pre>' + JSON.stringify(data, null, 2) + '</pre>' +
                    '</div>';
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    '<div class="error"><h3>Error:</h3><pre>' + error.message + '</pre></div>';
            });
        }
        
        function testCheckout() {
            const form = document.getElementById('test-form');
            const formData = new FormData(form);
            
            fetch('/checkout', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.text().then(text => {
                        try {
                            const data = JSON.parse(text);
                            throw new Error(JSON.stringify(data, null, 2));
                        } catch (e) {
                            throw new Error('Response: ' + text);
                        }
                    });
                }
                return response.json();
            })
            .then(data => {
                document.getElementById('result').innerHTML = 
                    '<div class="success">' +
                    '<h3>Checkout Test Result:</h3>' +
                    '<pre>' + JSON.stringify(data, null, 2) + '</pre>' +
                    '</div>';
            })
            .catch(error => {
                document.getElementById('result').innerHTML = 
                    '<div class="error"><h3>Checkout Error:</h3><pre>' + error.message + '</pre></div>';
            });
        }
    </script>
</body>
</html>