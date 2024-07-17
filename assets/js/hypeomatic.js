document.addEventListener('DOMContentLoaded', function() {
    try {
        const form = document.getElementById('posts-form');
        if (form) {
            const dynamicFormElements = document.getElementById('dynamic-form-elements');
            const body = document.body;
            const url = body.getAttribute('data-url');
            const spinner = document.getElementById('spinner');
            const projectSelect = document.querySelector('select#project');

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

                    // Add event listener for dynamically added select#image
                    const imageSelect = document.querySelector('select#image');
                    if (imageSelect) {
                        imageSelect.addEventListener('change', fetchAndUpdateContent);
                    }

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

            projectSelect.addEventListener('change', fetchAndUpdateContent);

            // Initial fetch and update content
            fetchAndUpdateContent();

            // Form submit event listener
            form.addEventListener('submit', function(event) {
                // Show the spinner
                spinner.style.display = 'block';

                // Disable all form elements
                Array.from(form.elements).forEach(element => {
                    element.setAttribute('readOnly', true);
                    element.classList.add('readonly');
                });

                // Hide the submit button
                const submitButton = form.querySelector('button[type="submit"]');
                if (submitButton) {
                    submitButton.style.display = 'none';
                }

                const submitBePatient_text = document.getElementById('submit-be-patient-text');
                submitBePatient_text.style.display = 'block';
            });
        }

    } catch (error) {
        console.error('An error occurred:', error);
    }
});
