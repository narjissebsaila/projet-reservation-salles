/*
    Fichier : script.js
    Rôle : ajouter une petite interaction côté client.
    Ici, on fait une recherche simple dans le tableau sans recharger la page.
*/

const searchInput = document.getElementById("searchInput");
const tableRows = document.querySelectorAll("#reservationTable tr");

searchInput.addEventListener("keyup", function () {
    const searchValue = searchInput.value.toLowerCase();

    tableRows.forEach(function (row) {
        const rowText = row.textContent.toLowerCase();

        if (rowText.includes(searchValue)) {
            row.style.display = "";
        } else {
            row.style.display = "none";
        }
    });
});