<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RENT-RIGHT-RENTALS</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="navbar">
        <div class="navbar_container">
         <a href="/" id="navbar_logo">RENTRIGHT-RENTALS</a>   
         <div class="navbar_toggle" id="mobile-menu">
         <span class="bar"></span><span class="bar"></span>
         <span class="bar"></span>
         </div>
         <ul class="navbar_menu">
            <li class="navbar_item">
                <a href="/INDEX.html" class="navbar_links">HOME</a>
            </li>
            <li class="navbar_item">
                <a href="/service.html" class="navbar_links">SERVICES</a>
                </li>
                <li class="navbar_item">
                    <a href="/PAY.html" class="navbar_links">PAY</a>
                    </li>
                    <li class="navbar_item">
                        <a href="/login.html" class="navbar_links">LOGIN</a>
                        </li>
                        <li class="navbar_item">
                            <a href="/REGISTER.HTML" class="navbar_links">REGISTER</a>
                            </li>
         </ul>
        </div>
    </nav>

    <div id="payment-section" class="section">
        <h2>Services and Payment</h2>
        <form id="payment-form" action="process_payment.php" method="post">
            <div class="service">
                <label>
                    <input type="checkbox" class="service-checkbox" name="service[]" value="Service 1" data-price="50">
                    Service 1 <span class="service-price">($50)</span>
                </label>
            </div>
            <div class="service">
                <label>
                    <input type="checkbox" class="service-checkbox" name="service[]" value="Service 2" data-price="75">
                    Service 2 <span class="service-price">($75)</span>
                </label>
            </div>
            <div class="service">
                <label>
                    <input type="checkbox" class="service-checkbox" name="service[]" value="Service 3" data-price="100">
                    Service 3 <span class="service-price">($100)</span>
                </label>
            </div>
            <div class="total">
                Total Amount: $<span id="total-amount">0.00</span>
            </div>
            <div class="payment-method">
                <label for="payment-method">Select Payment Method:</label>
                <select id="payment-method" name="payment_method" onchange="handlePaymentMethodChange()" required>
                    <option value="">Select Payment Method</option>
                    <option value="bank-card">Bank Card</option>
                    <option value="mpesa">M-Pesa</option>
                </select>
            </div>
            <div id="bank-card-details" style="display: none;">
                <label for="card-number">Card Number:</label>
                <input type="text" id="card-number" name="card_number" placeholder="Enter your card number">
                <label for="expiry-date">Expiry Date:</label>
                <input type="text" id="expiry-date" name="expiry_date" placeholder="MM/YY">
                <label for="cvv">CVV:</label>
                <input type="text" id="cvv" name="cvv" placeholder="CVV">
            </div>
            <div id="mpesa-details" style="display: none;">
                <label for="mpesa-number">M-Pesa Number:</label>
                <input type="text" id="mpesa-number" name="mpesa_number" placeholder="Enter your M-Pesa number">
            </div>
            <button type="submit" class="btn-submit">Submit Payment</button>
        </form>
    </div>

    <script>
        const checkboxes = document.querySelectorAll('.service-checkbox');
        const totalAmountElement = document.getElementById('total-amount');

        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                let total = 0;
                checkboxes.forEach(box => {
                    if (box.checked) {
                        total += parseFloat(box.getAttribute('data-price'));
                    }
                });
                totalAmountElement.textContent = total.toFixed(2);
            });
        });

        function handlePaymentMethodChange() {
            const paymentMethod = document.getElementById('payment-method').value;
            const bankCardDetails = document.getElementById('bank-card-details');
            const mpesaDetails = document.getElementById('mpesa-details');

            if (paymentMethod === 'bank-card') {
                bankCardDetails.style.display = 'block';
                mpesaDetails.style.display = 'none';
            } else if (paymentMethod === 'mpesa') {
                bankCardDetails.style.display = 'none';
                mpesaDetails.style.display = 'block';
            }
        }
    </script>
</body>
</html>