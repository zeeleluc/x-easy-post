document.getElementById('image').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const typeOptions = document.querySelectorAll('select#type option.type-crypto-punk');

    if (selectedOption.classList.contains('option-crypto-punk')) {
        typeOptions.forEach(option => {
            option.classList.remove('hide');
            option.removeAttribute('disabled')
        });
    } else {
        typeOptions.forEach(option => {
            option.classList.add('hide');
            option.setAttribute('disabled', 'disabled')
        });
    }
});
