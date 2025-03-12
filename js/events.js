var table = document.getElementById("month_calendar");

document.getElementById("filter_this_year").addEventListener("click", function() {
    event_filter = "this_year";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("nr_of_comments").innerHTML = this.responseText;
            document.querySelector(".event_list").innerHTML = this.responseText;
            //funct1(max_comment_id);
        }
    };
    //xhttp.open("GET", "count_comments.php?video_id=<?php echo $_GET['video_id'] ?>", true);
    xhttp.open("GET", "event_filter.php?event_filter=" + encodeURIComponent(event_filter), true);
    xhttp.send();
})



document.getElementById("filter_this_month").addEventListener("click", function() {
    event_filter = "this_month";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("nr_of_comments").innerHTML = this.responseText;
            document.querySelector(".event_list").innerHTML = this.responseText;
            //funct1(max_comment_id);
        }
    };
    //xhttp.open("GET", "count_comments.php?video_id=<?php echo $_GET['video_id'] ?>", true);
    xhttp.open("GET", "event_filter.php?event_filter=" + encodeURIComponent(event_filter), true);
    xhttp.send();
})

document.getElementById("filter_this_week").addEventListener("click", function() {
    event_filter = "this_week";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("nr_of_comments").innerHTML = this.responseText;
            document.querySelector(".event_list").innerHTML = this.responseText;

            //(this.responseText);

            //funct1(max_comment_id);
        }
    };
    //xhttp.open("GET", "count_comments.php?video_id=<?php echo $_GET['video_id'] ?>", true);
    xhttp.open("GET", "event_filter.php?event_filter=" + encodeURIComponent(event_filter), true);
    xhttp.send();
})

document.getElementById("filter_today").addEventListener("click", function() {
    event_filter = "today";
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            //document.getElementById("nr_of_comments").innerHTML = this.responseText;
            document.querySelector(".event_list").innerHTML = this.responseText;
            //funct1(max_comment_id);
        }
    };
    //xhttp.open("GET", "count_comments.php?video_id=<?php echo $_GET['video_id'] ?>", true);
    xhttp.open("GET", "event_filter.php?event_filter=" + encodeURIComponent(event_filter), true);
    xhttp.send();
})

//console.log(table);
var day_mumbers = document.querySelectorAll(".day-number");
for (var i = 0; i < day_mumbers.length; i++) {
    let day_number = day_mumbers[i]
    day_number.addEventListener("click", function(element) {
        event_day = this.getAttribute("data-date");
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                //document.getElementById("nr_of_comments").innerHTML = this.responseText;
                document.querySelector(".event_list").innerHTML = this.responseText;
                //funct1(max_comment_id);
            }
        };
        //xhttp.open("GET", "count_comments.php?video_id=<?php echo $_GET['video_id'] ?>", true);
        xhttp.open("GET", "event_day.php?event_day=" + encodeURIComponent(event_day), true);
        xhttp.send();
    })

}

