
document.addEventListener("DOMContentLoaded", () => {
    const passwordField = document.getElementById("password");
    if (passwordField) {
        // Nastav heslo pri načítaní stránky
        passwordField.value = generate_password();

        // Nastav nové heslo pri zaostrení
        passwordField.addEventListener("focus", () => {
            passwordField.value = generate_password();
        });
    } else {
        console.error("Prvok s ID 'password' neexistuje.");
    }
});


const keys = {
    upperCase: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
    lowerCase: "abcdefghijklmnopqrstuvwxyz",
    number: "0123456789",
    symbol: "!@#$%^&*()_+~\`|}{[]:;?><,./-="
}

const getKey = [
    function upperCase() {
        return keys.upperCase[Math.floor(Math.random() * keys.upperCase.length)];
    },
    function lowerCase() {
        return keys.lowerCase[Math.floor(Math.random() * keys.lowerCase.length)];
    },
    function number() {
        return keys.number[Math.floor(Math.random() * keys.number.length)];
    },
    function symbol() {
        return keys.symbol[Math.floor(Math.random() * keys.symbol.length)];
    }
];


function generate_password() {
    console.log("Začínam generovať heslo...");
    const passwordBox = document.getElementById("password");
    const getKey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!@#$%^&*()";
    const length = 15;
    let password = "";

    while (password.length < length) {
        let keyToAdd = getKey[Math.floor(Math.random() * getKey.length)];
        password += keyToAdd;
    }

    passwordBox.value = password;
    console.log("Vygenerované heslo:", password);

    return password;
}


function copyPassword() {
    const textarea = document.createElement('textarea');
    const password = document.getElementById("password").value;
    if (!password) { return; }
    textarea.value = password;
    document.body.appendChild(textarea);
    textarea.select();
    document.execCommand('copy');
    textarea.remove();
    alert('Password copied to clipboard');
}


function show_hide_note() {
    var new_pass_note = document.getElementById("pass_note_text");
    new_pass_note.style.display = (new_pass_note.style.display == "flex") ? "none" : "flex";
}



function get_host_name(url) {

    var full_base_url = url.replace(/(^\w+:|^)\/\//, '');
    console.log(full_base_url);
    if (document.getElementById("system_name").value === "") {
        document.getElementById("system_name").value = full_base_url;
    }
}


