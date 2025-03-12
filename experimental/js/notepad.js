$(document).ready(function() {
    size_li = $("#div_category_list li").size();
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
    el.style.display = (el.style.display == "block") ? "none" : "block";

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
    document.getElementById("overlay").style.visibility = 'hidden';
}

function ukaz_skry_novu_kategoriu(id) {
    var x = document.getElementById('new_cat');
    var e = document.getElementById(id);
    var eStyle = getComputedStyle(e).getPropertyValue('display');
    if (eStyle == "none") {
        x.style.display = "block"
    } else {
        x.style.display = "none";
    }

}

function ShowModalAddCat(note_id) {


    el = document.getElementById("add_new_tag");
    console.log(el);
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
};

const customBtn = document.getElementById("upload_note_from_file"); //tlacitko na ktory kliknem
const realFileBtn = document.getElementById("file-text"); //input typu file

customBtn.addEventListener("click", function() {
    realFileBtn.click();
});

function add_new_note() {
    window.location.href = "./note_short_add.php";
}