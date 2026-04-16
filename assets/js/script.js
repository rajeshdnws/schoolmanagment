/* ========================================
   School Management System - Main JavaScript
   ======================================== */

document.addEventListener('DOMContentLoaded', function() {
    initializeDropdowns();
    initializeFormValidation();
    initializeTableFeatures();
});

/* ========== Dropdown Menu Functionality ========== */
function initializeDropdowns() {
    const dropdowns = document.querySelectorAll('.dropdown');
    
    dropdowns.forEach(dropdown => {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        const menu = dropdown.querySelector('.dropdown-menu');
        
        if (toggle && menu) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
            });
        }
    });
    
    // Close dropdowns when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            document.querySelectorAll('.dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });
}

/* ========== Form Validation ========== */
function initializeFormValidation() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!validateForm(form)) {
                e.preventDefault();
            }
        });
    });
}

function validateForm(form) {
    const requiredFields = form.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#e74c3c';
            field.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.style.borderColor = '';
                }
            });
            isValid = false;
        }
    });
    
    if (!isValid) {
        showAlert('Please fill all required fields!', 'error');
    }
    
    return isValid;
}

/* ========== Table Features ========== */
function initializeTableFeatures() {
    const tables = document.querySelectorAll('.table');
    
    tables.forEach(table => {
        // Add sorting functionality
        const headers = table.querySelectorAll('th');
        headers.forEach((header, index) => {
            header.style.cursor = 'pointer';
            header.title = 'Click to sort';
            header.addEventListener('click', function() {
                sortTable(table, index);
            });
        });
    });
}

function sortTable(table, columnIndex) {
    const rows = Array.from(table.querySelectorAll('tbody tr'));
    const isAsc = table.getAttribute('data-sort-asc') === 'true';
    
    rows.sort((a, b) => {
        const aValue = a.querySelectorAll('td')[columnIndex]?.textContent.trim();
        const bValue = b.querySelectorAll('td')[columnIndex]?.textContent.trim();
        
        if (!isNaN(aValue) && !isNaN(bValue)) {
            return isAsc ? parseFloat(aValue) - parseFloat(bValue) : parseFloat(bValue) - parseFloat(aValue);
        }
        
        return isAsc ? aValue.localeCompare(bValue) : bValue.localeCompare(aValue);
    });
    
    rows.forEach(row => table.querySelector('tbody').appendChild(row));
    table.setAttribute('data-sort-asc', !isAsc);
}

/* ========== Alert Messages ========== */
function showAlert(message, type = 'info') {
    const alert = document.createElement('div');
    alert.className = `alert alert-${type}`;
    alert.textContent = message;
    alert.style.position = 'fixed';
    alert.style.top = '100px';
    alert.style.right = '20px';
    alert.style.zIndex = '9999';
    alert.style.minWidth = '300px';
    alert.style.maxWidth = '500px';
    alert.style.animation = 'slideIn 0.3s ease-out';
    
    document.body.appendChild(alert);
    
    setTimeout(() => {
        alert.style.animation = 'slideOut 0.3s ease-out';
        setTimeout(() => alert.remove(), 300);
    }, 5000);
}

/* ========== Confirmation Dialog ========== */
function confirmAction(message) {
    return confirm(message || 'Are you sure?');
}

/* ========== Search and Filter ========== */
function filterTable(tableId, searchValue) {
    const table = document.getElementById(tableId);
    const rows = table.querySelectorAll('tbody tr');
    let visibleCount = 0;
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchValue.toLowerCase())) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });
    
    if (visibleCount === 0) {
        const emptyRow = document.createElement('tr');
        emptyRow.innerHTML = '<td colspan="100%" style="text-align: center;">No results found</td>';
        table.querySelector('tbody').appendChild(emptyRow);
    }
}

/* ========== Date Formatting ========== */
function formatDate(date) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(date).toLocaleDateString('en-US', options);
}

function formatTime(time) {
    const [hours, minutes] = time.split(':');
    const hour = parseInt(hours);
    const ampm = hour >= 12 ? 'PM' : 'AM';
    const displayHour = hour % 12 || 12;
    return `${displayHour}:${minutes} ${ampm}`;
}

/* ========== Error Handling ========== */
window.addEventListener('error', function(event) {
    console.error('Error:', event.error);
});

/* ========== Number Formatting ========== */
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

function formatNumber(number) {
    return new Intl.NumberFormat('en-IN').format(number);
}

/* ========== Local Storage Functions ========== */
const Storage = {
    set: function(key, value) {
        localStorage.setItem(key, JSON.stringify(value));
    },
    
    get: function(key) {
        const value = localStorage.getItem(key);
        return value ? JSON.parse(value) : null;
    },
    
    remove: function(key) {
        localStorage.removeItem(key);
    },
    
    clear: function() {
        localStorage.clear();
    }
};

/* ========== Export to CSV Function ========== */
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    let csv = [];
    
    // Get headers
    const headers = [];
    table.querySelectorAll('th').forEach(header => {
        headers.push(header.textContent.trim());
    });
    csv.push(headers.join(','));
    
    // Get rows
    table.querySelectorAll('tbody tr').forEach(row => {
        const cells = [];
        row.querySelectorAll('td').forEach(cell => {
            cells.push('"' + cell.textContent.trim().replace(/"/g, '""') + '"');
        });
        csv.push(cells.join(','));
    });
    
    // Create and download CSV file
    const csvContent = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv.join('\n'));
    const link = document.createElement('a');
    link.href = csvContent;
    link.download = filename || 'export.csv';
    link.click();
}

/* ========== Print Table Function ========== */
function printTable(tableId) {
    const table = document.getElementById(tableId);
    const printWindow = window.open('', '', 'width=800,height=600');
    printWindow.document.write('<html><head><title>Print</title>');
    printWindow.document.write('<link rel="stylesheet" href="../assets/css/style.css">');
    printWindow.document.write('</head><body>');
    printWindow.document.write(table.innerHTML);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    setTimeout(() => printWindow.print(), 250);
}

/* ========== Keyboard Shortcuts ========== */
document.addEventListener('keydown', function(event) {
    // Alt + S: Submit form
    if (event.altKey && event.key === 's') {
        const form = document.querySelector('form');
        if (form) {
            form.submit();
        }
    }
    
    // Alt + Q: Quit/Go back
    if (event.altKey && event.key === 'q') {
        window.history.back();
    }
});

/* ========== Auto-hide Alerts ========== */
window.addEventListener('load', function() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s ease-out';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

/* ========== Add Loading State to Buttons ========== */
document.addEventListener('submit', function(e) {
    const form = e.target;
    const submitBtn = form.querySelector('button[type="submit"]');
    
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.textContent = 'Processing...';
    }
});

/* ========== Utilities ========== */
const Utils = {
    isEmail: function(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    },
    
    isPhone: function(phone) {
        const phoneRegex = /^[\d\s\-\+\(\)]+$/;
        return phoneRegex.length >= 10 && phoneRegex.test(phone);
    },
    
    isEmpty: function(value) {
        return value === null || value === undefined || value.toString().trim() === '';
    },
    
    getUrlParam: function(param) {
        const params = new URLSearchParams(window.location.search);
        return params.get(param);
    },
    
    setUrlParam: function(param, value) {
        const url = new URL(window.location);
        url.searchParams.set(param, value);
        window.history.pushState({}, '', url);
    }
};
