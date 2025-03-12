const passwords = document.querySelector("#passwords");

passwords.addEventListener("click", (event)=>{
    if(event.target.tagName==="BUTTON"){
        alert("Click!");
    }
})



var mobile_menus = document.querySelectorAll(".mobile_menu");
//console.log(mobile_menus);
var i;
for(i=0;i< mobile_menus.length;i++){
   var mobile_menu = mobile_menus[i];
    mobile_menu.addEventListener("click",toggleMenu,false);
}





/*const someInput = document.querySelector('button');
someInput.addEventListener('click', myFunc, false);
someInput.myParam = 'This is my parameter';
function myFunc(evt)
{
  window.alert(evt.currentTarget.myParam);
}*/




function toggleMenu_old(evt) {
    //evt.stopPropagation();
    //alert();
  var menuBox = document.querySelectorAll('.menu-box');
 console.log(menuBox);   
 for(var i=0; i<menuBox.length;i++){
  if(menuBox[i].style.display == "block") { // if is menuBox displayed, hide it
    menuBox[i].style.display = "none";
  }
  else { // if is menuBox hidden, display it
    menuBox[i].style.display = "block";
    }
  }
}

function toggleMenu(){
    var parent = document.createElement("ul");
 }   



function AddFavorite(PassID) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("fav-" + PassID).innerHTML =
                "<a href='#' onclick='RemoveFavorite(" + PassID + ");'><i class='fas fa-star'></i></a>";

        }
    };
    xhttp.open("GET", "add_favorites.php?id=" + PassID, true);
    xhttp.send();
}

function RemoveFavorite(PassID) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("fav-" + PassID).innerHTML =
                "<a href='#' onclick='AddFavorite(" + PassID + ");'><i class='far fa-star'></i></a>";

        }
    };
    xhttp.open("GET", "remove_favorites.php?id=" + PassID, true);
    xhttp.send();
}

function show_pass_details(PassID) {
    el = document.getElementById("show_pass_details");
    el.style.display = (el.style.display == "flex") ? "none" : "flex";

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pass_details").innerHTML =
                this.responseText;

        }
    };
    xhttp.open("GET", "password.php?id=" + PassID, true);
    xhttp.send();
}


function hide_password() {
    document.getElementById("show_pass_details").style.display = "none";
}

function show_as_list() {

}

function show_as_grid() {

}

const search_box = document.getElementById("search_password");
search_box.addEventListener("keyup", function() {
    search_text = document.getElementById("search_password").value;
    console.log(search_text);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("pass_maintable").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "password_search.php?text=" + encodeURIComponent(search_text), true);
    xhttp.send();
})

document.addEventListener("DOMContentLoaded", () => {
    const rows = document.querySelectorAll("td[data-href]");

    rows.forEach(row => {
        row.addEventListener("click", () => {
            //add hit ;
            window.open(row.dataset.href);
        });
    });
});


/* const pass_card = document.querySelectorAll(".pass_card");
for (i = 0; i < pass_card.length; i++) {
    pass_card[i].addEventListener("click", function() {
        alert(this.getAttribute("data-href"));
    })
}*/


function reload_passwords() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("passwords").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "reload_passwords.php", true);
    xhttp.send();
}

function get_newest() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("passwords").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "password_newest.php", true);
    xhttp.send();
}

function get_most_popular() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("passwords").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "password_most_popular.php", true);
    xhttp.send();
}

function get_favorites() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("passwords").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "password_favorites.php", true);
    xhttp.send();
}

function get_archived() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("passwords").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "password_archived.php", true);
    xhttp.send();
}

const tabs = document.querySelectorAll(".tab");
for (i = 0; i < tabs.length; i++) {
    tabs[i].addEventListener("click", function(e) {
        tab = e.target.innerHTML;
        console.log(tab);
        if (tab == "All") {
            //console.log("all passwords");
            reload_passwords();
        } else if (tab == "Favorites") {
            //console.log("favorites");
            get_favorites();
        } else if (tab == "Most popular") {
            //console.log("most popular");
            get_most_popular();
        } else if (tab == "Newest") {
            // console.log("newest");
            get_newest();
        }
    })
}


const keys = {
    upperCase: "ABCDEFGHIJKLMNOPQRSTUVWXYZ",
    lowerCase: "abcdefghijklmnopqrstuvwxyz",
    number: "0123456789",
    symbol: "!@#$%^&*()_+~\`|}{[]:;?><,./-="
}
const getKey = [
    function upperCase() {
        return keys.upperCase[Math.floor(Math.random() * keys.upperCase.length)];
    },
    function lowerCase() {
        return keys.lowerCase[Math.floor(Math.random() * keys.lowerCase.length)];
    },
    function number() {
        return keys.number[Math.floor(Math.random() * keys.number.length)];
    },
    function symbol() {
        return keys.symbol[Math.floor(Math.random() * keys.symbol.length)];
    }
];


function show_hide_note() {
    var new_pass_note = document.getElementById("pass_note_text");
    new_pass_note.style.display = (new_pass_note.style.display == "flex") ? "none" : "flex";
}

function show_add_note(id){
    //alert(id);
    sessionStorage.setItem("pass-id",id);
   var new_pass_note = document.querySelector(".show_add_note");
   new_pass_note.style.display = (new_pass_note.style.display == "flex") ? "none" : "flex";
}

function hide_add_note(){
    var new_pass_note = document.querySelector(".show_add_note");
    new_pass_note.style.display = "none";
}

function view_pass(id){
    window.location.href='password.php?id='+id;
    //alert(id);
}

function edit_pass(id){
    window.location.href='password_edit.php?id='+id;
}


function archive_pass(id){
      var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert('the password with id has been archived sucessfully')
        }
     }   
    xhttp.open("POST", "password_archive.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id=" + id);
}


function clone_pass(id){
       var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert('the password with id has been cloned sucessfully');
            console.log(this.responseText);
            window.location.href="password_edit.php?id="+this.responseText.trim();
        }
    }
    xhttp.open("POST", "password_clone.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id=" + id);
}

function remove_pass(id){
       var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            alert('the password with id has been deleted sucessfully')
        }
    }
    xhttp.open("POST", "password_delete.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("id=" + id);
}