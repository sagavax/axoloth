const customBtn = document.getElementById("upload_note_from_file"); //tlacitko na ktory kliknem
const realFileBtn = document.getElementById("file-text"); //input typu file




customBtn.addEventListener("click", function() {
    realFileBtn.click();
});

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

/*$(document).ready(function() {
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
});*/

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

function search_category(category) {
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


//var items = document.querySelectorAll("category_list");
var list = document.getElementById("category_list");
var items = list.children;

const myElement = document.getElementById('category_list');
for (let i = 0; i < myElement.children.length; i++) {
    //console.log(myElement.children[i].getAttribute("category-id"));
    myElement.children[i].addEventListener("click", function() {
        //alert(this.getAttribute("category-id"));
        show_notes_by_category(this.getAttribute("category-id"));
    })
}

function show_notes_by_category(category_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("notes").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "notes.php?category_id=" + category_id, true);
    xhttp.send();

}

//console.log(items);
/* list[i].addEventListener('click', () => {

     // Log values
     console.log(list[i].innerText);
 })*/

//https://developer.mozilla.org/en-US/docs/Web/API/Element/children