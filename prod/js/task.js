var btn_add = document.querySelector(".add_task_note");
btn_add.addEventListener("click", function(e) {
    var task_id = localStorage.getItem("task_id");
    window.location.href = "task_note.php?task_id=" + task_id;
})

var btns_remove = document.querySelectorAll(".remove_task_note");
console.log(btns_remove);
for (var i = 0; i < btns_remove.length; i++) {
    console.log(btns_remove.length);
    var btn_remove = btns_remove[i];
    btn_remove.addEventListener("click", function() {

        var note_id = this.parentNode.parentNode.getAttribute("task_note_id");
        alert(note_id);
    });

};

/*

https://stackoverflow.com/questions/20805505/addeventlistener-not-working-on-list-of-elements
https://stackoverflow.com/questions/26008243/is-it-more-efficient-to-use-addeventlistener-or-onclick-for-performance
https://stackoverflow.com/questions/6348494/addeventlistener-vs-onclick 
https://flaviocopes.com/how-to-add-event-listener-multiple-elements-javascript/
https://www.codeinwp.com/snippets/add-event-listener-to-multiple-elements-with-javascript/
https://www.codegrepper.com/code-examples/javascript/javascript+add+click+event+to+multiple+elements


*/