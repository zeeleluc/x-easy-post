document.addEventListener('DOMContentLoaded', function() {
    try {

        const form = document.getElementById('posts-form');
        if (form) {
            const dynamicFormElements = document.getElementById('dynamic-form-elements');
            const body = document.body;
            const url = body.getAttribute('data-url');

            // Function to fetch and update the HTML content
            const fetchAndUpdateContent = async () => {
                try {
                    // Collect all form input values
                    const formData = new FormData(form);
                    const formValues = {};
                    formData.forEach((value, key) => {
                        formValues[key] = value;
                    });

                    const response = await fetch(url + '/load-dynamic-form-elements', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(formValues),
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok ' + response.statusText);
                    }

                    const html = await response.text();
                    dynamicFormElements.innerHTML = html;
                } catch (error) {
                    console.error('Fetch error: ', error);
                }
            };

            // Event listener for form elements
            form.addEventListener('change', (event) => {
                if (event.target.name === "project" || form.contains(event.target)) {
                    fetchAndUpdateContent();
                }
            });

            // Event listener for buttons with the name "project"
            const handleButtonClick = () => {
                fetchAndUpdateContent();
            };

            form.querySelectorAll('button[name="project"]').forEach(button => {
                button.addEventListener('click', handleButtonClick);
                button.addEventListener('touchend', handleButtonClick); // Add touchend event for iPhone
            });
        }

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
