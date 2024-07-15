document.addEventListener('DOMContentLoaded', function() {
    loadQuickStats();
    loadServiceRequests();
    loadPaymentHistory();
    loadTenants();
    loadProperties();
    loadApplications();
});

function loadQuickStats() {
    fetch('get_landlord_stats.php')
        .then(response => response.json())
        .then(data => {
            const quickStats = document.getElementById('quick-stats');
            quickStats.innerHTML = `
                <p>Total Properties: ${data.totalProperties}</p>
                <p>Occupied Units: ${data.occupiedUnits}</p>
                <p>Vacant Units: ${data.vacantUnits}</p>
                <p>Total Monthly Income: $${data.monthlyIncome}</p>
            `;
        })
        .catch(error => console.error('Error:', error));
}

function loadServiceRequests() {
    fetch('get_service_requests.php')
        .then(response => response.json())
        .then(data => {
            const serviceRequests = document.getElementById('service-requests');
            serviceRequests.innerHTML = data.map(request => `
                <div class="service-request-item">
                    <p>Tenant: ${request.tenant}</p>
                    <p>Type: ${request.type}</p>
                    <p>Status: ${request.status}</p>
                    <p>Date: ${request.date}</p>
                    <button onclick="updateServiceStatus(${request.id})">Update Status</button>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

function loadPaymentHistory() {
    fetch('get_payment_history.php')
        .then(response => response.json())
        .then(data => {
            const paymentHistory = document.getElementById('payment-history');
            paymentHistory.innerHTML = data.map(payment => `
                <div class="payment-item">
                    <p>Tenant: ${payment.tenant}</p>
                    <p>Amount: $${payment.amount}</p>
                    <p>Date: ${payment.date}</p>
                    <p>Status: ${payment.status}</p>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

function loadTenants() {
    fetch('get_tenants.php')
        .then(response => response.json())
        .then(data => {
            const tenantList = document.getElementById('tenant-list');
            tenantList.innerHTML = data.map(tenant => `
                <div class="tenant-item">
                    <p>Name: ${tenant.name}</p>
                    <p>Unit: ${tenant.unit}</p>
                    <p>Lease End: ${tenant.leaseEnd}</p>
                    <button onclick="viewTenantDetails(${tenant.id})">View Details</button>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

function loadProperties() {
    fetch('get_properties.php')
        .then(response => response.json())
        .then(data => {
            const propertyList = document.getElementById('property-list');
            propertyList.innerHTML = data.map(property => `
                <div class="property-item">
                    <p>Address: ${property.address}</p>
                    <p>Units: ${property.units}</p>
                    <p>Occupancy: ${property.occupancy}%</p>
                    <button onclick="viewPropertyDetails(${property.id})">View Details</button>
                </div>
            `).join('');
        })
        .catch(error => console.error('Error:', error));
}

// function loadApplications() {
//     fetch('get_applications.php')
//         .then(response => response.json())
//         .then(data => {
//             const applicationList = document.getElementById('application-list');
//             applicationList.innerHTML = data.map(application => `
//                 <div class="application-item">
//                     <p>Applicant: ${application.name}</p>
//                     <p>Unit: ${application.unit}</p>
//                     <p>Date: ${application.date}</p>
//                     <button onclick="reviewApplication(${application.id})">Review</button>
//                 </div>
//             `).join('');
//         })
//         .catch(error => console.error('Error:', error));
// }

function updateServiceStatus(requestId) {
    const newStatus = prompt("Enter new status (open/in progress/completed):");
    if (newStatus) {
        fetch('update_service_status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ requestId, newStatus }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message);
            loadServiceRequests();
        })
        .catch(error => console.error('Error:', error));
    }
}

function viewTenantDetails(tenantId) {
    fetch(`get_tenant_details.php?id=${tenantId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Tenant Details</h2>
                    <p>Name: ${data.name}</p>
                    <p>Email: ${data.email}</p>
                    <p>Phone: ${data.phone}</p>
                    <p>Unit: ${data.unit}</p>
                    <p>Lease Start: ${data.leaseStart}</p>
                    <p>Lease End: ${data.leaseEnd}</p>
                    <p>Rent: $${data.rent}</p>
                    <p>Balance: $${data.balance}</p>
                </div>
            `;
            document.body.appendChild(modal);
            modal.style.display = 'block';

            const closeBtn = modal.querySelector('.close');
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                modal.remove();
            }
        })
        .catch(error => console.error('Error:', error));
}

function viewPropertyDetails(propertyId) {
    fetch(`get_property_details.php?id=${propertyId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Property Details</h2>
                    <p>Address: ${data.address}</p>
                    <p>Total Units: ${data.totalUnits}</p>
                    <p>Occupied Units: ${data.occupiedUnits}</p>
                    <p>Vacant Units: ${data.vacantUnits}</p>
                    <p>Total Monthly Rent: $${data.totalMonthlyRent}</p>
                    <p>Building Type: ${data.buildingType}</p>
                    <p>Year Built: ${data.yearBuilt}</p>
                </div>
            `;
            document.body.appendChild(modal);
            modal.style.display = 'block';

            const closeBtn = modal.querySelector('.close');
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                modal.remove();
            }
        })
        .catch(error => console.error('Error:', error));
}

function reviewApplication(applicationId) {
    fetch(`get_application_details.php?id=${applicationId}`)
        .then(response => response.json())
        .then(data => {
            const modal = document.createElement('div');
            modal.className = 'modal';
            modal.innerHTML = `
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Application Review</h2>
                    <p>Applicant: ${data.name}</p>
                    <p>Email: ${data.email}</p>
                    <p>Phone: ${data.phone}</p>
                    <p>Desired Unit: ${data.desiredUnit}</p>
                    <p>Proposed Move-in Date: ${data.moveInDate}</p>
                    <p>Employment: ${data.employment}</p>
                    <p>Income: $${data.income}</p>
                    <p>Credit Score: ${data.creditScore}</p>
                    <button onclick="approveApplication(${applicationId})">Approve</button>
                    <button onclick="rejectApplication(${applicationId})">Reject</button>
                </div>
            `;
            document.body.appendChild(modal);
            modal.style.display = 'block';

            const closeBtn = modal.querySelector('.close');
            closeBtn.onclick = function() {
                modal.style.display = 'none';
                modal.remove();
            }
        })
        .catch(error => console.error('Error:', error));
}

function approveApplication(applicationId) {
    fetch('approve_application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ applicationId: applicationId })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadApplications();
    })
    .catch(error => console.error('Error:', error));
}

function rejectApplication(applicationId) {
    fetch('reject_application.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ applicationId: applicationId })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        loadApplications();
    })
    .catch(error => console.error('Error:', error));
}