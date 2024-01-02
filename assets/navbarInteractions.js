import {updateLocale} from "./navbar.ts";

let flags = document.querySelectorAll('.flag');
flags.forEach(function (element) {
    element.addEventListener("click", function() {
        updateLocale('fr');
    });
})