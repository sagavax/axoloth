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

document.querySelector('input[type=checkbox]').addEventListener("click", function(e) {
    var el = e.target;
    var cust_id = el.getAttribute("customer-id");

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




function show_customers(status) {

}

function new_customer(customer_name) {

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