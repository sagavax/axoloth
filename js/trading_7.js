document.querySelector(".trading_assets").style.display = "none";
document.querySelector(".trading_notes").style.display = "none";

// show/hide modal for a new trading platform
document.getElementById("modal_add_platform").addEventListener("click", function() {
    var el = document.querySelector(".new_trading_platform")
    el.style.display = (el.style.display == 'none' || el.style.display == '') ? 'flex' : 'none';
})




// show/hide modal add new asset
document.getElementById("modal_add_asset").addEventListener("click", function() {
    var el = document.querySelector(".new_asset_platform")
    el.style.display = (el.style.display == 'none' || el.style.display == '') ? 'flex' : 'none';
})


// show/hide modal add new note
var add_platf_notes = document.querySelectorAll(".add_platf_note");
for (var i = 0; i < add_platf_notes.length; i++) {
    add_platf_notes[i].addEventListener("click", function() {
        var platf_id = this.parentNode.parentNode.id;
        localStorage.setItem("note_platf_id", platf_id)
        var el = document.querySelector(".platform_note")
        el.style.display = (el.style.display == 'none' || el.style.display == '') ? 'flex' : 'none';
    })
}

// show/hide modal add new asset
var add_platf_assets = document.querySelectorAll(".add_platf_asset");
for (var i = 0; i < add_platf_assets.length; i++) {
    add_platf_assets[i].addEventListener("click", function() {
        var platf_id = this.parentNode.parentNode.id;
        localStorage.setItem("platform_id", platf_id)
        var el = document.querySelector(".platform_asset")
        el.style.display = (el.style.display == 'none' || el.style.display == '') ? 'flex' : 'none';
    })
}

//delete platform
var delete_platf = document.querySelectorAll(".remove_platform");
for (var i = 0; i < delete_platf.length; i++) {
    delete_platf[i].addEventListener("click", function() {
        var platf_id = this.parentNode.parentNode.id;
        localStorage.setItem("platf_id", platf_id)
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(new_platform);
                reload_platforms();
            }
        };
        platform_id = localStorage.getItem("note_platf_id");
        xhttp.open("GET", "platform_remove.php?platform_id=" + platform_id, true);
        xhttp.send();
    })
}

document.getElementById("hide_update_note").addEventListener("click", function() {
    document.querySelector(".edit_platform_note").style.display = "none";
})



//tabs
var parent = document.getElementById("tabs");
var tab_item = parent.children;

for (var i = 0; i < tab_item.length; i++) {
    tab_item[i].style.cursor = "pointer";
    tab_item[i].addEventListener("click", function() {
        var active_tab = this.getAttribute("tab-data");
        if (active_tab === "platforms") {
            document.querySelector(".trading_platforms").style.display = "block";
            document.querySelector(".trading_assets").style.display = "none";
            document.querySelector(".trading_notes").style.display = "none";
        } else if (active_tab === "assets") {
            document.querySelector(".trading_platforms").style.display = "none";
            document.querySelector(".trading_assets").style.display = "block";
            document.querySelector(".trading_notes").style.display = "none";
        } else if (active_tab === "notes") {
            document.querySelector(".trading_platforms").style.display = "none";
            document.querySelector(".trading_assets").style.display = "none";
            document.querySelector(".trading_notes").style.display = "block";
        }
    })
}


//adding of a new platform
document.getElementById("add_platform").addEventListener("click", function() {
    var new_platform = document.getElementById("platform_name").value
    var logo = document.getElementById("platform_logo").value
    console.log(logo);

    if (new_platform === "") {
        alert('Cannot be empty!!!');
    } else {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //alert(new_platform);
                document.querySelector(".new_trading_platform").style.display = "none";
                document.getElementById("platform_name").value = "";
                document.getElementById("platform_logo").value = "";
                reload_platforms();

            }
        };
        xhttp.open("POST", "platform_add.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("platform=" + new_platform + "&logo=" + logo);
    }
})




//adding of a new asset
document.getElementById("add_asset").addEventListener("click", function() {
    var asset_name = document.getElementById("asset_name").value;
    var asset_symbol = document.getElementById("asset_symbol").value;
    var asset_url = document.getElementById("asset_url").value;
    var asset_type = document.getElementById("asset_type").value
    var asset_description = document.getElementById("asset_description").value

    if (asset_name === "") {
        alert('Cannot be empty!!!');
    } else {
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.querySelector(".new_asset_platform").style.display = "none";
                var inputs = document.querySelector("input");
                for (var i = 0; i < inputs.length; i++) {
                    inputs[i].value = "";
                }
                document.getElementById("asset_description").value = "";
                document.getElementById("asset_name").value = "";
                document.getElementById("asset_symbol").value = "";
                document.getElementById("asset_url").value = "";
                document.getElementById("asset_type").value = "";

                reload_assets();
            }
        };

        xhttp.open("POST", "asset_add.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhttp.send("asset_name=" + encodeURIComponent(asset_name) + "&asset_symbol=" + encodeURIComponent(asset_symbol) + "&asset_url=" + encodeURIComponent(asset_url) + "&asset_type=" + encodeURIComponent(asset_type) + "&asset_description=" + encodeURIComponent(asset_description));
    }
})

document.getElementById("assign_asset").addEventListener("click", function() {
    var asset = document.getElementById("asset").value;
    var asset_amount = document.getElementById("asset_amount").value;
    var asset_note = document.getElementById("asset_note").value

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".platform_asset").style.display = "none";

            document.getElementById("asset_amount").value = "";
            document.getElementById("asset_note").value = "";

            reload_assets();
        }
    };
    var platform_id = localStorage.getItem("platform_id");
    xhttp.open("POST", "platform_asset_add.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("asset_symbol=" + encodeURIComponent(asset) + "&asset_amount=" + encodeURIComponent(asset_amount) + "&asset_note=" + encodeURIComponent(asset_note) + "&platform_id=" + encodeURIComponent(platform_id));

})


//add new note

document.getElementById("add_note").addEventListener("click", function() {
    //get note title
    var note_title = document.getElementById("note_title").value;
    //get note text
    var note_text = document.getElementById("note_text").value;
    //get platform id
    var platf_id = localStorage.getItem("note_platf_id");

    if (note_text === "") {
        alert('Cannot be empty!!!');
    } else {
        //find relevant plattform

        //console.log(parent);
        //attach created note 

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //get note id
                var xhttd = new XMLHttpRequest();
                xhttd.onreadystatechange = function() {
                        localStorage.setItem("latest_id", this.responseText.trim());
                        let note_id = localStorage.getItem('latest_id');
                        console.log("latest note is" + note_id);
                        const parent = document.querySelector('[platform-id="' + platf_id + '"]');
                        parent.insertAdjacentHTML("afterbegin",
                            `<div class='note' note-id=` + note_id + `>
                        <div class='note_text'>` + note_text + `</div>
                        <div class='note_action'><button type='button' class='btn-small note_edit'><i class='fa fa-edit'></i></button><button type='button' class='btn-small note_delete'><i class='fa fa-times'></i></button></div></div>`);

                        document.getElementById("note_title").value = "";
                        document.getElementById("note_text").value = "";
                        document.querySelector(".platform_note").style.display = "none";
                        //console.log(this.responseText.trim());
                    }
                    //console.log(platf_id);
                xhttd.open("GET", "get_note_id.php?platforn_id=" + platf_id, true);
                xhttd.send();
                //attach a note
                //const latest_note = localStorage.getItem("latest_id");

            }
        };
        //wript note into the database.
        xhttp.open("POST", "note_add.php", true);
        xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        let platform_id = localStorage.getItem("note_platf_id");
        data = "note_title=" + note_title + "&note_text=" + encodeURIComponent(note_text) + "&platform_id=" + platform_id;
        xhttp.send(data);
    }
})


// hide modal modal new trading platform
document.getElementById("hide_platform").addEventListener("click", function() {
    var el = document.querySelector(".new_trading_platform")
    el.style.display = "none";
});

// hide modal modal new trading asset
document.getElementById("hide_asset").addEventListener("click", function() {
    var el = document.querySelector(".new_asset_platform")
    el.style.display = "none";
});

//hide platform note modal
document.getElementById("hide_note").addEventListener("click", function() {
    var el = document.querySelector(".platform_note")
    el.style.display = "none";
});

document.getElementById("hide_modal_asset").addEventListener("click", function() {
    var el = document.querySelector(".platform_asset")
    el.style.display = "none";
});

// reloads list of all trading platforms
function reload_platforms() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".trading_platforms").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_platforms.php", true);
    xhttp.send();
}

// reloads list of all trading assets
function reload_assets() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".trading_assets").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_assets.php", true);
    xhttp.send();
}

function reload_platf_notes(platform_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".trading_feeds").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_notes.php?platform_id=" + platform_id, true);
    xhttp.send();
}

function reload_all_notes() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".trading_assets").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_notes.php?platform_id=" + platform_id, true);
    xhttp.send();
}

//displays action menu
var menus = document.querySelectorAll(".action_menu");
for (var i = 0; i < menus.length; i++) {
    //console.log(menus[i]);
    menus[i].addEventListener("contextmenu", function(e) {

        //alert("Click");
        var el = document.querySelector(".context_menu");
        el.style.display = (el.style.display == 'none' || el.style.display == '') ? 'flex' : 'none';
        e.preventDefault();
        el.style.left = e.x + 'px';
        el.style.top = e.y + 'px';
    })
}


//searching within trading platforms
document.getElementById("search_platform").addEventListener("keyup", function() {
    var search_asset = document.getElementById("search_platform").value;
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".platforms").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "search_platform.php?platform=" + encodeURIComponent(search_asset), true);
    xhttp.send();
})


/*var parent = document.querySelectorAll(".trading_feeds");
for (var i = 0; i < parent.length; i++) {
    var del_note_b = parent[i].getElementsByClassName("note_delete"); //all delete note buttons
    for (var y = 0; y < del_note_b.length; y++) {
        del_note_b[y].addEventListener("click", function() {
            //alert("bla");
            var note_id = this.parentElement.parentElement.getAttribute("note-id");
            console.log(note_id);
            var platform_id = this.parentElement.parentElement.getAttribute("platform-id");
            console.log(platform_id);
            //parent[i].removeChild(this.parentElement.parentElement);
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function() {
                reload_platf_notes(platform_id)
            };

            xhttp.open("POST", "note_remove.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            data = "note_id=" + note_id;
            xhttp.send(data);
        })
    }
}*/

var parents = document.querySelectorAll(".trading_feeds");
for (var i = 0; i < parents.length; i++) { //divs with class trading_feeds
    //console.log(parents[i]);

    parents[i].addEventListener("click", function(e) {
        //console.log(e.target.classList);
        if (e.target.classList.contains("note_delete") === true || e.target.classList.contains("fa-times") === true) {
            //alert("delete note");
            //console.log(e.target.closest(".note").getAttribute("note-id"));
            var note_id = e.target.closest(".note").getAttribute("note-id");
            delete_platform_note(note_id);
        }

        if (e.target.classList.contains("note_edit") === true || e.target.classList.contains("fa-edit") === true) {
            //console.log(e.target.closest(".note").getAttribute("note-id"));
            var note_id = e.target.closest(".note").getAttribute("note-id");
            edit_platform_note(note_id);
        }

    })
}

function attach_note() {
    var parent = document.queryselector(".trading_feeds ");
    parent.insertAdjacentHTML("afterbegin", "");
}


/*const logo_wraps = document.querySelectorAll(".platform_logo");
console.log(logo_wraps);
for (var i = 0; i < logo_wraps.length; i++) {
    logo_wraps[i].addEventListener("click", function() {
        //get platform url
        var platf_id = this.parentNode.id;
        get_platform_url(this.parentNode.id);
        var platf_url = localStorage.getItem("platform_url");
        console.log(platf_url);
        if (platf_url) {
            //window.location.href = platf_url;
            //localStorage.clear();
        }
    })
}*/

const logo_images = document.querySelectorAll(".platform_logo img");
for (var i = 0; i < logo_images.length; i++) {
    logo_images[i].addEventListener("click", function() {
        console.log(this.parentNode.parentNode.id);
        var platf_url = this.parentNode.parentNode.getAttribute("platform-url");
        //get_platform_url(this.parentNode.parentNode.id);

        if (platf_url) {
            //alert(platf_url);
            window.location.href = platf_url;
            //localStorage.clear();

        }
    })
}


function get_platform_url(platf_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        /*     if (this.readyState == 4 && this.status == 200) {
                 var platf_url = localStorage.getItem("platform_url");
                 console.log(platf_url);*/
    }

    xhttp.open("GET", "get_platform_url.php?platform=" + platf_id, true);
    xhttp.send();
}


/*https://www.codegrepper.com/code-examples/javascript/javascript+count+number+of+words+in+a+string
https://www.tutorialspoint.com/how-to-count-a-number-of-words-in-given-string-in-javascript
https://www.google.com/search?q=javascript+determne+word+in+a+text&oq=javascript+determne+word+in+a+text&aqs=edge..69i57.23199j0j1&sourceid=chrome&ie=UTF-8 
*/

function delete_platform_note(id) {
    console.log(id);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //alert(new_platform);
            //document.querySelector(".new_trading_platform").style.display = "none";
            //document.getElementById("platform_name").value = "";
            //document.getElementById("platform_logo").value = "";
            //reload_platforms();

        }
    };
    xhttp.open("POST", "delete_platform_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("note_id" + id);
}


function detele_asset(asset_id) {

}

function edit_platform_note(id) {
    console.log(id);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".edit_platform_note").style.display = "flex";
            document.getElementById("edit_note_text").value = this.responseText.trim();
        }
    };
    xhttp.open("GET", "load_platform_note?note_id=" + id, true);
    xhttp.send();
}