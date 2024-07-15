document.addEventListener('DOMContentLoaded', function() {
    loadQuickStats();
    loadServiceHistory();
    loadPaymentSummary();
    loadMessages();

    document.getElementById('service-request-form').addEventListener('submit', submitServiceRequest);
    document.getElementById('send-message-form').addEventListener('submit', sendMessage);
});

function loadQuickStats() {
    fetch('get_quick_stats.php')
        .then(response => response.json())
        .then(data => {
            const quickStats = document.getElementById('quick-stats');
            quickStats.innerHTML = `
                <p>Rent Due: $${data.rentDue}</p>
                <p>Next Payment Date: ${data.nextPaymentDate}</p>
                <p>Open Service Requests: ${data.openServiceRequests}</p>
            `;
        })
        .catch(error => console.error('Error:', error));
}

function loadServiceHistory() {
    fetch('get_service_history.php')
        .then(response => response.json())
        .then(data => {
            const serviceHistory = document.getElementById('service-history');
            serviceHistory.innerHTML = data.map(service => `
                <div class="service-item">
                    <p>Type: ${service.type}</p>
                    <p>Status: ${service.status}</p>
                    <p>Date: ${service.date}</p>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

function loadPaymentSummary() {
    fetch('get_payment_summary.php')
        .then(response => response.json())
        .then(data => {
            const paymentSummary = document.getElementById('payment-summary');
            paymentSummary.innerHTML = `
                <p>Last Payment: $${data.lastPayment} on ${data.lastPaymentDate}</p>
                <p>Current Balance: $${data.currentBalance}</p>
            `;
        })
        .catch(error => console.error('Error:', error));
}

function loadMessages() {
    fetch('get_messages.php')
        .then(response => response.json())
        .then(data => {
            const messageList = document.getElementById('message-list');
            messageList.innerHTML = data.map(message => `
                <div class="message-item">
                    <p>From: ${message.from}</p>
                    <p>Date: ${message.date}</p>
                    <p>${message.content}</p>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

function submitServiceRequest(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('submit_service_request.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadServiceHistory();
    })
    .catch(error => console.error('Error:', error));
}

function sendMessage(event) {
    event.preventDefault();
    const formData = new FormData(event.target);
    fetch('send_message.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadMessages();
        event.target.reset();
    })
    .catch(error => console.error('Error:', error));
}