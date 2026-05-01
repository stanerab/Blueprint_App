/**
 * Blueprint Clinical App - Main JavaScript
 * Fully responsive with enhanced UX for clinical settings
 */

(function() {
    'use strict';

    // ===== DOM ELEMENTS =====
    const body = document.body;
    
    // ===== INITIALIZATION =====
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Blueprint App initialized');
        initializeApp();
    });

    function initializeApp() {
        setupFormValidation();
        setupInitialsInputs();
        setupConfirmationDialogs();
        setupResponsiveTables();
        setupAutoDismissAlerts();
        setupDateInputs();
        setupTooltips();
        setupMobileMenu();
        setupLoadingStates();
        setupKeyboardShortcuts();
    }

    // ===== FORM VALIDATION =====
    function setupFormValidation() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                if (!validateForm(this)) {
                    e.preventDefault();
                }
            });

            // Real-time validation
            const inputs = form.querySelectorAll('input[required], select[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    validateField(this);
                });
            });
        });
    }

    function validateForm(form) {
        let isValid = true;
        const requiredFields = form.querySelectorAll('[required]');
        
        requiredFields.forEach(field => {
            if (!validateField(field)) {
                isValid = false;
            }
        });

        return isValid;
    }

    function validateField(field) {
        const value = field.value.trim();
        const errorElement = field.nextElementSibling?.classList.contains('field-error') 
            ? field.nextElementSibling 
            : createErrorElement(field);

        // Remove existing error
        if (field.classList.contains('error')) {
            field.classList.remove('error');
            if (errorElement) errorElement.remove();
        }

        // Check if empty
        if (!value) {
            showFieldError(field, 'This field is required');
            return false;
        }

        // Specific validations
        if (field.type === 'email' && !isValidEmail(value)) {
            showFieldError(field, 'Please enter a valid email address');
            return false;
        }

        if (field.name === 'initials' && value.length > 3) {
            showFieldError(field, 'Initials must be 3 characters or less');
            return false;
        }

        if (field.name === 'password' && value.length < 6) {
            showFieldError(field, 'Password must be at least 6 characters');
            return false;
        }

        return true;
    }

    function createErrorElement(field) {
        const error = document.createElement('div');
        error.className = 'field-error';
        error.style.color = '#e74c3c';
        error.style.fontSize = '12px';
        error.style.marginTop = '5px';
        field.parentNode.insertBefore(error, field.nextSibling);
        return error;
    }

    function showFieldError(field, message) {
        field.classList.add('error');
        field.style.borderColor = '#e74c3c';
        
        const error = field.nextElementSibling?.classList.contains('field-error') 
            ? field.nextElementSibling 
            : createErrorElement(field);
        
        error.textContent = message;
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    // ===== INITIALS INPUT HANDLING =====
    function setupInitialsInputs() {
        const initialsInputs = document.querySelectorAll('input[name="initials"]');
        
        initialsInputs.forEach(input => {
            input.addEventListener('input', function() {
                // Convert to uppercase and remove non-letters
                this.value = this.value.toUpperCase().replace(/[^A-Z]/g, '');
                
                // Limit to 3 characters
                if (this.value.length > 3) {
                    this.value = this.value.slice(0, 3);
                }
            });

            // Visual feedback
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });

            input.addEventListener('blur', function() {
                this.parentElement.classList.remove('focused');
            });
        });
    }

    // ===== CONFIRMATION DIALOGS =====
    function setupConfirmationDialogs() {
        // Archive buttons
        document.querySelectorAll('.btn-archive').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to archive this item? You can restore it later.')) {
                    e.preventDefault();
                }
            });
        });

        // Delete buttons
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function(e) {
                if (!confirm('⚠️ WARNING: This action cannot be undone. Are you sure you want to permanently delete this item?')) {
                    e.preventDefault();
                }
            });
        });

        // Logout button
        const logoutBtn = document.querySelector('.btn-logout');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to logout?')) {
                    e.preventDefault();
                }
            });
        }
    }

    // ===== RESPONSIVE TABLES =====
    function setupResponsiveTables() {
        const tables = document.querySelectorAll('.data-table');
        
        tables.forEach(table => {
            const wrapper = document.createElement('div');
            wrapper.className = 'table-responsive';
            table.parentNode.insertBefore(wrapper, table);
            wrapper.appendChild(table);

            // Add horizontal scroll indicator on mobile
            if (window.innerWidth <= 768) {
                addScrollIndicator(wrapper);
            }
        });

        // Handle resize
        let resizeTimeout;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimeout);
            resizeTimeout = setTimeout(function() {
                updateResponsiveTables();
            }, 250);
        });
    }

    function addScrollIndicator(wrapper) {
        if (wrapper.querySelector('.scroll-indicator')) return;
        
        const indicator = document.createElement('div');
        indicator.className = 'scroll-indicator';
        indicator.innerHTML = '← Swipe to scroll more →';
        indicator.style.cssText = `
            text-align: center;
            padding: 10px;
            background: var(--light-color);
            color: var(--primary-color);
            font-size: 12px;
            border-radius: 0 0 8px 8px;
            display: none;
        `;
        
        wrapper.appendChild(indicator);

        // Show indicator if content overflows
        if (wrapper.scrollWidth > wrapper.clientWidth) {
            indicator.style.display = 'block';
            
            // Hide after scrolling
            wrapper.addEventListener('scroll', function() {
                indicator.style.opacity = '0.5';
            });
        }
    }

    function updateResponsiveTables() {
        const wrappers = document.querySelectorAll('.table-responsive');
        wrappers.forEach(wrapper => {
            const indicator = wrapper.querySelector('.scroll-indicator');
            if (indicator) {
                if (wrapper.scrollWidth > wrapper.clientWidth) {
                    indicator.style.display = 'block';
                } else {
                    indicator.style.display = 'none';
                }
            }
        });
    }

    // ===== AUTO-DISMISS ALERTS =====
    function setupAutoDismissAlerts() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        
        alerts.forEach(alert => {
            // Add close button
            const closeBtn = document.createElement('button');
            closeBtn.innerHTML = '×';
            closeBtn.style.cssText = `
                float: right;
                background: none;
                border: none;
                font-size: 20px;
                cursor: pointer;
                padding: 0 5px;
            `;
            closeBtn.setAttribute('aria-label', 'Close');
            alert.appendChild(closeBtn);

            // Close on button click
            closeBtn.addEventListener('click', function() {
                dismissAlert(alert);
            });

            // Auto-dismiss after 5 seconds
            setTimeout(() => {
                if (alert.parentNode) {
                    dismissAlert(alert);
                }
            }, 5000);
        });
    }

    function dismissAlert(alert) {
        alert.style.transition = 'opacity 0.3s ease';
        alert.style.opacity = '0';
        setTimeout(() => {
            if (alert.parentNode) {
                alert.remove();
            }
        }, 300);
    }

    // ===== DATE INPUT ENHANCEMENTS =====
    function setupDateInputs() {
        const dateInputs = document.querySelectorAll('input[type="date"]');
        
        dateInputs.forEach(input => {
            // Set max date to today for discharge date
            if (input.name === 'discharge_date') {
                input.max = new Date().toISOString().split('T')[0];
            }

            // Admission date should not be in future
            if (input.name === 'admission_date') {
                input.max = new Date().toISOString().split('T')[0];
            }

            // Add placeholder for empty dates
            if (!input.value) {
                addDatePlaceholder(input);
            }
        });
    }

    function addDatePlaceholder(input) {
        const placeholder = document.createElement('span');
        placeholder.className = 'date-placeholder';
        placeholder.textContent = 'Select date';
        placeholder.style.cssText = `
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            pointer-events: none;
        `;
        
        input.style.position = 'relative';
        input.parentNode.style.position = 'relative';
        input.parentNode.appendChild(placeholder);

        input.addEventListener('focus', () => placeholder.remove());
        input.addEventListener('blur', () => {
            if (!input.value) addDatePlaceholder(input);
        });
    }

    // ===== TOOLTIPS =====
    function setupTooltips() {
        const tooltipElements = document.querySelectorAll('[title], .notes-cell');
        
        tooltipElements.forEach(element => {
            element.addEventListener('mouseenter', function(e) {
                const title = this.getAttribute('title') || this.textContent;
                if (!title) return;

                const tooltip = document.createElement('div');
                tooltip.className = 'custom-tooltip';
                tooltip.textContent = title;
                tooltip.style.cssText = `
                    position: fixed;
                    background: var(--primary-color);
                    color: white;
                    padding: 8px 12px;
                    border-radius: 4px;
                    font-size: 12px;
                    z-index: 1000;
                    max-width: 300px;
                    word-wrap: break-word;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.2);
                `;

                document.body.appendChild(tooltip);

                const rect = this.getBoundingClientRect();
                tooltip.style.left = rect.left + 'px';
                tooltip.style.top = (rect.top - tooltip.offsetHeight - 5) + 'px';

                this.addEventListener('mouseleave', function() {
                    tooltip.remove();
                }, { once: true });
            });
        });
    }

    // ===== MOBILE MENU =====
    function setupMobileMenu() {
        const topBar = document.querySelector('.top-bar');
        if (!topBar) return;

        // Create mobile menu button if needed
        if (window.innerWidth <= 768) {
            const menuBtn = document.createElement('button');
            menuBtn.className = 'mobile-menu-btn';
            menuBtn.innerHTML = '☰';
            menuBtn.style.cssText = `
                background: none;
                border: none;
                color: white;
                font-size: 24px;
                cursor: pointer;
                display: none;
            `;

            const container = topBar.querySelector('.container');
            container.insertBefore(menuBtn, container.firstChild);

            menuBtn.addEventListener('click', function() {
                const userInfo = container.querySelector('.user-info');
                userInfo.classList.toggle('show');
            });
        }
    }

    // ===== LOADING STATES =====
    function setupLoadingStates() {
        const forms = document.querySelectorAll('form');
        
        forms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('[type="submit"]');
                if (submitBtn) {
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="loading-spinner"></span> Processing...';
                    
                    // Re-enable after timeout (in case of error)
                    setTimeout(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    }, 5000);
                }
            });
        });
    }

    // ===== KEYBOARD SHORTCUTS =====
    function setupKeyboardShortcuts() {
        document.addEventListener('keydown', function(e) {
            // Ctrl/Cmd + L to focus login username
            if ((e.ctrlKey || e.metaKey) && e.key === 'l') {
                e.preventDefault();
                const usernameInput = document.querySelector('#username');
                if (usernameInput) usernameInput.focus();
            }

            // Ctrl/Cmd + S to submit form
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                const form = document.querySelector('form');
                if (form) form.submit();
            }

            // Escape to close alerts
            if (e.key === 'Escape') {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(alert => dismissAlert(alert));
            }
        });
    }

    // ===== SEARCH FUNCTIONALITY =====
    window.searchTable = function(inputId, tableId) {
        const input = document.getElementById(inputId);
        if (!input) return;

        const filter = input.value.toUpperCase();
        const table = document.getElementById(tableId);
        if (!table) return;

        const tr = table.getElementsByTagName('tr');
        
        for (let i = 1; i < tr.length; i++) {
            const td = tr[i].getElementsByTagName('td');
            let found = false;
            
            for (let j = 0; j < td.length; j++) {
                if (td[j]) {
                    const txtValue = td[j].textContent || td[j].innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        found = true;
                        break;
                    }
                }
            }
            
            tr[i].style.display = found ? '' : 'none';
        }

        // Show/hide no results message
        const tbody = table.querySelector('tbody');
        const visibleRows = Array.from(tr).slice(1).filter(row => row.style.display !== 'none');
        
        let noResults = tbody.querySelector('.no-search-results');
        if (visibleRows.length === 0) {
            if (!noResults) {
                noResults = document.createElement('tr');
                noResults.className = 'no-search-results';
                noResults.innerHTML = '<td colspan="10" class="no-data">No matching records found</td>';
                tbody.appendChild(noResults);
            }
        } else if (noResults) {
            noResults.remove();
        }
    };

    // ===== EXPORT TABLE TO CSV =====
    window.exportToCSV = function(tableId, filename) {
        const table = document.getElementById(tableId);
        if (!table) return;

        const rows = table.querySelectorAll('tr');
        const csv = [];
        
        rows.forEach(row => {
            const cols = row.querySelectorAll('td, th');
            const rowData = [];
            cols.forEach(col => {
                let text = col.textContent.trim();
                // Remove actions column from export
                if (!col.classList.contains('actions')) {
                    rowData.push('"' + text.replace(/"/g, '""') + '"');
                }
            });
            csv.push(rowData.join(','));
        });

        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename || 'export.csv';
        a.click();
        window.URL.revokeObjectURL(url);
    };
})();
