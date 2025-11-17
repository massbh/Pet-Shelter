// // Load header and footer automatically
// document.addEventListener("DOMContentLoaded", function () {
//     // Load header
//     fetch("/components/header.html")
//         .then((response) => response.text())
//         .then((data) => {
//             document.getElementById("header-container").innerHTML = data;
//         })
//         .catch((error) => console.error("Error loading header:", error));

//     // Load footer
//     fetch("/components/footer.html")
//         .then((response) => response.text())
//         .then((data) => {
//             document.getElementById("footer-container").innerHTML = data;
//         })
//         .catch((error) => console.error("Error loading footer:", error));
// });
