function ChangeStatus(TaskID, MinTaskID) {
    var xhttp = new XMLHttpRequest();
    var id = TaskID;
    var min_task_id = MinTaskID;
    var e = document.getElementById("task_status-" + TaskID);
    var status = e.options[e.selectedIndex].value;;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("task_status-" + id).innerHTML =
                "<option value='" + status + "' selected='selected'>" + status + "</option>";
            message = "The status for the task with id " + id + " has been changed sucessfully";
            alert(message);
            window.location = 'index.php?display_as=list'
        }
    };
    //xhttp.open("GET", "task_status.php?id="+id+"&min_task_id+"+min_task_id+"&status="+status, true);
    xhttp.open("GET", "task_status.php?id=" + id + "&status=" + status, true);
    xhttp.send();

}

function ChangePriority(TaskID) {
    var xhttp = new XMLHttpRequest();
    var id = TaskID;
    var e = document.getElementById("task_priority-" + TaskID);
    var priority = e.options[e.selectedIndex].value;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("task_priority-" + id).innerHTML =
                "<option value='" + priority + "' selected='selected'>" + priority + "</option>";
            message = "The priority for the task with id " + id + " has been changed sucessfully";
            alert(message);
            window.location = 'index.php?display_as=list'
        }
    };
    xhttp.open("GET", "task_priority.php?id=" + id + "&priority=" + priority, true);
    xhttp.send();

}

function FilterPriority(priority) {
    var xhttp = new XMLHttpRequest();
    var e = document.getElementById("filter_task_priority");
    //var e = document.getElementById("task_priority-"+TaskID);
    var priority = e.options[e.selectedIndex].value;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("table_tasks_wrap").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "filter_priority.php?priority=" + priority, true);
    xhttp.send();

}

function FilterStatus(status) {
    var xhttp = new XMLHttpRequest();
    var e = document.getElementById("filter_task_status");
    var task_status = e.options[e.selectedIndex].value;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("table_tasks_wrap").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "filter_status.php?task_status=" + task_status, true);
    xhttp.send();

}

function FilterCategory(category) {
    var xhttp = new XMLHttpRequest();
    var e = document.getElementById("filter_task_category");
    var status = e.options[e.selectedIndex].value;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("table_tasks_wrap").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "filter_category.php?category=" + category, true);
    xhttp.send();

}

function FilterCustomers(cust_id) {
    var xhttp = new XMLHttpRequest();
    var e = document.getElementById("filter_task_customers");
    var cust_id = e.options[e.selectedIndex].value;
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("table_tasks_wrap").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "filter_customer.php?customer=" + cust_id, true);
    xhttp.send();

}

function SyncTask(task_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "task_sync.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_id=" + task_id);
}


function remove_task_note(task_note_id) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "remove_task_note.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_id=" + task_id);
}