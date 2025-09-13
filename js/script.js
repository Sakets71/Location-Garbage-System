// JavaScript for interactive elements and functionality

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
    
    // Status update confirmation
    const statusForms = document.querySelectorAll('.status-form');
    statusForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const status = this.querySelector('select').value;
            if (!confirm(`Are you sure you want to mark this report as ${status}?`)) {
                e.preventDefault();
            }
        });
    });
    
    // Driver assignment confirmation
    const assignForms = document.querySelectorAll('.assign-form');
    assignForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const driverSelect = this.querySelector('select');
            const driverName = driverSelect.options[driverSelect.selectedIndex].text;
            if (!confirm(`Are you sure you want to assign this report to ${driverName}?`)) {
                e.preventDefault();
            }
        });
    });
    
    // Initialize maps if needed
    if (typeof initMap === 'function') {
        initMap();
    }
    
    // Auto-dismiss alerts after 5 seconds
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    forms.forEach(form => {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
    
    // Image preview for file uploads
    const imageInput = document.getElementById('image');
    if (imageInput) {
        imageInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('image-preview');
                    if (!preview) {
                        const previewDiv = document.createElement('div');
                        previewDiv.id = 'image-preview';
                        previewDiv.className = 'mt-3';
                        previewDiv.innerHTML = '<img src="" alt="Image Preview" class="img-thumbnail" style="max-height: 200px;">';
                        imageInput.parentNode.appendChild(previewDiv);
                    }
                    document.querySelector('#image-preview img').src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        });
    }
});

// Function to initialize map (to be implemented in pages that need maps)
function initMap() {
    // This will be implemented in admin/dashboard.php and driver/dashboard.php
    console.log('Map initialization function called');
}

// Geolocation functions
function getLocation() {
    if (navigator.geolocation) {
        document.getElementById('location-status').textContent = 'Retrieving location...';
        document.getElementById('location-status').className = 'text-info ms-2';
        
        navigator.geolocation.getCurrentPosition(
            showPosition, 
            handleLocationError,
            { timeout: 10000 }
        );
    } else {
        document.getElementById('location-status').textContent = 'Geolocation is not supported by this browser';
        document.getElementById('location-status').className = 'text-danger ms-2';
    }
}

function showPosition(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    
    document.getElementById('latitude').value = latitude;
    document.getElementById('longitude').value = longitude;
    document.getElementById('location-status').textContent = 'Location retrieved successfully';
    document.getElementById('location-status').className = 'text-success ms-2';
    
    // Reverse geocoding to get address (using Nominatim API)
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${latitude}&lon=${longitude}&zoom=18&addressdetails=1`)
        .then(response => response.json())
        .then(data => {
            if (data.display_name) {
                document.getElementById('location').value = data.display_name;
            }
        })
        .catch(error => {
            console.error('Error getting address:', error);
        });
}

function handleLocationError(error) {
    let errorMessage;
    switch(error.code) {
        case error.PERMISSION_DENIED:
            errorMessage = "User denied the request for Geolocation.";
            break;
        case error.POSITION_UNAVAILABLE:
            errorMessage = "Location information is unavailable.";
            break;
        case error.TIMEOUT:
            errorMessage = "The request to get user location timed out.";
            break;
        case error.UNKNOWN_ERROR:
            errorMessage = "An unknown error occurred.";
            break;
    }
    
    document.getElementById('location-status').textContent = errorMessage;
    document.getElementById('location-status').className = 'text-danger ms-2';
}

// Dashboard statistics chart (if Chart.js is included)
function initStatsChart() {
    const ctx = document.getElementById('statsChart');
    if (ctx) {
        // This would be implemented if we add charts to the dashboard
        console.log('Chart initialization would happen here');
    }
}