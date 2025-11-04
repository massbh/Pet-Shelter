document.addEventListener("DOMContentLoaded", function () {
    // Load header
    fetch("/components/header.html")
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("header-container").innerHTML = data;

            const user = window.user || null;

            const signBtnP = document.querySelector(
                ".header-buttons .sign-up-btn p"
            );
            const signBtnLink = document.querySelector(
                ".header-buttons .sign-up-btn"
            );

            if (signBtnP && signBtnLink) {
                if (user) {
                    signBtnP.textContent = user.name;
                    signBtnLink.href = "/dashboard";
                } else {
                    signBtnP.textContent = "Sign In";
                    signBtnLink.href = "/signin";
                }
            }
        })
        .catch((error) => console.error("Error loading header:", error));

    // Load footer
    fetch("/components/footer.html")
        .then((response) => response.text())
        .then((data) => {
            document.getElementById("footer-container").innerHTML = data;
        })
        .catch((error) => console.error("Error loading footer:", error));
});
