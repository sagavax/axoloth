function show_add_video() {
    new_short_note = document.getElementById("modal_add_new_video");
    new_short_note.style.display = (new_short_note.style.display == "flex") ? "none" : "flex";
}

function hide_add_video() {
    document.getElementById("modal_add_new_video").style.display = "none";
}

function attach_new_video(title, source) {

    //const url = encodeURI(note_source);
    var parent_notes = document.getElementById("notes");

    //get latest note id

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("latest_id", this.responseText.trim());
        }
    };
    xhttp.open("GET", "get_latest_note_id.php", true);
    xhttp.send();

    //get customer name
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            localStorage.setItem("customer_name", this.responseText.trim());
            var note_id = localStorage.getItem("latest_id");
            var customer_name = localStorage.getItem("customer_name");

            //attach created link
            parent_notes.insertAdjacentHTML('afterbegin', `<div class='note'><div class='note_title'>${title}</div><div class='note_video'>${linkify(source)}</div><div class='note_footer'><div class='note_action'><a href='picture_external_edit.php?note_id=${note_id}' class='btn-small' title='edit external image'><i class='fa fa-edit'></i><a href='note_delete.php?note_id=${note_id}' title='delete image' class='btn-small'><i class='fa fa-times'></i></a><a href='note_clone.php?note_id=${note_id}' title='clone note' class='btn-small'><i class='fa fa-clone'></i></a><a href='note_archive.php?note_id=${note_id}' title='archive note' class='btn-small'><i class='fa fa-archive'></i></a></div><div class='note_cat_wrap'><button title='Add new category' class='btn-small-hollow' note-id=\$note_id onclick='ShowModalAddCat(${note_id})'><i class='fa fa-plus'></i></button><button class='btn-small-green'>${customer_name}</button></div></div></div>`);

        }
    };
    xhttp.open("GET", "get_latest_note_customer.php", true);
    xhttp.send();

    //get lastest id and customer name

}

document.getElementById("btnAddvideo").addEventListener("click", () => {
    var video_title = document.getElementById("video_title");
    var video_source = document.getElementById("video_source");
    var vid_title = video_title.value;
    var vid_source = video_source.value;


    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //reload_note_categories()
            //alert("bol pridany link");
            attach_new_video(vid_title, vid_source);
            document.getElementById("modal_add_new_video").style.display = "none";
        }
    };
    xhttp.open("POST", "video_add.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("video_title=" + encodeURIComponent(vid_title) + "&video_source=" + encodeURIComponent(vid_source));

    //zavrieme modal
})

function embded_video(video_source) {
    //var url = "https://www.youtube.com/watch?v=sGbxmsDFVnE";
    var id = video_source.split("?v=")[1]; //sGbxmsDFVnE
    var embedlink = "http://www.youtube.com/embed/" + id;
}

//https://jsfiddle.net/8qt2xrx8/3/