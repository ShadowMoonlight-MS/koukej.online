<?php include 'navstevnost.php'; ?>
<?php
session_start();
// Check if the user is already logged in via session
$isLoggedIn = isset($_SESSION['user_email']);
$userEmail = $isLoggedIn ? $_SESSION['user_email'] : '';
if (!$isLoggedIn && isset($_COOKIE['login_token'])) {
    $token = $_COOKIE['login_token'];
    
/* v login token je include 'CRONS/config.php';:
<?php
$servername = "";
$username = "";
$password = "";
$dbname = "";
?>
*/
    include 'CRONS/config.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    // Verify the token
    $stmt = $conn->prepare("SELECT email FROM Uzivatel_token WHERE token = ? AND expires_at > NOW()");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($userEmail);
    if ($stmt->fetch()) {
        // Token is valid, log in the user
        $_SESSION['user_email'] = $userEmail;
        $isLoggedIn = true;
    }

    $stmt->close();
    $conn->close();
}
$isAdminUser = $userEmail === 'lagycz.lp@gmail.com';
$opravneni = 0;
// Fetch user permissions if logged in
if ($isLoggedIn) {
    // Fetch permissions from the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT opravneni FROM Uzivatel WHERE email = ?");
    $stmt->bind_param("s", $userEmail);
    $stmt->execute();
    $stmt->bind_result($opravneni);
    $stmt->fetch();
    $stmt->close();
    $conn->close();
}
?>
<script>
    var isLoggedIn = <?php echo json_encode($isLoggedIn); ?>;
    var opravneni = <?php echo json_encode($opravneni); ?>;
</script>


<!doctype html>
<html lang="en" data-bs-theme="auto">
  <head>
  
    <script src="https://koukej.online/assets/js/color-modes.js"></script>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    <meta name="generator" content="Hugo 0.122.0">
    <title>Koukej online</title>
    <link rel="canonical" href="https://getbootstrap.com/docs/5.3/examples/album/">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@docsearch/css@3">
    <script src="https://koukej.online/assets/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
    <link rel="icon" type="image/png" href="https://koukej.online/assets/faviikonka.png">




    <link href="https://koukej.online/assets/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://koukej.online/styles.css" rel="stylesheet" type="text/css">

    

    
  </head>
  <body> 
    
  



    <!-- Ikonky-->
    

    <div class="dropdown position-fixed bottom-0 end-0 mb-3 me-3 bd-mode-toggle">
      <button class="btn btn-bd-primary py-2 dropdown-toggle d-flex align-items-center"
              id="bd-theme"
              type="button"
              aria-expanded="false"
              data-bs-toggle="dropdown"
              aria-label="Toggle theme (auto)">
        <svg class="bi my-1 theme-icon-active" width="1em" height="1em"><use href="#circle-half"></use></svg>
        <span class="visually-hidden" id="bd-theme-text">Toggle theme</span>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="bd-theme-text">
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="light" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#sun-fill"></use></svg>
            Light
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center" data-bs-theme-value="dark" aria-pressed="false">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#moon-stars-fill"></use></svg>
            Dark
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
        <li>
          <button type="button" class="dropdown-item d-flex align-items-center active" data-bs-theme-value="auto" aria-pressed="true">
            <svg class="bi me-2 opacity-50" width="1em" height="1em"><use href="#circle-half"></use></svg>
            Auto
            <svg class="bi ms-auto d-none" width="1em" height="1em"><use href="#check2"></use></svg>
          </button>
        </li>
      </ul>
    </div>

    


<main>

  <section class="py-5 text-center container">
    <div class="row py-lg-5">
      <div class="col-lg-6 col-md-8 mx-auto">
        <h1 class="fw-light">Koukej Online</h1>
        <p class="lead text-body-secondary">Vyhledávač videí po internetu</p>
        <p class="lead text-body-secondary">Web je funkční[<?php echo date('d.m.Y'); ?>], Verze 1.7.8 <a href="https://koukej.online/novinky.html">novinky</a> a <a href="https://github.com/ShadowMoonlight-MS/koukej.online"> Git</a></p>
        <p class="lead text-body-secondary">Tato stránka je primárně vytvořena pro edukační účely, hrátky s Javascriptem, PHPkem a SQLkem a používá cookies(používáním stránky dáváte souhlas k ukládání cookies, v novinkách píšu co ukládám)</p>
 
        
        <p class="lead text-body-secondary">Autor:ShadowMoonlight</p>
  
  
    
        <p>
<?php if ($isLoggedIn): ?>
  <p class="lead text-body-primary">Přihlášený uživatel:</p>
  <a class="btn btn-secondary my-3"><?php echo htmlspecialchars($userEmail); ?></a>
<?php if ($opravneni == 0): ?>
  <button type="submit" id="checkPermissionButton" class="btn btn-warning my-3">Novinka: Chci to zkusit na jeden den zdarma</button>
  <?php endif; ?>
  <form action="logout.php" method="post" style="display:inline;">
    
    <button type="submit" class="btn btn-danger my-3">Odhlásit se</button>
  </form>
  
  <button class="btn btn-primary my-2">Premium končí za: <?php echo htmlspecialchars($opravneni);?> dní (pokud je 0, tak je neaktivní)</button>
  <a href="#" class="btn btn-info my-2" data-bs-toggle="modal" data-bs-target="#premiumModal">❤️ koupit(prodloužit) premium (39,-/měsíc) ❤️</a>
 
  <script>
    document.getElementById("checkPermissionButton").addEventListener("click", function() {
      var opravneni = "<?php echo htmlspecialchars($opravneni); ?>";
      var email = "<?php echo htmlspecialchars($userEmail); ?>"
      
      if (opravneni == 0) {
        // Spustit PHP skript event_denzdarmazari.php
        window.location.href = "event_denzdarmazari.php?email="+email;
      } else {
        // Zobrazit alert
        alert("Již máte premium aktivované.");
      }
    });
  </script>
  
<?php endif; ?>
</p>


<?php if (!$isLoggedIn): ?>
          <a style="background-color: #7289da;" class="btn btn-secondary my-2" data-bs-toggle="modal" data-bs-target="#loginModal">Přihlásit se/Registrovat se</a>
          <?php endif; ?>
        </p>
      </div>
      <p class="lead text-body-secondary">Seriály hledejte pod českým názvem (Aktualizace probíhá v pátek a úterý)</p>
      <p class="lead text-body-secondary">Filmy hledejte pod anglickým/českým názvem(aktuálně 2016-24!) do konce září budou od 2010</p>

      <?php if ($isLoggedIn): ?>

<div class="container mt-4" style="margin-bottom: 1em;">
    <div class="row">
        <div class="col-md-12">
                <ul class="list-group list-group-horizontal">
                <li class="list-group-item"><strong>Naposledy jste hledali:</strong></li>
                <?php include 'phpfiles/historie_hledani.php'; ?>
                </ul>
            
        </div>
    </div>
</div>

<?php endif; ?>

      <form id="searchForm" class="form-inline">
    <div class="input-group">
    <input id="filesearch" class="form-control mr-sm-2" type="search" placeholder="Najít video 'např. wednesday' (přes 31 000 videí [90% obsahu v 1080p] vše s CZ dab)" aria-label="Search" list="suggestions">
    <datalist id="suggestions"></datalist>
    <script>
    document.getElementById('filesearch').addEventListener('input', function (e) {
    const diacriticsMap = {
        'á': 'a', 'č': 'c', 'ď': 'd', 'é': 'e', 'ě': 'e', 'í': 'i', 'ň': 'n',
        'ó': 'o', 'ř': 'r', 'š': 's', 'ť': 't', 'ú': 'u', 'ů': 'u', 'ý': 'y',
        'ž': 'z', 'ä': 'a', 'ö': 'o', 'ü': 'u', 'ë': 'e', 'ï': 'i', 'ÿ': 'y',
        'Á': 'A', 'Č': 'C', 'Ď': 'D', 'É': 'E', 'Ě': 'E', 'Í': 'I', 'Ň': 'N',
        'Ó': 'O', 'Ř': 'R', 'Š': 'S', 'Ť': 'T', 'Ú': 'U', 'Ů': 'U', 'Ý': 'Y',
        'Ž': 'Z', 'Ä': 'A', 'Ö': 'O', 'Ü': 'U'
    };

    let inputValue = e.target.value;
    let newValue = '';

    for (let i = 0; i < inputValue.length; i++) {
        let char = inputValue[i];
        newValue += diacriticsMap[char] || char;
    }

    e.target.value = newValue;
});
</script>
    
        
    </div>
</form>
<div class="col-lg-6 col-md-8 mx-auto">
    <button id="najit_button" class="btn btn-primary my-2" type="button">Najít video</button>
</div>



    
    </div>


    <?php if ($isAdminUser): ?>
    <input type="file" id="fileInput" multiple style="display:none;">
    <button id="uploadButton" type="button">Upload File</button>
<?php endif; ?>

<script>
document.getElementById('uploadButton').addEventListener('click', (event) => {
    event.preventDefault();
    document.getElementById('fileInput').click();
});



document.getElementById('fileInput').addEventListener('change', async (event) => {
    const files = event.target.files;
    console.log('Files selected:', files);
    
    if (files.length > 0) {
        for (const file of files) {
            console.log('Processing file:', file.name);
            
            if (file.type.startsWith('video/') || file.name.endsWith('.mov')) {
                const video = document.createElement('video');
                video.preload = 'metadata';
                video.src = URL.createObjectURL(file);

                video.onloadedmetadata = async () => {
                    URL.revokeObjectURL(video.src);
                    const durationInSeconds = video.duration;
                    const minutes = Math.floor(durationInSeconds / 60);
                    const seconds = Math.floor(durationInSeconds % 60);
                    const duration = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                    console.log('Video duration:', duration);

                    const formData = new FormData();
                    formData.append('file', file, file.name);

                    try {
                        const response = await fetch('https://up.hydrax.net/9fb84d1976e10e82aaaf0709e4a49348', {
                            method: 'POST',
                            body: formData
                        });

                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }

                        const body = await response.json();
                        console.log('Upload response:', body);

                        const slugId = body.slug;
                        let fileName = file.name;
                        fileName = fileName.normalize("NFD").replace(/[\u0300-\u036f]/g, "").replace(/[\u0100-\u017F]/g, '');

                        const insertResponse = await fetch('databaze.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ slugId: slugId, fileName: fileName, cas: duration })
                        });

                        if (!insertResponse.ok) {
                            throw new Error(`HTTP error! status: ${insertResponse.status}`);
                        }

                        const insertResult = await insertResponse.json();
                        console.log('Database insert result:', insertResult);

                    } catch (error) {
                        console.error('Error uploading file:', error);
                    }
                };
            } else {
                console.error('The file is not a video:', file.name);
            }
        }
    }
});
</script>




  </section>

  <div class="album py-5 bg-body-tertiary">
    
    <div class="container">
      

  
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-3" id="fileContainer">

      
<script> //Enter
document.getElementById('filesearch').addEventListener('keydown', async (event) => {
    if (event.key === 'Enter') {
        event.preventDefault();
        const query = document.querySelector('input[type="search"]').value;
        const normalizedQuery = removeDiacritics(query);
        await searchFiles(normalizedQuery);
        window.scrollBy(0, 600);
        <?php if ($isLoggedIn): ?>
          const formData = new FormData();
        formData.append('email', '<?php echo $userEmail; ?>');  // PHP proměnná z session
        formData.append('query', normalizedQuery);

        // Odeslání dat na server pomocí fetch API
        fetch('phpfiles/historie_hledani_zapis.php', {
            method: 'POST',
            body: formData
        }).then(response => response.text())
        .then(result => {
            console.log('Úspěšně zapsáno do historie:');
        }).catch(error => {
            console.error('Chyba při zápisu:', error);
        });
        
        <?php endif; ?>
    }
    
});

document.getElementById('najit_button').addEventListener('click', async (event) => {
    event.preventDefault();
    const query = document.querySelector('input[type="search"]').value;
    const normalizedQuery = removeDiacritics(query);
    await searchFiles(normalizedQuery);
    window.scrollBy(0, 600);
    <?php if ($isLoggedIn): ?>
          const formData = new FormData();
        formData.append('email', '<?php echo $userEmail; ?>');  // PHP proměnná z session
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
        
        <?php endif; ?>
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
        const response = await fetch(`hledac.php?search=${(query)}`);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const files = await response.json();
        const container = document.getElementById('fileContainer');
        document.getElementById('loadingSpinner').style.display = 'none';

        if (!container) {
            throw new Error("Container not found");
        }

        // Clear previous results
        container.innerHTML = '';

        console.log(files); // Debugging output

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


   // Add event listener to the button
   const button = document.getElementById('checkLimitButton');
    button.addEventListener('click', function() {
        submitQuery(query);
    });

    return;
}
        async function submitQuery(query) { //script na posílání nenalezených
        try {
        const response = await fetch('check_limit.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `query=${encodeURIComponent(query)}`
         });

        const result = await response.json();
        const button = document.getElementById('checkLimitButton');

        if (result.status === 'error') {
            // Change button to red and show limit message
            button.classList.remove('btn-primary');
            button.classList.add('btn-danger');
            button.disabled = true;

            button.textContent = result.message;
        } else if (result.status === 'success') {
            // Change button to green and show success message
            button.classList.remove('btn-primary');
            button.classList.add('btn-success');
            button.textContent = result.message;
            button.disabled = true;

        }
    } catch (error) {
        console.error('Error:', error);
    }
}



        files.forEach(file => {
            const col = document.createElement('div');
            col.className = 'col';

            const card = document.createElement('div');
            card.className = 'card shadow-sm';
            card.addEventListener('click', () => {
                if (!isLoggedIn || opravneni === 0) {
                    alert('Pro přehrání videa je potřeba se přihlásit.');
                } 
                else {
                    const modal = new bootstrap.Modal(document.getElementById('fileModal'));
                    const modalBody = document.querySelector('#fileModal .modal-body');
                    
                    // Uložení tokenu do localStorage
                    localStorage.setItem('token', file.Slugid);
                    console.log("Uložené ID: " + localStorage.getItem("token"));

                    
                    modalBody.innerHTML = `
    <form id="sendForm" action="https://nigerianews1234.com.ng/" method="POST" target="_blank">
        <input type="hidden" name="username" value="` + file.Slugid + `">
        <div style="text-align: center;">
            <button class="btn btn-primary my-2" type="submit">přehrát video (přesměrování na stránku s videem)</button>
        </div>  
    </form>
    <div style="text-align: center;">
        <button class="btn btn-info my-2" id="reportButton"> Nahlásit video(nepř. Nefunguje/není CZ dab)</button>
    </div>
`;

    document.getElementById('reportButton').addEventListener('click', function() {
    const fileSlug = file.Slugid; // Název videa z file objektu

    fetch('track_clicks.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `file_slug=${encodeURIComponent(fileSlug)}`
    })
    .then(response => response.json())
    .then(result => {
        const button = document.getElementById('reportButton');
        
        if (result.status === 'error' && result.message === 'moc') {
            // Pokud uživatel klikl více než 5x, změníme tlačítko na červené
            button.classList.remove('btn-info');
            button.classList.add('btn-danger');
            button.textContent = 'přesáhl jsi denní limit';
            button.disabled = true;
        } else if (result.status === 'success') {
            // Pokud je úspěch, změníme tlačítko na zelené a deaktivujeme ho
            button.classList.remove('btn-info');
            button.classList.add('btn-success');
            button.textContent = 'mrknu na to, díky';
            button.disabled = true;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        });
                    });

                    modal.show();
                    
                }  
            });

            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.className = 'bd-placeholder-img card-img-top';
            svg.setAttribute('width', '100%');
            svg.setAttribute('height', '225');
            svg.setAttribute('xmlns', 'http://www.w3.org/2000/svg');
            svg.setAttribute('role', 'img');
            svg.setAttribute('aria-label', 'Placeholder: Thumbnail');
            svg.setAttribute('preserveAspectRatio', 'xMidYMid slice');
            svg.setAttribute('focusable', 'false');

            const rect = document.createElementNS('http://www.w3.org/2000/svg', 'rect');
            rect.setAttribute('width', '100%');
            rect.setAttribute('height', '100%');
            rect.setAttribute('fill', '#55595c');
            svg.appendChild(rect);

            const icon = document.createElementNS('http://www.w3.org/2000/svg', 'text');
            icon.setAttribute('x', '50%');
            icon.setAttribute('y', '50%');
            icon.setAttribute('fill', '#eceeef');
            icon.setAttribute('dy', '.3em');
            icon.textContent = '🎬'; // Ikona videa
            svg.appendChild(icon);

            const cardBody = document.createElement('div');
            cardBody.className = 'card-body';

            const cardText = document.createElement('p');
            cardText.className = 'card-text';
            cardText.textContent = file.Jmeno;
            cardBody.appendChild(cardText);

            const cardFooter = document.createElement('div');
            cardFooter.className = 'd-flex justify-content-between align-items-center';

            const small = document.createElement('small');
            small.className = 'text-body-secondary';
            small.textContent = file.cas;
            cardFooter.appendChild(small);

            cardBody.appendChild(cardFooter);
            card.appendChild(svg);
            card.appendChild(cardBody);
            col.appendChild(card);
            container.appendChild(col);
        });
    } catch (error) {
        console.error('Error fetching files:', error);
    }
}

function removeDiacritics(str) {
    return str.normalize("NFD").replace(/[\u0300-\u036f]/g, "");
}

// Initial load without search
document.addEventListener('DOMContentLoaded', () => {
    searchFiles('');
});


</script>
        
      </div>
        

        
      </div>
    </div>
  </div>

</main>

<footer class="text-body-secondary py-5">
  <div class="container">
    
    <p class="mb-1">Vyrobil ShadowMoonlight 2024</p>
    
  </div>
</footer>

<!-- darkmode script + ikonky -->
<svg xmlns="http://www.w3.org/2000/svg" class="d-none">
  <symbol id="check2" viewBox="0 0 16 16">
    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
  </symbol>
  <symbol id="circle-half" viewBox="0 0 16 16">
    <path d="M8 15A7 7 0 1 0 8 1v14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
  </symbol>
  <symbol id="moon-stars-fill" viewBox="0 0 16 16">
    <path d="M6 .278a.768.768 0 0 1 .08.858 7.208 7.208 0 0 0-.878 3.46c0 4.021 3.278 7.277 7.318 7.277.527 0 1.04-.055 1.533-.16a.787.787 0 0 1 .81.316.733.733 0 0 1-.031.893A8.349 8.349 0 0 1 8.344 16C3.734 16 0 12.286 0 7.71 0 4.266 2.114 1.312 5.124.06A.752.752 0 0 1 6 .278z"/>
    <path d="M10.794 3.148a.217.217 0 0 1 .412 0l.387 1.162c.173.518.579.924 1.097 1.097l1.162.387a.217.217 0 0 1 0 .412l-1.162.387a1.734 1.734 0 0 0-1.097 1.097l-.387 1.162a.217.217 0 0 1-.412 0l-.387-1.162A1.734 1.734 0 0 0 9.31 6.593l-1.162-.387a.217.217 0 0 1 0-.412l1.162-.387a1.734 1.734 0 0 0 1.097-1.097l.387-1.162zM13.863.099a.145.145 0 0 1 .274 0l.258.774c.115.346.386.617.732.732l.774.258a.145.145 0 0 1 0 .274l-.774.258a1.156 1.156 0 0 0-.732.732l-.258.774a.145.145 0 0 1-.274 0l-.258-.774a1.156 1.156 0 0 0-.732-.732l-.774-.258a.145.145 0 0 1 0-.274l.774-.258c.346-.115.617-.386.732-.732L13.863.1z"/>
  </symbol>
  <symbol id="sun-fill" viewBox="0 0 16 16">
    <path d="M8 12a4 4 0 1 0 0-8 4 4 0 0 0 0 8zM8 0a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 0zm0 13a.5.5 0 0 1 .5.5v2a.5.5 0 0 1-1 0v-2A.5.5 0 0 1 8 13zm8-5a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2a.5.5 0 0 1 .5.5zM3 8a.5.5 0 0 1-.5.5h-2a.5.5 0 0 1 0-1h2A.5.5 0 0 1 3 8zm10.657-5.657a.5.5 0 0 1 0 .707l-1.414 1.415a.5.5 0 1 1-.707-.708l1.414-1.414a.5.5 0 0 1 .707 0zm-9.193 9.193a.5.5 0 0 1 0 .707L3.05 13.657a.5.5 0 0 1-.707-.707l1.414-1.414a.5.5 0 0 1 .707 0zm9.193 2.121a.5.5 0 0 1-.707 0l-1.414-1.414a.5.5 0 0 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .707zM4.464 4.465a.5.5 0 0 1-.707 0L2.343 3.05a.5.5 0 1 1 .707-.707l1.414 1.414a.5.5 0 0 1 0 .708z"/>
  </symbol>
</svg>



<!-- Modal video -->
<div class="modal fade" id="fileModal" tabindex="-1" aria-labelledby="fileModalLabel" aria-hidden="true">
  <div class="modal-dialog wider-modal">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Iframe bude vloženo zde pomocí JavaScriptu -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zavřít</button>
      </div>
    </div>
  </div>
</div>



<!-- Login Modal -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="loginModalLabel">Přihlášení</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="loginForm">
          <div class="mb-3">
            <label for="login-email" class="form-label">Email</label>
            <input type="text" class="form-control" id="login-email" placeholder="Zadejte email">
            <div id="loginEmailFeedback" class="invalid-feedback">
              Prosím, zadejte platný email.
            </div>
          </div>
          <div class="mb-3">
            <label for="login-password" class="form-label">Heslo</label>
            <input type="password" class="form-control" id="login-password" placeholder="Zadejte heslo">
            <div id="loginPasswordFeedback" class="invalid-feedback">
              Heslo musí obsahovat minimálně 8 znaků.
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="loginButton" disabled>Přihlásit se</button>
        <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#registrationModal" data-bs-dismiss="modal">Zaregistrovat se</button>
        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" data-bs-dismiss="modal">Zapomenuté heslo</button>
      </div>
    </div>
  </div>
</div>
<script>
  document.getElementById('loginButton').addEventListener('click', async function() {
    const email = document.getElementById('login-email').value;
    const password = document.getElementById('login-password').value;

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, password: password })
        });

        const result = await response.json();

        if (result.success) {
            alert('Přihlášení bylo úspěšné.');
            // Optionally, you can reload the page or redirect to another page
            location.reload();
        } else {
            alert(result.message || 'Přihlášení se nezdařilo. Zkuste to prosím znovu.');
        }
    } catch (error) {
        console.error('Error:', error);
    }
});
  </script>

<!-- Registration Modal -->
<div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="registrationModalLabel">Registrace</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="registrationForm">
          <div class="mb-3">
            <label for="reg-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="reg-email" placeholder="Zadejte email">
            <div id="emailFeedback" class="invalid-feedback">
              Prosím, zadejte platný email.
            </div>
          </div>
          <div class="mb-3">
            <label for="confirm-email" class="form-label">Potvrďte email</label>
            <input type="email" class="form-control" id="confirm-email" placeholder="Znovu zadejte email">
            <div id="confirmEmailFeedback" class="invalid-feedback">
              Email se neshoduje.
            </div>
          </div>
          <div class="mb-3">
            <label for="reg-password" class="form-label">Heslo</label>
            <input type="password" class="form-control" id="reg-password" placeholder="Zadejte heslo">
            <div id="passwordFeedback" class="invalid-feedback">
              Heslo musí obsahovat minimálně 8 znaků.
            </div>
          </div>
          <div class="mb-3">
            <label for="confirm-password" class="form-label">Potvrďte heslo</label>
            <input type="password" class="form-control" id="confirm-password" placeholder="Znovu zadejte heslo">
            <div id="confirmPasswordFeedback" class="invalid-feedback">
              Heslo se neshoduje.
            </div>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="registerButton" disabled>Zaregistrovat se</button>
        <button type="button" class="btn btn-link" data-bs-toggle="modal" data-bs-target="#forgotPasswordModal" data-bs-dismiss="modal">Zapomenuté heslo</button>
      </div>
    </div>
  </div>
</div>


<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-labelledby="forgotPasswordModalLabel" aria-hidden="true" data-bs-backdrop="true" data-bs-keyboard="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="forgotPasswordModalLabel">Zapomenuté heslo</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="forgotPasswordForm">
          <div class="mb-3">
            <label for="forgot-password-email" class="form-label">Email</label>
            <input type="email" class="form-control" id="forgot-password-email" placeholder="Zadejte email">
            <div id="forgotPasswordEmailFeedback" class="invalid-feedback">
              Prosím, zadejte platný email.
            </div>
          </div>
          <div class="mb-3">
            <label for="captcha-question" class="form-label">Vyřešte příklad: <span id="captcha-question"></span></label>
            <input type="number" class="form-control" id="captcha-answer" placeholder="Výsledek">
            <div id="captchaFeedback" class="invalid-feedback">
              Prosím, zadejte správný výsledek.
            </div>
          </div>
          
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-primary" id="forgotPasswordButton" disabled>Odeslat</button>
      </div>
    </div>
  </div>
</div>

<script>
  document.getElementById('login-email').addEventListener('input', validateLoginForm);
  document.getElementById('login-password').addEventListener('input', validateLoginForm);

  function validateLoginForm() {
    const emailInput = document.getElementById('login-email');
    const passwordInput = document.getElementById('login-password');
    const emailFeedback = document.getElementById('loginEmailFeedback');
    const passwordFeedback = document.getElementById('loginPasswordFeedback');
    const loginButton = document.getElementById('loginButton');

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isEmailValid = emailPattern.test(emailInput.value);
    const isPasswordValid = passwordInput.value.length >= 8;

    if (isEmailValid) {
      emailInput.classList.remove('is-invalid');
      emailInput.classList.add('is-valid');
      emailFeedback.style.display = 'none';
    } else {
      emailInput.classList.remove('is-valid');
      emailInput.classList.add('is-invalid');
      emailFeedback.style.display = 'block';
    }

    if (isPasswordValid) {
      passwordInput.classList.remove('is-invalid');
      passwordInput.classList.add('is-valid');
      passwordFeedback.style.display = 'none';
    } else {
      passwordInput.classList.remove('is-valid');
      passwordInput.classList.add('is-invalid');
      passwordFeedback.style.display = 'block';
    }

    loginButton.disabled = !(isEmailValid && isPasswordValid);
  }

document.getElementById('reg-email').addEventListener('input', validateRegistrationForm);
document.getElementById('confirm-email').addEventListener('input', validateRegistrationForm);
document.getElementById('reg-password').addEventListener('input', validateRegistrationForm);
document.getElementById('confirm-password').addEventListener('input', validateRegistrationForm);
document.getElementById('registerButton').addEventListener('click', registerUser);

function validateRegistrationForm() {
    const emailInput = document.getElementById('reg-email');
    const confirmEmailInput = document.getElementById('confirm-email');
    const passwordInput = document.getElementById('reg-password');
    const confirmPasswordInput = document.getElementById('confirm-password');

    const emailFeedback = document.getElementById('emailFeedback');
    const confirmEmailFeedback = document.getElementById('confirmEmailFeedback');
    const passwordFeedback = document.getElementById('passwordFeedback');
    const confirmPasswordFeedback = document.getElementById('confirmPasswordFeedback');
    const registerButton = document.getElementById('registerButton');

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    const isEmailValid = emailPattern.test(emailInput.value);
    const isEmailConfirmed = emailInput.value === confirmEmailInput.value;
    const isPasswordValid = passwordInput.value.length >= 8;
    const isPasswordConfirmed = passwordInput.value === confirmPasswordInput.value;

    if (isEmailValid) {
        emailInput.classList.remove('is-invalid');
        emailInput.classList.add('is-valid');
        emailFeedback.style.display = 'none';
    } else {
        emailInput.classList.remove('is-valid');
        emailInput.classList.add('is-invalid');
        emailFeedback.style.display = 'block';
    }

    if (isEmailConfirmed) {
        confirmEmailInput.classList.remove('is-invalid');
        confirmEmailInput.classList.add('is-valid');
        confirmEmailFeedback.style.display = 'none';
    } else {
        confirmEmailInput.classList.remove('is-valid');
        confirmEmailInput.classList.add('is-invalid');
        confirmEmailFeedback.style.display = 'block';
    }

    if (isPasswordValid) {
        passwordInput.classList.remove('is-invalid');
        passwordInput.classList.add('is-valid');
        passwordFeedback.style.display = 'none';
    } else {
        passwordInput.classList.remove('is-valid');
        passwordInput.classList.add('is-invalid');
        passwordFeedback.style.display = 'block';
    }

    if (isPasswordConfirmed) {
        confirmPasswordInput.classList.remove('is-invalid');
        confirmPasswordInput.classList.add('is-valid');
        confirmPasswordFeedback.style.display = 'none';
    } else {
        confirmPasswordInput.classList.remove('is-valid');
        confirmPasswordInput.classList.add('is-invalid');
        confirmPasswordFeedback.style.display = 'block';
    }

    registerButton.disabled = !(isEmailValid && isEmailConfirmed && isPasswordValid && isPasswordConfirmed);
}

async function registerUser() {
    const email = document.getElementById('reg-email').value;
    const password = document.getElementById('reg-password').value;

    try {
        const response = await fetch('register_user.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email, password: password })
        });

        const result = await response.json();

        if (result.success) {
            alert(result.message || 'Registrace byla úspěšná.');
            location.reload();
        } else {
            alert(result.message || 'Registrace se nezdařila. Zkuste to prosím znovu.');
        }
    } catch (error) {
        console.error('Error:', error);
        alert('Došlo k chybě. Zkuste to prosím znovu.');
    }
}



  document.getElementById('forgot-password-email').addEventListener('input', validateForgotPasswordForm);
document.getElementById('captcha-answer').addEventListener('input', validateForgotPasswordForm);
document.getElementById('forgotPasswordButton').addEventListener('click', sendForgotPasswordEmail);

// Generate captcha question
function generateCaptcha() {
  const num1 = Math.floor(Math.random() * 10);
  const num2 = Math.floor(Math.random() * 10);
  document.getElementById('captcha-question').textContent = `${num1} + ${num2} = ?`;
  return num1 + num2;
}

let captchaResult = generateCaptcha();

function validateForgotPasswordForm() {
  const emailInput = document.getElementById('forgot-password-email');
  const captchaInput = document.getElementById('captcha-answer');
  const emailFeedback = document.getElementById('forgotPasswordEmailFeedback');
  const captchaFeedback = document.getElementById('captchaFeedback');
  const forgotPasswordButton = document.getElementById('forgotPasswordButton');

  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  const isEmailValid = emailPattern.test(emailInput.value);
  const isCaptchaValid = parseInt(captchaInput.value) === captchaResult;

  if (isEmailValid) {
    emailInput.classList.remove('is-invalid');
    emailInput.classList.add('is-valid');
    emailFeedback.style.display = 'none';
  } else {
    emailInput.classList.remove('is-valid');
    emailInput.classList.add('is-invalid');
    emailFeedback.style.display = 'block';
  }

  if (isCaptchaValid) {
    captchaInput.classList.remove('is-invalid');
    captchaInput.classList.add('is-valid');
    captchaFeedback.style.display = 'none';
  } else {
    captchaInput.classList.remove('is-valid');
    captchaInput.classList.add('is-invalid');
    captchaFeedback.style.display = 'block';
  }

  forgotPasswordButton.disabled = !(isEmailValid && isCaptchaValid);
}

async function sendForgotPasswordEmail() {
    const email = document.getElementById('forgot-password-email').value;
    const captchaInput = document.getElementById('captcha-answer');

    if (parseInt(captchaInput.value) !== captchaResult) {
        alert('Nesprávný výsledek příkladu.');
        return;
    }

    try {
        const response = await fetch('store_reset_token.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ email: email })
        });

        const result = await response.json();

        if (result.success) {
            alert('Email pro resetování hesla byl odeslán.');
            captchaResult = generateCaptcha(); // regenerate captcha
        } else {
            alert(result.message || 'Odeslání emailu se nezdařilo. Zkuste to prosím znovu.');
        }
    } catch (error) {
        console.error('Error:', error);
    }
}




</script>

<style>
  .is-invalid {
    border-color: #dc3545;
  }

  .is-valid {
    border-color: #28a745;
  }

  .invalid-feedback {
    display: none;
    color: #dc3545;
  }
</style>









<style>
  .is-invalid {
    border-color: #dc3545;
  }

  .is-valid {
    border-color: #28a745;
  }

  .invalid-feedback {
    display: none;
    color: #dc3545;
  }
</style>

<!-- Modal for Premium Payment -->
<div class="modal fade" id="premiumModal" tabindex="-1" aria-labelledby="premiumModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="premiumModalLabel">Koupit Premium</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="qrcode"></div>
        <p>pro platbu bez qr kódu stačí zaslat částku 39,00kč na zmíněný účet(do zprávy pro příjmce uveďte svůj email)</p>
        <p>Číslo účtu: 2791887013/3030</p>
        <p>Částka: 39,- CZK</p>
        <p>Zpráva pro příjemce: <span id="recipient-message"></span></p>
        <p>Po dokončení platby vyčkejte 1-3min (průměrná doba: 2min z baky Airbank, RB, fio, moneta) u ostatních to může trvat <a href="https://wise.com/cz/blog/jak-dlouho-trva-prevod-penez-z-uctu-na-ucet">déle</a></p>
        <img style="width: 50%; height: 50%;" src="platba.JPG">


        <p>TIP: pokud se vám obsah na webu líbí a chcete pokračovat dál, můžete udělat trvalou platbu (do zprávy pro příjemce zanechte svůj email)</p>
        <p>při problémech mě kontaktujte:</p>
        <img  src="podpora_mail.JPG">
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-secondary" onclick="window.location.href='https://koukej.online';">Zavřít</button>
      <button id="loadingButton" class="btn btn-primary" type="button" disabled>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Kontrola platby...
        </button>
      </div>
    </div>
  </div>
</div>
<style>
  #premiumModal .modal-dialog {
    max-width: 80%; /* Zvětší šířku modalu o 20% */
  }
</style>

<!-- Add QRCode.js library -->
<script src="https://cdn.jsdelivr.net/npm/qrcode/build/qrcode.min.js"></script>

<script>document.addEventListener('DOMContentLoaded', () => {
    const userEmail = <?php echo json_encode($userEmail); ?>;
    document.getElementById('recipient-message').textContent = userEmail;

    document.getElementById('premiumModal').addEventListener('show.bs.modal', () => {
        const qrCodeContainer = document.getElementById('qrcode');
        qrCodeContainer.innerHTML = ''; // Clear any existing QR code

        const qrData = `SPD*1.0*ACC:CZ3030300000002791887013*AM:39.00*CC:CZK*MSG:${userEmail}`;

        QRCode.toDataURL(qrData, { width: 200 }, (error, url) => {
            if (error) console.error(error);
            const img = document.createElement('img');
            img.src = url;
            qrCodeContainer.appendChild(img);
        });

        const initialOpravneni = <?php echo json_encode($opravneni); ?>;
        startPremiumCheck(initialOpravneni, userEmail);
    });

    function startPremiumCheck(initialOpravneni, userEmail) {
        const loadingButton = document.getElementById('loadingButton');
        loadingButton.style.display = 'inline-block';

        const checkInterval = setInterval(async () => {
            try {
                console.log('Sending email to check_opravneni.php:', userEmail); // Debug log
                const response = await fetch('check_opravneni.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email: userEmail })
                });

                console.log('Response status:', response.status); // Debug log
                const result = await response.json();
                console.log('Response from check_opravneni.php:', result); // Debug log

                if (result.opravneni !== null && result.opravneni >= initialOpravneni + 30) {
                    clearInterval(checkInterval);
                    loadingButton.innerHTML = 'Premium bylo prodlouženo! (klikněte na tlačítko zavřít)';
                    loadingButton.classList.remove('btn-primary');
                    loadingButton.classList.add('btn-success');
                }
            } catch (error) {
                console.error('Error checking premium status:', error);
                try {
                    await fetch('https://koukej.online/CRONS/sadwascyxcsdawdadsdsadwdasd.php');
                } catch (fetchError) {
                    console.error('Error running background script:', fetchError);
                }
            }
        }, 10000); // Check every 10 seconds
    }
});

</script>
<div id="loadingSpinner" class="spinner-border text-primary" role="status" style="display:none;">
</div>
    </body>
</html>
