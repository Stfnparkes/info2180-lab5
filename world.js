document.addEventListener('DOMContentLoaded', function() {
    const lookupButton = document.getElementById('lookup');
    const lookupCitiesButton = document.getElementById('lookupCities');
    const countryInput = document.getElementById('country');
    const resultDiv = document.getElementById('result');

    function handleLookup(lookupType) {
        const countryName = countryInput.value.trim();
        let url = `world.php?country=${encodeURIComponent(countryName)}`;


        if (lookupType === 'cities') {
            url += '&lookup=cities'; 
        }


        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.text(); 
            })
            .then(data => {
                resultDiv.innerHTML = data;
            })
            .catch(error => {
                resultDiv.innerHTML = `<p style="color: red;">Error: ${error.message}</p>`;
                console.error('Fetch error:', error);
            });
    }


    lookupButton.addEventListener('click', function() {
        handleLookup('country'); 
    });

    lookupCitiesButton.addEventListener('click', function() {
        handleLookup('cities'); 
    });
});