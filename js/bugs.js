const bug_list = document.querySelector('.bug_list');
const modal_show_status = document.querySelector('.modal_show_status');
const modal_show_priority = document.querySelector('.modal_show_priority');


bug_list.addEventListener('click', function(event) {
    const targetClass = event.target.classList;

    if (event.target.tagName === 'DIV' && (targetClass.contains("bug_status") || targetClass.contains("bug_priority"))) {
        const bugId = event.target.closest(".bug").getAttribute('bug-id');
        sessionStorage.setItem('bug_id', bugId);
        console.log(bugId);

        const modal = targetClass.contains("bug_status") ? modal_show_status : modal_show_priority;
        
        if (!modal) return;

        const rect = event.target.getBoundingClientRect();
        
        // Posunutie o 10px doľava
        modal.style.left = `${rect.left + rect.width / 2 - modal.offsetWidth / 2 - 10}px`;
        modal.style.top = `${rect.top - modal.offsetHeight - 20}px`;

        modal.showModal();
    }
});


modal_show_status.addEventListener('click', function(event) {
if (event.target.tagName === 'LI') {    
    const bugId = sessionStorage.getItem('bug_id');
    const bugStatus = event.target.innerText;
    console.log(`Bug ${bugId} status updated to ${bugStatus}`);
    if(document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText === "fixed"){
        alert("Cannot change status of a fixed bug.");
        modal_show_status.close();
        return;
        
    } else {
    changeBugStatus(bugId, bugStatus);
    modal_show_status.close();
    }
}
});

modal_show_priority.addEventListener('click', function(event) {
    if (event.target.tagName === 'LI') {    
        const bugId = sessionStorage.getItem('bug_id');
        const bugPriority = event.target.innerText;
        console.log(`Bug ${bugId} status updated to ${bugPriority}`);
        if(document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText === "fixed"){
            alert("Cannot change priority of a fixed bug.");
            modal_show_priority.close();
            return;
            
        } else {
        changeBugPriority(bugId, bugPriority);
        modal_show_priority.close();
        }
    }
});


function changeBugStatus(bugId, bugStatus) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_status`).innerText = bugStatus;
           
        }
    };
    xhttp.open("POST", "bugs_change_status.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "bug_id=" + encodeURIComponent(bugId) + "&bug_status=" + encodeURIComponent(bugStatus);
    xhttp.send(params);
}

function changeBugPriority(bugId, bugPriority) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).innerText = bugPriority;
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).classList.remove("low", "medium", "high", "critical");
            document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).classList.add(bugPriority);
            //document.querySelector(`.bug[bug-id='${bugId}'] .bug_priority`).style.border = "1px solid #d1d1d1"; 
        }
    };
    xhttp.open("POST", "bugs_change_priority.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "bug_id=" + encodeURIComponent(bugId) + "&bug_priority=" + encodeURIComponent(bugPriority);
    xhttp.send(params);
}


function addNewComment(bugId) {
    document.queryselector('.modal_add_comment').showModal();
}