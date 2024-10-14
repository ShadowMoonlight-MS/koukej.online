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