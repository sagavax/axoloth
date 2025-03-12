function date_validation(date1, date2) {
    date1 = document.getElementById("start_date").value;
    date2 = document.getElementById("end_date").value;
    if (date2 > date1) {
        alert("start date cannot be greater than end date!!");
    }

}