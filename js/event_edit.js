window.onload = function (){
    document.getElementById("color_palette").style.display="none"; 
    document.querySelector(".event_color").addEventListener("click", function(){
      //alert("test");
      if(document.getElementById("color_palette").style.display=="none"){
        document.getElementById("color_palette").style.display="block"; 
      } else {
        document.getElementById("color_palette").style.display="none"; 
      }
    });

    var parent = document.getElementById("color_palette");
      var div = document.createElement('div');
      let colors=["#ede7f6","#9575cd","#b3e5fc","#4fc3f7","#b2dfdb","#4db6ac","#c5e1a5","#aed581","#ffcdd2","#ef9a9a","#fcdc29","#7ba8ef","#b3cff3","#fb819b","#8bc34a"];

      for ( i=0; i<colors.length;i++ ){
        parent.innerHTML+="<div class='palette_item'></div>";
        let palette_items = document.querySelectorAll(".palette_item");
        for (y=0;y<palette_items.length;y++){
          var color = colors[y];
          palette_items[y].style.background=color;
          //https://stackoverflow.com/questions/20805505/addeventlistener-not-working-on-list-of-elements
          //palette_items[y].innerHTML=y;
        } // set all divs background color from the list
      }  
      //get number of all colors
     
        document.querySelectorAll('.palette_item').forEach(item => {
          item.addEventListener('click', event => {
            //handle click
          
            //alert(item.style.backgroundColor);
            document.querySelector(".event_color").style.backgroundColor = item.style.backgroundColor;
            var event_id = localStorage.getItem("event_id");
            var color = item.style.backgroundColor;
           console.log(event_id, color);
           update_event_color(color, event_id);
          })
        })
  }

document.addEventListener('click',function(e){
    if(e.target && e.target.className=="fas fa-list"){
          //do something
          alert("show as list");
     } else if (e.target && e.target.className=="fas fa-th-large"){
        alert("show as grid");
     }
 });

document.getElementById("is_meeting").addEventListener("click",function(e){
    if(e.target.checked){
        //alert("is checked");
        var event_id = localStorage.getItem("event_id");
        create_meeting(event_id);
    } else {
        //alert("unchecked");
        var event_id = localStorage.getItem("event_id");
        cancel_meeting(event_id);
    }
});



 function update_event_color(color, event_id){
    //alert(event_id+", "+color);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           //alert("color has been changed");
           document.getElementById("color_palette").style.display="none";
        }
    };
    xhttp.open("GET", "event_color.php?color="+encodeURIComponent(color)+"&event_id="+encodeURIComponent(event_id), true);
    xhttp.send();
}

function create_meeting(event_id){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        }
    }           
    xhttp.open("GET", "event_meeting.php?event_id="+encodeURIComponent(event_id)+"&is_meeting=1", true);
    xhttp.send()
}

function cancel_meeting(event_id){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
        }
    }
    xhttp.open("GET", "event_meeting.php?event_id="+encodeURIComponent(event_id)+"&is_meeting=0", true);
    xhttp.send()
}