<?php session_start();

?>

<?php include "../include/dbconnect.php";?>
<?php include "../include/functions.php";?>

<?php

if (isset($_POST['get_meta'])) {
	//pridat novy kategoriu
	$tags = get_meta_tags($_POST['note_url']);
	$content = file_get_contents($_POST['note_url']);

	$doc = new DOMDocument();

// squelch HTML5 errors
	@$doc->loadHTML($content);

	$meta = $doc->getElementsByTagName('meta');
	foreach ($meta as $element) {
		$tag = [];
		foreach ($element->attributes as $node) {
			$tag[$node->name] = $node->value;
		}
		$tags[] = $tag;
	}

	print_r($tags);
	//print_r($tags);
	//echo $tags['parsely-section'];
	// echo $tags['parsely-metadata'];
}
?>

<!DOCTYPE html>
<head>
   <meta charset="utf-8" />
   <title>E.I.S - Add new link</title>
   <link rel="stylesheet" href="../css/style_new.css?<?php echo time() ?>" type="text/css">
   <link rel="stylesheet" href="../css/notepad.css?<?php echo time() ?>" type="text/css">
   <link href="https://netdna.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700' rel='stylesheet' type='text/css'>
   <?php include "../include/icon.php"?>
</head>
<body>
<div id="header"> <!--header -->
<div class="header-logo">E.I.S.</div> <!--logo -->
 <div class="header-menu">
     <!--menu-->
    <?php include '../include/menu.php'?>
  </div>
</div> <!-- end of header -->
   <!--end of header -->
   <div id="layout">
       <div class="add_new_link">
               <form action="" method="post" id="new_note_form">
                  <!--<div class="edit_note_title"><input type="text" name="note_title" autocomplete="off" placeholder="title..."></div>-->
                  <div class="edit_note_url"><input name="note_url" placeholder="url...."></textarea></div>
                  <div class="edit_note_action"><a href='index.php' class="flat-btn">&lt;&lt; Back to home</a> <button name="get_meta" type="submit" class="flat-btn">get meta tags</button></div>
               </form>
       </div>
   </div>
</body>
</html>