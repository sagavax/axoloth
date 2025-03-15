var tasks = document.getElementById("tasks");

const slider = document.getElementById("slider");
const slider_value = document.getElementById("slider_value");
const display_as = document.getElementById("display_as");
const dialog_new_task = document.querySelector(".dialog_new_task");
var project_progress = document.querySelector(".project_progress");

dialog_new_task.addEventListener("show", function(event) {
    alert("New task");
});

dialog_new_task.addEventListener("click", function(event) {
    if (event.target.tagName === "BUTTON" && event.target.name === "create_task") {
        alert("Adding new task");
         //createNewTask();
    }
    });


    //when hide the progress bar dialog update the progrss bar
    project_progress.addEventListener("cancel", function(event) {
    const taskId=sessionStorage.getItem('task_id');
    const  progressValueFromStorage = sessionStorage.getItem('task_progress');
    const progressValue = parseFloat(progressValueFromStorage);
    document.querySelector(`.task[data-task-id='${taskId}'] progress`).value = progressValue;
    console.log(`Task ${taskId} progress updated to ${progressValue}`);
    //alert("Hiding new task: "+taskId);
})




if (display_as) {
    display_as.addEventListener("click", function(event) {
        if (event.target.tagName === "BUTTON") {
            switch (event.target.name) {
                case "display_as_list":
                    alert("display as list");
                    break;
                case "display_as_group":
                    alert("display as group");
                    break;
                case "display_as_scrum":
                    alert("display as scrum");
                    break;
                case "add_new_task":
                    alert("add new task");
                    document.querySelector(".dialog_new_task")?.showModal();
                    break;
            }
        }
    });
}



tasks.addEventListener("click", function(event) {
    if(event.target.tagName === "PROGRESS") {
        //alert("You clicked on a progress bar");
        var taskId = event.target.closest(".task").getAttribute("data-task-id");
        sessionStorage.setItem('task_id', taskId);
        console.log(taskId);
        var slide_value = document.getElementById("slide_value");
        // Open the modal for updating progress
        
        project_progress.showModal();
        if(project_progress){
            slide_value.innerHTML = event.target.value;
        //var project_progress = document.querySelector(".project_progress");
        project_progress.showModal();
        if(project_progress){
            slide_value.innerHTML = slider.value;
            sessionStorage.setItem("task_progress",slider.value); 

            // Update the current slider value (each time you drag the slider handle)
            slider.oninput = function() {
                slide_value.innerHTML = slider.value;
                updateProgress(taskId, slider.value);
            }
        }
    }
   }
});

/* document.querySelector(".new_task").addEventListener("click", function() {
    window.location.href = "task_add.php";
})
 */
document.getElementById("search_task").addEventListener("keyup", function() {
    search_text = document.getElementById("search_task").value;
    console.log(search_text);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("tasks").innerHTML =
                this.responseText;
        }
    };
    xhttp.open("GET", "task_search.php?text=" + encodeURIComponent(search_text), true);
    xhttp.send();
})

function ChangeStatus(TaskID, MinTaskID) {
    var xhttp = new XMLHttpRequest();
    var id = TaskID;
    var min_task_id = MinTaskID;
    var e = document.getElementById("task_status-" + TaskID);
    var status = e.options[e.selectedIndex].value;
    if(status==="Completed") {
        alert("Task is completed, can't change status to completed");
        return;
    } else{
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
    xhttp.open("POST", "task_status.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = "id=" + id + "&status=" + status
    xhttp.send(data);
    }
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
    xhttp.open("POST", "task_note_remove.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_id=" + task_id);
}

function updateProgress(taskId, progress) {
    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "task_update_progress.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("task_id=" + taskId + "&progress=" + progress);
}

function getProgress(taskId){
    var xhttp = new XMLHttpRequest();
    xhttp.open("GET", "task_get_current_progress.php?task_id=" + taskId, true);
    xhttp.send();
    return xhttp.responseText;
}

function createNewTask() {
    var task_text = document.querySelector('input[name="new_task_text"]').value;
    var task_priority = document.querySelector('select[name="new_task_priority"]').value;
    var task_status = document.querySelector('select[name="new_task_status"]').value;
    var task_date= document.querySelector('input[name="start_task_date"]').value;

    var xhttp = new XMLHttpRequest();
    xhttp.open("POST", "task_create.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    var data = "task_text=" + task_text + "&task_priority=" + task_priority + "&task_status=" + task_status + "&task_date=" + task_date;
    xhttp.send(data);
}

function animateProgress(currValue, targetValue) {
    if (currValue < targetValue) { 
        for (var i = currValue; i < targetValue; i++) {
            console.log("Progress increasing:", i);
        }
    } else if (currValue > targetValue) {
        for (var i = currValue; i > targetValue; i--) {
            console.log("Progress decreasing:", i);
        }
    }
}
