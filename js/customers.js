document.querySelector(".fab-container").addEventListener("click", function() {
    add_new_customer();
});

document.getElementById("hide_add_customer").addEventListener("click", function() {
    hide_add_customer();
})


document.querySelector("#add_customer").addEventListener("click", function() {
    var customer = document.getElementById("customer_name").value;
    if (customer === "") {
        alert("Cannot by empty");
    } else {
        //check if customer exists
        check_and_add_customer();
    }
})


document.getElementById("search_customer").addEventListener("keyup", function() {
    search_text = document.getElementById("search_customer").value;
    //console.log(search_text);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".customers").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "customer_search.php?text=" + encodeURIComponent(search_text), true);
    xhttp.send();
})


//console.log(document.querySelectorAll('input[type=checkbox]'));

document.querySelectorAll('input[type=checkbox]').forEach(item => {
    item.addEventListener('click', e => {
        //handle click

        var el = e.target;
        var cust_id = el.getAttribute("customer-id");
        console.log(cust_id);

        if (e.target.checked) {

            //set customer active
            //alert("checkbox is checked");
            var cust_status = 'active';

            //set_customer_status()
        } else {
            //alert("checkbox is not checked");

            //console.log(cust_id);
            var cust_status = 'inactive';

        }

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //reload_customer();
            }
        };
        xhttp.open("GET", "customer_status.php?cust_id=" + encodeURIComponent(cust_id) + "&cust_status=" + encodeURIComponent(cust_status), true);
        xhttp.send();

    })
});

// checks[i].addEventListener("click", function(e) {





function show_customers(status) {

}

function set_customer_status(customer_name, status) {

}

function reload_customers() {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(".customers").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "customers_reaload.php?", true);
    xhttp.send();
}

function add_new_customer() {
    el = document.querySelector(".overlay");
    el.style.display = (el.style.display == "flex") ? "none" : "flex";
}

function hide_add_customer() {
    document.querySelector(".overlay").style.display = "none";
}


function check_and_add_customer() {
    var customer = document.querySelector("#customer_name").value;
    var cust_description = document.querySelector("#customer_description").value;
    var cust_url = document.querySelector("#customer_url").value;

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if (this.responseText.trim() === "zakaznik_existuje") {
                document.getElementById("customer_name").value = "";
                alert("Zakaznik existuje");
            }
        }
    };
    xhttp.open("POST", "customer_add.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    console.log(customer);
    xhttp.send("cust_name=" + customer + "&cust_description=" + cust_description + "&cust_url=" + cust_url);
}
/*

https://stackoverflow.com/questions/1687296/what-is-dom-event-delegation
https://stackoverflow.com/questions/34896106/attach-event-to-dynamic-elements-in-javascript
https://flaviocopes.com/how-to-add-event-listener-multiple-elements-javascript/ */