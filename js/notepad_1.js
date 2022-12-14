$(document).ready(function() {
    size_li = $("#div_category_list li").length;
    x = 7;
    $('#div_category_list li:lt(' + x + ')').show();
    $('#loadMore').click(function() {
        x = (x + 5 <= size_li) ? x + 5 : size_li;
        $('#div_category_list li:lt(' + x + ')').show();
    });
    $('#showLess').click(function() {
        x = (x - 5 < 0) ? 3 : x - 5;
        $('#div_category_list li').not(':lt(' + x + ')').hide();
    });
});

function ShowModalAddCat(note_id) {


    el = document.getElementById("add_new_tag");
    el.style.display = (el.style.display == "flex") ? "none" : "flex";

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("missing_cats").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "load_miss_note_cats.php?note_id=" + note_id, true);
    xhttp.send();


}

function add_new_link() {
    /* el = document.getElementById("overlay");
     el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";*/

new_link = document.getElementById("add_new_link");
new_link.style.display = (new_link.style.display == "flex") ? "none" : "flex";

//new_link.style.visibility = (new_link.style.display == "block") ? "none" : "block";
}

function ShowModalAddNewCat() {


    el = document.getElementById("overlay");
    el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";
}

function close() {
    document.querySelector(".overlay").style.display = "none";
}

function search_category(category){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("category_list").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "load_category.php?category=" + category, true);
    xhttp.send();
} 