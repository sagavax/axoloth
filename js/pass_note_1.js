function save_note(id) {
    console.log(id);
    //note_title = document.getElementById("pass_note_title").value;
    note_text = document.getElementById("pass_note_text").value;
    /*console.log(note_text);*/
    attach_new_pass_note(note_text);


    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            //alert("bol pridany link");
            //attach_new_pass_note(note_text);
            document.getElementById("pass_note_text").value = "";
            //document.querySelector(".pass_note_container").style.display = "none";
        }
    };

    xhttp.open("POST", "create_pass_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("pass_id=" + id + "&note_text=" + encodeURIComponent(note_text));

}

function attach_new_pass_note(note_text) {
    var parent_container = document.querySelector(".pass_note_container");
    parent_container.insertAdjacentHTML('afterbegin', "<div class='pass_note'>" + note_text + "</div>");
    //parent_container.style.display = "none";
}


function save_modal_note() {
    var id = sessionStorage.getItem("pass-id");
    //note_title = document.getElementById("pass_note_title").value;
    note_text = document.getElementById("pass_note_text").value;
    /*console.log(note_text);*/
   // attach_new_pass_note(note_text);


    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            //alert("bol pridany link");
            //attach_new_pass_note(note_text);
            document.getElementById("pass_note_text").value = "";
            document.querySelector(".show_add_note").style.display = "none";
        }
    };

    xhttp.open("POST", "create_pass_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("pass_id=" + id + "&note_text=" + encodeURIComponent(note_text));

}