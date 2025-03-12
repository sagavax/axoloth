var btns_remove = document.querySelectorAll(".remove_task_note");
//console.log(btns_remove);
for (var i = 0; i < btns_remove.length; i++) {
    var btn_remove = btns_remove[i];
    btn_remove.addEventListener("click", function() {

        var note_id = this.parentNode.parentNode.getAttribute("note-id");
        var task_id = localStorage.getItem("task_id");
        //alert(note_id);
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                reload_task_notes();
            }
        }
        xhttp.open("POST", "task_note_remove.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("note_id=" + note_id + "&task_id=" + task_id);
    });

};

/*

https://stackoverflow.com/questions/20805505/addeventlistener-not-working-on-list-of-elements
https://stackoverflow.com/questions/26008243/is-it-more-efficient-to-use-addeventlistener-or-onclick-for-performance
https://stackoverflow.com/questions/6348494/addeventlistener-vs-onclick 
https://flaviocopes.com/how-to-add-event-listener-multiple-elements-javascript/


*/

var btn_add = document.querySelector(".add_task_note");
btn_add.addEventListener("click", function(e) {
    console.log("click");
    var task_id = localStorage.getItem("task_id");
    var el = document.querySelector(".modal_add_task_note");
    el.style.display = (el.style.display == 'none' || el.style.display == '') ? 'flex' : 'none';


    //window.location.href = "task_note.php?task_id=" + task_id;
})

document.querySelector(".task_note_add").addEventListener("click", function() {
    var note_text = document.getElementById("task_note_text").value;
    var task_id = localStorage.getItem("task_id");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

            document.querySelector(".modal_add_task_note").style.display = "none";
            reload_task_notes();
        }
    }
    xhttp.open("POST", "task_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_id=" + task_id + "&note_text=" + encodeURIComponent(note_text));




});

document.querySelector(".task_note_hide").addEventListener("click", function() {
    document.querySelector(".modal_add_task_note").style.display = "none";
});

function reload_task_notes() {
    var task_id = localStorage.getItem("task_id");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".task_notes_body").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_task_notes.php?task_id=" + task_id, true);
    xhttp.send();
}


var menus = document.querySelectorAll(".menu");
for (var i = 0; i < menus.length; i++) {
    menus[i].addEventListener("click", function(e) {
        console.log(menu[i]);
        alert("Click");
    })
}