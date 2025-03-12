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