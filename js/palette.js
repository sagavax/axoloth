window.onload = function (){
    
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
      //let colors=["#ede7f6","#9575cd","#b3e5fc","#4fc3f7","#b2dfdb","#4db6ac","#c5e1a5","#aed581","#ffcdd2","#ef9a9a","#fcdc29","#7ba8ef","#b3cff3","#fb819b","#8bc34a"];
      var colors = ["#f28b82","#fbbc04","#fff475","#ccff90","#a7ffeb","#cbf0f8","#aecbfa","#d7aefb","#fdcfe8","#e6c9a8","#e8eaed","#ffffff"];

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
            document.querySelector(".palette_item").style.backgroundColor = item.style.backgroundColor;
            //var event_id = localStorage.getItem("event_id");
            var color = item.style.backgroundColor;
           //console.log(event_id, color);
           //update_event_color(color, event_id);
          })
        })
  }