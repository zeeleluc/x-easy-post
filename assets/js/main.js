document.getElementById('image').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const typeOptionsCryptoPunks = document.querySelectorAll('select#type option.type-crypto-punk');
    const typeOptionsLooneyLuca = document.querySelectorAll('select#type option.type-looneyluca');

    if (selectedOption.classList.contains('option-crypto-punk')) {
        typeOptionsCryptoPunks.forEach(option => {
            option.classList.remove('hide');
            option.removeAttribute('disabled')
        });
    } else if (selectedOption.classList.contains('option-looneyluca')) {
        typeOptionsLooneyLuca.forEach(option => {
            option.classList.remove('hide');
            option.removeAttribute('disabled')
        });
    } else {
        typeOptionsCryptoPunks.forEach(option => {
            option.classList.add('hide');
            option.setAttribute('disabled', 'disabled')
        });
        typeOptionsLooneyLuca.forEach(option => {
            option.classList.add('hide');
            option.setAttribute('disabled', 'disabled')
        });
    }
});
