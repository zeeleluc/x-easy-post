document.addEventListener('DOMContentLoaded', function() {
    try {
        // Handle image selection change
        const imageSelect = document.getElementById('image');
        if (imageSelect) {
            imageSelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const typeOptionsCryptoPunks = document.querySelectorAll('select#type option.type-crypto-punk');
                const typeOptionsLooneyLuca = document.querySelectorAll('select#type option.type-looneyluca');
                const typeOptionsBaseAliens = document.querySelectorAll('select#type option.type-basealiens');

                if (selectedOption.classList.contains('option-crypto-punk')) {
                    typeOptionsCryptoPunks.forEach(option => {
                        option.classList.remove('hide');
                        option.removeAttribute('disabled');
                    });
                    typeOptionsLooneyLuca.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                    typeOptionsBaseAliens.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                } else if (selectedOption.classList.contains('option-looneyluca')) {
                    typeOptionsLooneyLuca.forEach(option => {
                        option.classList.remove('hide');
                        option.removeAttribute('disabled');
                    });
                    typeOptionsCryptoPunks.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                    typeOptionsBaseAliens.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                } else if (selectedOption.classList.contains('option-basealiens')) {
                    typeOptionsBaseAliens.forEach(option => {
                        option.classList.remove('hide');
                        option.removeAttribute('disabled');
                    });
                    typeOptionsLooneyLuca.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                    typeOptionsCryptoPunks.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                } else {
                    typeOptionsCryptoPunks.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                    typeOptionsLooneyLuca.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                    typeOptionsBaseAliens.forEach(option => {
                        option.classList.add('hide');
                        option.setAttribute('disabled', 'disabled');
                    });
                }
            });
        }

        // Show the alert and remove it after 3.5 seconds
        setTimeout(function() {
            let alertContainer = document.querySelector('.alert-container');
            if (alertContainer) {
                alertContainer.remove();
            }
        }, 3500);

        // Handle tab navigation via URL hash
        var hash = window.location.hash;
        if (hash) {
            var tabTrigger = document.querySelector('button[data-bs-target="' + hash + '"]');
            if (tabTrigger) {
                var tab = new bootstrap.Tab(tabTrigger);
                tab.show();
            }
        }

        // Update URL hash when a tab is shown
        document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(function(tabTrigger) {
            tabTrigger.addEventListener('shown.bs.tab', function(e) {
                history.pushState(null, null, e.target.getAttribute('data-bs-target'));
            });
        });

        // Check login status
        function checkLoginStatus() {
            const xhr = new XMLHttpRequest();
            xhr.open('GET', '/check-login', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success === false) {
                        window.location.href = '/login';
                    }
                }
            };
            xhr.send();
        }

        // Form element focus and change handling
        const form = document.querySelector('form#posts-form');
        if (form) {
            const formElements = form.querySelectorAll('textarea, select, input');
            formElements.forEach(element => {
                element.addEventListener('focus', function() {
                    checkLoginStatus();
                });
                element.addEventListener('change', function() {
                    checkLoginStatus();
                });
            });

            // Disable submit button after form submission
            form.addEventListener('submit', function(event) {
                const submitButton = form.querySelector('button[type="submit"], input[type="submit"]');
                if (submitButton) {
                    submitButton.disabled = true;
                }
            });
        }

    } catch (error) {
        console.error('An error occurred:', error);
    }
});
