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


function ShowModalAddCat(note_id) {
    localStorage.setItem("note_id", note_id);
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

function show_add_link() {
    /* el = document.getElementById("overlay");
     el.style.visibility = (el.style.visibility == "visible") ? "hidden" : "visible";*/

    //zobrazime modal

    new_link = document.getElementById("add_new_link");
    new_link.style.display = (new_link.style.display == "flex") ? "none" : "flex";
    document.getElementById("add_link").addEventListener("click", () => {
            var xhttp = new XMLHttpRequest();
            var note_title = document.getElementById("note_title").value;
            var note_source = document.getElementById("note_source").value;

            xhttp.onreadystatechange = function() {
                if (this.readyState == 4 && this.status == 200) {
                    //reload_note_categories()
                    alert("bol pridany link");
                    new_link.style.display = "none";
                    attach_new_link(note_title, note_source);

                    //reload_notes();
                }
            };
            xhttp.open("POST", "note_add_link.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("note_title=" + encodeURIComponent(note_title) + "&note_source=" + encodeURIComponent(note_source));

            //zavrieme modal

        })
        //new_link.style.visibility = (new_link.style.display == "block") ? "none" : "block";
}

function hide_add_link() {
    new_link = document.getElementById("add_new_link");
    new_link.style.display = "none";
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

//tabs
const tabs = document.querySelector("#tabs ul");
for (let i = 0; i < tabs.children.length; i++) {
    //tabs.children[i].classList.add("tab_inactive"); //najprv nastavim vsetky taby ako neaktivne
    //tabs.children[0].classList.add("tab_active"); //prv bude aktivny ako default

    tabs.children[i].addEventListener("click", function(e) {
        for (let y = 0; y < tabs.children.length; y++) {
            tabs.children[y].classList.remove("tab_active");
            tabs.children[y].classList.add("tab_inactive");
            //console.log(tabs.children[y]);
        }
        tabs.children[i].classList.add("tab_active");
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("notes").innerHTML =
                    this.responseText;
            }
        };
        const tab_text = tabs.children[i].innerHTML
        xhttp.open("GET", "notes.php?tab_id=" + tab_text, true);
        xhttp.send();
        //alert(tabs.children[i].innerHTML);
    })
}

function delete_category(e) {
    //category_id = this.parentNode.getAttribute("category_id");
    cat_id = e.parentNode.getAttribute("category-id");
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            reload_note_categories()
            alert("bolo to vymazane");
        }
    };
    xhttp.open("POST", "note_remove_category.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("cat_id=" + cat_id);
    //alert(this.getAttribute("data-element"));
}

function reload_note_categories() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("category_list").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_categories.php", true);
    xhttp.send();
}

function add_tag(text, cat_id) {
    console.log(cat_id);
    console.log(text);

    //vytvori tag
    var button = document.createElement("button");
    //var li = document.createElement("li");
    button.classList.add("flat-btn");
    //link.href = "index.php?cat_id=" + cat_id;
    button.innerText = text;
    button.setAttribute("cat-id", cat_id);
    const parent = document.querySelector(".tag_list_wrap");
    parent.appendChild(button);

    //vlozi ho do databazy
    var xhttp = new XMLHttpRequest();
    var note_id = localStorage.getItem("note_id")

    console.log(note_id);

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            alert("bol pridany tag");
        }
    };
    xhttp.open("POST", "note_add_tag.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("cat_id=" + cat_id + "&note_id=" + note_id);

    //zavrieme modal

    document.querySelector(".overlay").style.display = "none";
}

//console.log(items);
/* list[i].addEventListener('click', () => {

     // Log values
     console.log(list[i].innerText);
 })*/

//https://developer.mozilla.org/en-US/docs/Web/API/Element/children

function attach_new_link(title, source) {

    //const url = encodeURI(note_source);
    var parent_notes = document.getElementById("notes");
    console.log(parent_notes);
    parent_notes.innerHTML += "<div class='note'><div class='note_title'>" + title + "</div><div class='note_text'>" + source + "</div><div class='note_footer'></div></div>";

}