const customBtn = document.getElementById("upload_note_from_file"); //tlacitko na ktory kliknem
const realFileBtn = document.getElementById("file-text"); //input typu file

window.addEventListener("load", () => {
    const loader = document.querySelector(".loader");

    loader.classList.add("loader--hidden");

    loader.addEventListener("transitionend", () => {
        document.body.removeChild(loader);
    });
});


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
    //zobrazime modal

    new_link = document.getElementById("add_new_link");
    new_link.style.display = (new_link.style.display == "flex") ? "none" : "flex";
}

function show_add_note() {
    //alert("normal note");
    window.location.href = "note_add.php";
}

function show_add_short_note() {
    document.getElementById("num_chars").innerHTML = "0";
    new_short_note = document.getElementById("add_new_short_note");
    new_short_note.style.display = (new_short_note.style.display == "flex") ? "none" : "flex";
}

function hide_add_short_note() {
    document.getElementById("short_note_title").value = "";
    document.getElementById("short_note_text").value = "";
    document.getElementById("num_chars").innerHTML = "0";
    new_short_note = document.getElementById("add_new_short_note");
    new_short_note.style.display = "none";
}

document.getElementById("add_link").addEventListener("click", () => {
        var xhttp = new XMLHttpRequest();
        var note_title = document.getElementById("note_title");
        var note_source = document.getElementById("note_source");
        var source = note_source.value;
        var title = note_title.value;

        console.log(source);
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //reload_note_categories()
                //alert("bol pridany link");
                new_link.style.display = "none";
                document.getElementById("note_title").value = "";
                document.getElementById("note_source").value = "";

                attach_new_link(title, source);

                //reload_notes();
            }
        }
        xhttp.open("POST", "note_add_link.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("note_title=" + encodeURIComponent(title) + "&note_source=" + encodeURIComponent(source));

        //zavrieme modal

    })
    //new_link.style.visibility = (new_link.style.display == "block") ? "none" : "block";


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

    //vytvori tag
    var button = document.createElement("button");
    //var li = document.createElement("li");
    button.classList.add("flat-btn");
    //link.href = "index.php?cat_id=" + cat_id;
    button.innerText = text;
    button.setAttribute("cat-id", cat_id);
    
    //prilepi novo vytvoreny tag do zoznamu tag    
    //const paernt = document.querySelectorAll('[data-foo="value"]');
    //get note id z localstoragu
    var note_id = localStorage.getItem("note_id");
    console.log(note_id);
    //najdi najblizsiu poznamku
    //https://stackoverflow.com/questions/17821560/loop-through-all-descendants-of-a-div-js-only
    var ancestor = document.querySelectorAll('[note-id="'+note_id+'"');
    var descendents = ancestor[0].getElementsByTagName('*');
    
    var i, e;
    for (i = 0; i < descendents.length; ++i) {
        e = descendents[i];
        console.log(e);
        if(e.className=="tag_list_wrap"){
            e.appendChild(button);
        }
    }
    //parent.appendChild(button);


    //vlozi ho do databazy
    var xhttp = new XMLHttpRequest();
    var note_id = localStorage.getItem("note_id")

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


//https://developer.mozilla.org/en-US/docs/Web/API/Element/children

function attach_new_link(title, source) {

    //const url = encodeURI(note_source);
    var parent_notes = document.getElementById("notes");

    //get latest note id

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("latest_id", this.responseText.trim());
        }
    };
    xhttp.open("GET", "get_latest_note_id.php", true);
    xhttp.send();

    //get customer name
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("customer_name", this.responseText.trim());
            var note_id = localStorage.getItem("latest_id");
            var customer_name = localStorage.getItem("customer_name");

            //attach created link
            parent_notes.insertAdjacentHTML('afterbegin', `<div class='note'><div class='note_title'><span>${title}</span></div><div class='note_text'>${linkify(source)}</div><div class='note_footer'><div class='note_action'><a href='link_edit.php?note_id=${note_id}' class='btn-small' title='edit link'><i class='fa fa-edit'></i></a><a href='note_delete.php?note_id=${note_id}' title='delete note' class='btn-small'><i class='fa fa-times'></i></a><a href='note_clone.php?note_id=${note_id}' title='clone note' class='btn-small'><i class='fa fa-clone'></i></a><a href='note_archive.php?note_id=${note_id}' title='archive note' class='btn-small'><i class='fa fa-archive'></i></a></div><div class='note_cat_wrap'><button title='Add new category' class='btn-small-hollow' note-id=\$note_id onclick='ShowModalAddCat(${note_id})'><i class='fa fa-plus'></i></button><button class='btn-small-green'>${customer_name}</button></div></div></div>`);

        }
    };
    xhttp.open("GET", "get_latest_note_customer.php", true);
    xhttp.send();

    //get lastest id and customer name

}

function linkify(inputText) {
    var replacedText, replacePattern1, replacePattern2, replacePattern3;

    //URLs starting with http://, https://, or ftp://
    replacePattern1 = /(\b(https?|ftp):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gim;
    replacedText = inputText.replace(replacePattern1, '<a href="$1" target="_blank">$1</a>');

    //URLs starting with "www." (without // before it, or it'd re-link the ones done above).
    replacePattern2 = /(^|[^\/])(www\.[\S]+(\b|$))/gim;
    replacedText = replacedText.replace(replacePattern2, '$1<a href="http://$2" target="_blank">$2</a>');

    //Change email addresses to mailto:: links.
    replacePattern3 = /(([a-zA-Z0-9\-\_\.])+@[a-zA-Z\_]+?(\.[a-zA-Z]{2,6})+)/gim;
    replacedText = replacedText.replace(replacePattern3, '<a href="mailto:$1">$1</a>');

    return replacedText;
}


function show_add_ext_image() {
    new_ext_image = document.getElementById("add_new_ext_image");
    new_ext_image.style.display = (new_ext_image.style.display == "flex") ? "none" : "flex";
}

function hide_ext_image() {
    new_ext_image = document.getElementById("add_new_ext_image");
    new_ext_image.style.display = "none";
}

document.getElementById("add_ext_pic").addEventListener("click", () => {
    var image_title = document.getElementById("image_title");
    var image_source = document.getElementById("image_source");
    var im_title = image_title.value;
    var im_source = image_source.value;

    //console.log(im_source, );
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            //alert("bol pridany link");
            attach_new_image(im_title, im_source);
            new_ext_image.style.display = "none";
        }
    };
    xhttp.open("POST", "picture_external_add.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("image_title=" + encodeURIComponent(im_title) + "&image_source=" + encodeURIComponent(im_source));

    //zavrieme modal
})

function attach_new_image(title, source) {

    //const url = encodeURI(note_source);
    var parent_notes = document.getElementById("notes");

    //get latest note id

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("latest_id", this.responseText.trim());
        }
    };
    xhttp.open("GET", "get_latest_note_id.php", true);
    xhttp.send();

    //get customer name
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("customer_name", this.responseText.trim());
            var note_id = localStorage.getItem("latest_id");
            var customer_name = localStorage.getItem("customer_name");

            //attach created link
            parent_notes.insertAdjacentHTML('afterbegin', `<div class='note'><div class='note_title'><span>${title}</span></div><div class='note_image'><img src='${(source)}'></div><div class='note_footer'><div class='note_action'><a href='picture_external_edit.php?note_id=${note_id}' class='btn-small' title='edit external image'><i class='fa fa-edit'></i><a href='note_delete.php?note_id=${note_id}' title='delete image' class='btn-small'><i class='fa fa-times'></i></a><a href='note_clone.php?note_id=${note_id}' title='clone note' class='btn-small'><i class='fa fa-clone'></i></a><a href='note_archive.php?note_id=${note_id}' title='archive note' class='btn-small'><i class='fa fa-archive'></i></a></div><div class='note_cat_wrap'><button title='Add new category' class='btn-small-hollow' note-id=\$note_id onclick='ShowModalAddCat(${note_id})'><i class='fa fa-plus'></i></button><button class='btn-small-green'>${customer_name}</button></div></div></div>`);

        }
    };
    xhttp.open("GET", "get_latest_note_customer.php", true);
    xhttp.send();

}

var note_text = document.getElementById("short_note_text").value;
//console.log(document.getElementById("short_note_text"));
document.getElementById("short_note_text").addEventListener("change", () => countWords(note_text));

document.getElementById("add_short_note").addEventListener("click", () => {
    note_title = document.getElementById("short_note_title").value;
    note_text = document.getElementById("short_note_text").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            //alert("bol pridany link");
            attach_new_short_note(note_title, note_text);
            document.getElementById("add_new_short_note").style.display = "none";
            document.getElementById("short_note_text").value = "";
            document.getElementById("short_note_title").value = "";
        }
    };
    xhttp.open("POST", "note_short_add.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_title=" + encodeURIComponent(note_title) + "&note_text=" + encodeURIComponent(note_text));

})


function attach_new_short_note(title, note_text) {

    //const url = encodeURI(note_source);
    var parent_notes = document.getElementById("notes");

    //get latest note id

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("latest_id", this.responseText.trim());
        }
    };
    xhttp.open("GET", "get_latest_note_id.php", true);
    xhttp.send();

    //get customer name
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("customer_name", this.responseText.trim());
            var note_id = localStorage.getItem("latest_id");
            var customer_name = localStorage.getItem("customer_name");

            //attach created link
            parent_notes.insertAdjacentHTML('afterbegin', `<div class='note'>
                <div class='note_title'>${title}</div>
                <div class='note_text'>${note_text}</div>
                <div class='note_footer'>
                    <div class='note_action'>
                        <a href='note.php?note_id=${note_id}' class='btn-small' title='view note'><i class='fa fa-eye'></i></a>
                        <a href='note_edit.php?note_id=${note_id}' class='btn-small' title='edit note'><i class='fa fa-edit'></i></a>
                        <a href='note_delete.php?note_id=${note_id}' title='delete note' class='btn-small'><i class='fa fa-times'></i></a>
                        <a href='note_clone.php?note_id=${note_id}' title='clone note' class='btn-small'><i class='fa fa-clone'></i></a>
                        <a href='note_archive.php?note_id=${note_id}' title='archive note' class='btn-small'><i class='fa fa-archive'></i></a>
                        <a href='note_attachment.php?note_id=${note_id}' title='attach a file' class='btn-small'><i class='fas fa-paperclip'></i></a>
                    </div>
                    <div class='note_cat_wrap'>
                        <button title='Add new category' class='btn-small-hollow' note-id=$note_id onclick='ShowModalAddCat(${note_id})'><i class='fa fa-plus'></i></button>
                        <button class='btn-small-green'>${customer_name}</button>
                    </div>
                </div>
            </div>`);

        }
    };
    xhttp.open("GET", "get_latest_note_customer.php", true);
    xhttp.send();

    //get lastest id and customer name

}

function countWords(str) {
    str = str.replace(/(^\s*)|(\s*$)/gi, "");
    str = str.replace(/[ ]{2,}/gi, " ");
    str = str.replace(/\n /, "\n");
    return str.split(' ').length;
}

var button_cat = document.getElementById("submit_new_category");
button_cat.addEventListener("click", function() {
    var new_category_name = document.getElementById("new_category_name").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            //alert("bol pridany link");
            attach_new_category(new_category_name);

            document.getElementById("new_category_name").value = "";

        }
    };
    xhttp.open("POST", "category_add.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("category_name=" + encodeURIComponent(new_category_name));
})


function attach_new_category(category_name) {

    //const url = encodeURI(note_source);
    var parent_category = document.getElementById("category_list");

    //get latest cat id

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("latest_cat_id", this.responseText.trim());
            var latest_id = localStorage.getItem("latest_cat_id");
            parent_category.insertAdjacentHTML('afterbegin', "<li category-id=" + latest_id + ">" + category_name + "<i class='fa fa-times' onclick='delete_category(this);'></i></li>");
        }
    };
    xhttp.open("GET", "get_latest_category_id.php", true);
    xhttp.send();
}

function number_of_chars() {
    const length = document.getElementById("short_note_text").value.length;
    document.getElementById("num_chars").innerHTML = parseInt(length);
    //%
    var ratio = Math.floor((parseInt(length) / 800) * 100);
    console.log(ratio + "%");
    /* circular progress bar
    https://youtu.be/Ft73g5Kyknw
    https://youtu.be/_s0rqUkQkrQ */
}

function add_image_title(id){
        
    var note_title_wrap = document.getElementById("note-title-"+id);
    note_title_wrap.classList.toggle("hidden");
   
 
}

function save_note_title(id){
        //alert(id);
         var title_input = document.getElementById("input-note-id-"+id);  
        //console.log(title_input);
        var note_title = title_input.value;

      var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           
            var note_title_wrap = document.getElementById("note-title-"+id);
            note_title_wrap.style.visibility = "hidden";
            //document.getElementById("input-title-"+id).value = "";
           
            //remove wrap from note title
            var wrap = document.getElementById("note-title-"+id);
            var parent_wrap = wrap.parentNode;
            while (parent_wrap.firstChild) {
                parent_wrap.removeChild(parent_wrap.firstChild);
            }

            parent_wrap.innerHTML="<span>"+note_title+"</span>";
                      
            //reload note title
            //load_note_title(id);
        }
    };
    xhttp.open("POST", "update_note_title.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_title=" + encodeURIComponent(note_title) + "&note_id=" + encodeURIComponent(id));
}

function load_note_title(id){
      var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector("note_title").innerHTML=this.responseText;
     } 
    };       

      xhttp.open("POST", "reload_note_title.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("&note_id=" + encodeURIComponent(id));
}