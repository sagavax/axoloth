function goURL(url){
    var url = document.getElementById("pass_url").value;
    window.open(url);
  }

  document.getElementById("is_favorite").disabled = true


  function LogonType(type_id) {
    const credentials = {
        0: { user_name: "", password: "" },
        1: { user_name: "tmisura@gmail.com", password: "Thomas$.pa$$w0rd" },
        2: { user_name: "tmisura@gmail.com", password: "YTOKaQHOhXO" },
        3: { user_name: "ftb_axoloth", password: "g1sqrrprfwb5yhh" },
        4: { user_name: "tmisura", password: "Toma$.pa$$w0rd" },
        5: { user_name: "tmisura@gmail.com", password: "28d72uvyesn2eka" },
        6: { user_name: "sagavax", password: "sxzec4yytboelcj" },
        7: { user_name: "tmisura@gmail.com", password: "642vol5mmedfrxs" }
    };

    const userCredentials = credentials[type_id];
    if (userCredentials) {
        document.new_password.user_name.value = userCredentials.user_name;
        document.new_password.password.value = userCredentials.password;
    } else {
        // Default behavior if an invalid `type_id` is provided
        document.new_password.user_name.value = "";
        document.new_password.password.value = "";
    }
}

/* if(window.scrollY==0){
 //user scrolled to the top of the page
    document.querySelector(".pass_tags").style.top="20px";
} */


    function save_note(id) {
      //note_title = document.getElementById("pass_note_title").value;
      note_text = document.getElementById("pass_note_text").value;
      /*console.log(note_text);*/
     
  
  
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              note_id = this.responseText.trim();
              document.getElementById("pass_note_text").value = "";
               attach_new_pass_note(note_text,note_id);
          }
      };
  
      xhttp.open("POST", "create_pass_note.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("pass_id=" + id + "&note_text=" + encodeURIComponent(note_text));
  
  }
  
  function attach_new_pass_note(note_text,note_id) {
      var parent_container = document.querySelector(".pass_note_container");
      parent_container.insertAdjacentHTML('afterbegin', "<div class='pass_note' note-id="+note_id+"><button class='close' type='button'  onclick='remove_pass_note("+note_id+");'><i class='fa fa-times'></i></button>" + note_text + "</div>");
      //parent_container.style.display = "none";
  }
  
  
  function save_modal_note() {
      var id = sessionStorage.getItem("pass-id");
      //note_title = document.getElementById("pass_note_title").value;
      note_text = document.getElementById("pass_note_text").value;
      /*console.log(note_text);*/
     // attach_new_pass_note(note_text);
  
  
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              //reload_note_categories()
              //alert("bol pridany link");
              //attach_new_pass_note(note_text);
              document.getElementById("pass_note_text").value = "";
              document.querySelector(".show_add_note").style.display = "none";
          }
      };
  
      xhttp.open("POST", "create_pass_note.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("pass_id=" + id + "&note_text=" + encodeURIComponent(note_text));
  
  }
  
  function remove_pass_note(note_id){
  
      var xhttp = new XMLHttpRequest();
      xhttp.onreadystatechange = function() {
          if (this.readyState == 4 && this.status == 200) {
              //remove note from the list
              const parent = document.querySelector(".pass_note_container");
              const child = document.querySelector('[note-id="'+note_id+'"]');
              parent.removeChild(child);
          }
      };
  
  
      xhttp.open("POST", "remove_pass_note.php", true);
      xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
      xhttp.send("note_id=" + note_id);
  }