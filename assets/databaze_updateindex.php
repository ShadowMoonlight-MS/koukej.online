<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Fetch</title>
</head>
<body>
    
    <div id="output"></div>
    <script>
        async function fetchData(page) {
            const response = await fetch('databaze_update.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: `page=${page}`
            });
            const data = await response.json();
            return data;
        }

        function formatSQLInsert(item) {
            const jmeno = item.name ? item.name.replace(/'/g, "\\'") : '';
            const slug = item.slug ? item.slug.replace(/'/g, "\\'") : '';
            return `INSERT INTO \`Soubor\` (\`idsoubor\`, \`Jmeno\`, \`Slugid\`, \`cas\`) VALUES (NULL, '${jmeno}', '${slug}', NULL);`;
        }

        async function fetchAllData() {
            const outputDiv = document.getElementById('output');
            for (let page = 1; page <= 20; page++) {
                const data = await fetchData(page);
                if (data.items) {
                    data.items.forEach(item => {
                        const sqlInsert = formatSQLInsert(item);
                        const pre = document.createElement('pre');
                        pre.textContent = sqlInsert;
                        outputDiv.appendChild(pre);
                    });
                } else {
                    console.error('No items found in the data:', data);
                }
                await new Promise(resolve => setTimeout(resolve, 500)); // Optional delay
            }
        }

        fetchAllData();
    </script>
</body>
</html>
