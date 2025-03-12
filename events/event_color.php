<?php include("../include/dbconnect.php");

function rgb_to_hex( string $rgba ) : string {
    if ( strpos( $rgba, '#' ) === 0 ) {
        return $rgba;
    }

    preg_match( '/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i', $rgba, $by_color );

    return sprintf( '#%02x%02x%02x', $by_color[1], $by_color[2], $by_color[3] );
}


$event_id = $_GET['event_id'];
$color = $_GET['color']; //color in rgb

$color = rgb_to_hex($color);


$sql="UPDATE tblcustomer_events SET event_color= '$color' where event_id=$event_id";
$result = mysqli_query($con, $sql)  or die("MySQli ERROR: ".mysqli_error($con));
