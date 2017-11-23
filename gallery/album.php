<?php 
	include '../db/database_conn.php';
	ini_set("session.save_path", "");
	session_start();
	
	require_once('../controls.php');
	echo makePageStart("Album");
	echo makeWrapper();
	echo makeLoginLogoutBtn();
	echo makeProfileButton();
	echo makeNavMenu();
	echo makeHeader("Album");
?>
<!doctype html>
<html>
<head>
<script type='text/javascript' src='../jquery-2.2.0.js'></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<script type="text/javascript" src="../jquery-ui.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<meta charset="UTF-8" />
<script src="../scripts/jquery.js"></script>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700" rel="stylesheet">
<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i" rel="stylesheet">
<script src="../scripts/parsley.min.js"></script>
<link rel="stylesheet" href="../css/parsley.css" type="text/css" />
<link rel="stylesheet" href="../css/stylesheet.css" type="text/css" />
<link href="../css/bootstrap.css" rel="stylesheet">
<script src="../scripts/bootstrap.min.js"></script>

	<!-- FOR FANCY BOS  -->
	<link rel="stylesheet" href="../assests/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" href="../assests/font-awesome/css/font-awesome.min.css">
	<script src="../assests/jquery/jquery.min.js"></script>
	<link rel="stylesheet" href="../assests/jquery-ui/jquery-ui.min.css">
	<script src="../assests/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="../assests/fancybox/source/jquery.fancybox.css?v=2.1.5" type="text/css" media="screen" />    
    <script type="text/javascript" src="../assests/fancybox/source/jquery.fancybox.pack.js?v=2.1.5"></script>
	

<title>Gallery</title>
</head>
<body>
<div class="content">
	<div class="container">
		<?php
			
			$sqlAlbum = "SELECT * from album WHERE albumStatus= '1' order by albumCreateDate";
			$rsAlbum = mysqli_query($conn, $sqlAlbum);
			while ($album = mysqli_fetch_assoc($rsAlbum)) {
				
				
				echo "\t<div class='album'>
							<a href=\"photos.php?albumID={$album['albumID']}&albumtitle={$album['albumDescription']}\"><img src='{$album['albumCoverPath']}' width='100%' height='270px'/></a>
							<div><span class='albumTitle'>{$album['albumDescription']}</span>";
							
							
							
							$sql1 = "SELECT photoID
									 FROM album_photo WHERE photoStatus = '1' AND albumID = '".$album['albumID']."'";
							$result1 = mysqli_query($conn, $sql1);
							$count = 0;
							while($row1 = mysqli_fetch_row($result1)){
								$count += 1;
							};
				echo "\t<span class='photoCount'>$count photos</span>
						</div></div>";
			};
		?>
		<a class='various outer' data-fancybox-type='iframe' href='createAlbum.php'>Create an Album</a>
		
		
		
		
		
		
	
		
		
		
		

	</div>
</div>
<?php
	echo makeFooter();
	echo makePageEnd();
?>
</body>
<script>

	$(".various").fancybox({
		maxWidth	: 1170,
		maxHeight	: 700,
		fitToView	: false,
		width		: '70%',
		height		: '70%',
		autoSize	: false,
		closeClick	: true,
		openEffect	: 'none',
		closeEffect	: 'none',
		afterClose: function () { // USE THIS IT IS YOUR ANSWER THE KEY WORD IS "afterClose"
			parent.location.reload(true);
		}
	});

  


</script>
</html>