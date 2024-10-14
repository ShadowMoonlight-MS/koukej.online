document.getElementById('filesearch').addEventListener('keydown', async (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        const query = document.querySelector('input[type="search"]').value;
        const normalizedQuery = removeDiacritics(query);
        await searchFiles(normalizedQuery);
        window.scrollBy(0, 600);

        // Kontrola pro PHP session, tuto část zpracuješ v HTML souboru s PHP
        if (window.isLoggedIn) {
            const formData = new FormData();
            formData.append('email', window.userEmail); // PHP proměnná z session
            formData.append('query', normalizedQuery);

            // Odeslání dat na server pomocí fetch API
            fetch('phpfiles/historie_hledani_zapis.php', {
                method: 'POST',
                body: formData
            }).then(response => response.text())
            .then(result => {
                console.log('Úspěšně zapsáno do historie:', result);
            }).catch(error => {
                console.error('Chyba při zápisu:', error);
            });
        }
    }
});

document.getElementById('najit_button').addEventListener('click', async (event) => {
    event.preventDefault();
    const query = document.querySelector('input[type="search"]').value;
    const normalizedQuery = removeDiacritics(query);
    await searchFiles(normalizedQuery);
    window.scrollBy(0, 600);

    if (window.isLoggedIn) {
        const formData = new FormData();
        formData.append('email', window.userEmail);
        formData.append('query', normalizedQuery);

        fetch('phpfiles/historie_hledani_zapis.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
        .then(result => {
            console.log('Úspěšně zapsáno do historie:', result);
        }).catch(error => {
            console.error('Chyba při zápisu:', error);
        });
    }
});

let typingTimer;
const typingDelay = 100;

document.getElementById('filesearch').addEventListener('input', () => {
    clearTimeout(typingTimer);
    const query = document.getElementById('filesearch').value;
    if (query.length > 1) {
        typingTimer = setTimeout(async () => {
            const normalizedQuery = removeDiacritics(query);
            await showSuggestions(normalizedQuery);
        }, typingDelay);
    }
});

async function showSuggestions(query) {
    try {
        const response = await fetch(`suggestions.php?query=${encodeURIComponent(query)}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const suggestions = await response.json();
        const dataList = document.getElementById('suggestions');
        dataList.innerHTML = ''; // Clear previous suggestions

        suggestions.forEach(suggestion => {
            const option = document.createElement('option');
            option.value = suggestion;
            dataList.appendChild(option);
        });
    } catch (error) {
        console.error('Error fetching suggestions:', error);
    }
}

async function searchFiles(query) {
  document.getElementById('loadingSpinner').style.display = 'block';

    try {
        console.log(`Searching for: ${query}`);
        const response = await fetch(`hledac.php?search=${encodeURIComponent(query)}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const files = await response.json();
        const container = document.getElementById('fileContainer');
        document.getElementById('loadingSpinner').style.display = 'none';

        if (!container) {
            throw new Error("Container not found");
        }

        container.innerHTML = '';  // Clear previous results
        console.log(files);

        if (!Array.isArray(files) || files.length === 0) {
            container.innerHTML = `
                <div></div>
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 100%;">
                  <p class="text-center">Nic jsem nenašel 😔 zkus to vyhledat pod jiným názvem (např. Supernatural - Lovci duchu)</p>
                </div>
                <div class="col-12 d-flex justify-content-center align-items-center" style="height: 100%;">
                  <button type="button" class="btn btn-primary" id="checkLimitButton">
                    Nenašel jste to ani pod jiným názvem? Kliknutím na toto tlačítko pošleš nám název videa, který hledáš: <strong>${query}</strong> a my se podíváme, jestli ho v databázi nenajdeme 😊
                  </button>
                </div>
            `;

            const button = document.getElementById('checkLimitButton');
            button.addEventListener('click', function() {
                submitQuery(query);
            });
            return;
        }

        // Dále zpracování souborů atd. ...
    } catch (error) {
        console.error('Error fetching files:', error);
    }
}

function removeDiacritics(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

document.addEventListener('DOMContentLoaded', () => {
    searchFiles('');
});
