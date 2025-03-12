

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image from clipboard</title>
    <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
     <link href="../css/style_new.css?<?php echo time(); ?>" rel="stylesheet" type="text/css" />
    <link href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css' rel='stylesheet' type='text/css'>
    <link rel='shortcut icon' href='../eis.ico'>
</head>
<body>
    <div id="header">
        <!--header -->
        <div class="header-logo">e.i.s.</div>
        <!--logo -->
         <div class="header-menu">
        <!--menu-->
            <?php include '../include/menu.php' ?>
        </div>
    </div>
    <div id="layout">
        <div id="image_from_clipboard">
            <div id="canvas_wrapper">
                <div class="canvas_text">
                     Focus this tab and press <kbd>CTRL</kbd> + <kbd>V</kbd>. The image on your clipboard will be rendered on the canvas !
                </div>
                <div class="canvas">
                    <canvas id="mycanvas">
                </div>
                <div class="canvas_save">
                    <a href='index.php' class="flat-btn">&lt;&lt; Back to home</a><button class="flat-btn" onclick="save_image()">Save image</button>
                </div>
            </div><!-- canvas_wrapper -->          
        </div><!-- image from clipboard-->
    </div><!-- layout -->
    <script type="text/javascript">
        /**
 * This handler retrieves the images from the clipboard as a blob and returns it in a callback.
 * 
 * @see http://ourcodeworld.com/articles/read/491/how-to-retrieve-images-from-the-clipboard-with-javascript-in-the-browser
 * @param pasteEvent 
 * @param callback 
 */

 /* 
  *     https://codepen.io/rebelchris/pen/rNevrXg - Canvas to image
  *     https://dev.to/dailydevtips1/vanilla-javascript-save-canvas-as-an-image-3pfa
  *     https://weworkweplay.com/play/saving-html5-canvas-as-image/
  *     https://stackoverflow.com/questions/18377891/how-can-i-let-user-paste-image-data-from-the-clipboard-into-a-canvas-element-in
  *     
  */
function retrieveImageFromClipboardAsBlob(pasteEvent, callback){
	if(pasteEvent.clipboardData == false){
        if(typeof(callback) == "function"){
            callback(undefined);
        }
    };

    var items = pasteEvent.clipboardData.items;

    if(items == undefined){
        if(typeof(callback) == "function"){
            callback(undefined);
        }
    };

    for (var i = 0; i < items.length; i++) {
        // Skip content if not image
        if (items[i].type.indexOf("image") == -1) continue;
        // Retrieve image on clipboard as blob
        var blob = items[i].getAsFile();

        if(typeof(callback) == "function"){
            callback(blob);
        }
    }
}

window.addEventListener("paste", function(e){

    // Handle the event
    retrieveImageFromClipboardAsBlob(e, function(imageBlob){
        // If there's an image, display it in the canvas
        if(imageBlob){
            var canvas = document.getElementById("mycanvas");
            var ctx = canvas.getContext('2d');
            
            // Create an image to render the blob on the canvas
            var img = new Image();

            // Once the image loads, render the img on the canvas
            img.onload = function(){
                // Update dimensions of the canvas with the dimensions of the image
                canvas.width = this.width;
                canvas.height = this.height;

                // Draw the image
                ctx.drawImage(img, 0, 0);
            };

            // Crossbrowser support for URL
            var URLObj = window.URL || window.webkitURL;

            // Creates a DOMString containing a URL representing the object given in the parameter
            // namely the original Blob
            img.src = URLObj.createObjectURL(imageBlob);
        }
    });
}, false);


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
        } 
    </script>
</body>
</html>

