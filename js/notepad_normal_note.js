function show_add_note() {
    new_short_note = document.getElementById("modal_add_new_note");
    new_short_note.style.display = (new_short_note.style.display == "flex") ? "none" : "flex";
}

function hide_add_note() {
    new_short_note = document.getElementById("modal_add_new_note");
    new_short_note.style.display = "none";
}