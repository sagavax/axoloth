const ideas_list = document.querySelector('.ideas_list');
const modal_show_status = document.querySelector('.modal_show_status');
const modal_show_priority = document.querySelector('.modal_show_priority');


ideas_list.addEventListener('click', function(event) {
    const targetClass = event.target.classList;

    if (event.target.tagName === 'DIV' && (targetClass.contains("idea_status") || targetClass.contains("idea_priority"))) {
        const ideaId = event.target.closest(".idea").getAttribute('idea-id');
        sessionStorage.setItem('idea_id', ideaId);
        console.log(ideaId);

        const modal = targetClass.contains("idea_status") ? modal_show_status : modal_show_priority;
        
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
    const ideaId = sessionStorage.getItem('idea_id');
    const ideastatus = event.target.innerText;
    console.log(`idea ${ideaId} status updated to ${ideastatus}`);
    if(document.querySelector(`.idea[idea-id='${ideaId}'] .idea_status`).innerText === "fixed"){
        alert("Cannot change status of a fixed idea.");
        modal_show_status.close();
        return;
        
    } else {
    changeideastatus(ideaId, ideastatus);
    modal_show_status.close();
    }
}
});

modal_show_priority.addEventListener('click', function(event) {
    if (event.target.tagName === 'LI') {    
        const ideaId = sessionStorage.getItem('idea_id');
        const ideaPriority = event.target.innerText;
        console.log(`idea ${ideaId} status updated to ${ideaPriority}`);
        if(document.querySelector(`.idea[idea-id='${ideaId}'] .idea_status`).innerText === "fixed"){
            alert("Cannot change priority of a fixed idea.");
            modal_show_priority.close();
            return;
            
        } else {
        changeideaPriority(ideaId, ideaPriority);
        modal_show_priority.close();
        }
    }
});


function changeideastatus(ideaId, ideastatus) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_status`).innerText = ideastatus;
           
        }
    };
    xhttp.open("POST", "ideas_change_status.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "idea_id=" + encodeURIComponent(ideaId) + "&idea_status=" + encodeURIComponent(ideastatus);
    xhttp.send(params);
}

function changeideaPriority(ideaId, ideaPriority) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        // Check if the request is complete and was successful
        if (this.readyState == 4 && this.status == 200) {
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).innerText = ideaPriority;
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).classList.remove("low", "medium", "high", "critical");
            document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).classList.add(ideaPriority);
            //document.querySelector(`.idea[idea-id='${ideaId}'] .idea_priority`).style.border = "1px solid #d1d1d1"; 
        }
    };
    xhttp.open("POST", "ideas_change_priority.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    // Send the request with the videoId and modpackId
    var params = "idea_id=" + encodeURIComponent(ideaId) + "&idea_priority=" + encodeURIComponent(ideaPriority);
    xhttp.send(params);
}


function addNewComment(ideaId) {
    document.queryselector('.modal_add_comment').showModal();
}