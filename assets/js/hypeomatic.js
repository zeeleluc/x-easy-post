document.addEventListener('DOMContentLoaded', function() {
    try {
        const form = document.getElementById('posts-form');
        if (form) {
            const dynamicFormElements = document.getElementById('dynamic-form-elements');
            const body = document.body;
            const url = body.getAttribute('data-url');
            const spinner = document.getElementById('spinner');

            // Function to fetch and update the HTML content
            const fetchAndUpdateContent = async () => {
                try {
                    // Show the spinner
                    spinner.style.display = 'block';
                    dynamicFormElements.style.display = 'none';

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
                } finally {
                    // Hide the spinner after a delay of 500ms
                    setTimeout(() => {
                        spinner.style.display = 'none';
                        dynamicFormElements.style.display = 'block';
                    }, 500);
                }
            };

            // Event listener for form elements
            form.addEventListener('change', (event) => {
                fetchAndUpdateContent();
            });

            form.querySelectorAll('button[name="project"]').forEach(button => {
                button.addEventListener('click', handleButtonClick);
                button.addEventListener('touchend', handleButtonClick); // Add touchend event for iPhone
            });

            fetchAndUpdateContent();
        }

    } catch (error) {
        console.error('An error occurred:', error);
    }
});
