function show_add_short_note() {
    new_short_note = document.getElementById("add_new_short_note");
    new_short_note.style.display = (new_short_note.style.display == "flex") ? "none" : "flex";
}

function hide_add_short_note() {
    new_short_note = document.getElementById("add_new_short_note");
    new_short_note.style.display = "none";
}

//https://www.youtube.com/watch?v=cRAHKgT-DUk