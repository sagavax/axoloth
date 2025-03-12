<?php
	function dateDiff ($d1, $d2) {
	// Return the number of days between the two dates:

  	return round(abs(strtotime($d1)-strtotime($d2))/86400);

	}  // end function dateDiff

?>	