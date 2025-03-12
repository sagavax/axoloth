function save_image() {
            var mycanvas = document.getElementById("mycanvas");
            var dataURL = mycanvas.toDataURL("image/jpg"); 
            //var data = base64_encode(dataURL);   
            var xhttp = new XMLHttpRequest();
            //var data = imgBase64: dataURL;
            xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                const context = mycanvas.getContext('2d');
                context.clearRect(0, 0, mycanvas.width, mycanvas.height);
                 alert("Image uploaded");
                }
            };

            xhttp.open("POST", "upload_clip_image.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send("data=" + mycanvas.toDataURL());