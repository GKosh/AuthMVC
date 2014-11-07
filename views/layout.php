<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php
echo $title;
?></title>
<link rel="stylesheet" type="text/css" href="<?php echo $CSS; ?>" media="all">
<script type="text/javascript" src=BASE_URL"/data/js/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="<?php echo $JS; ?>"></script>
</head>
<body id="main_body" >

	<?php if (file_exists($header_path)) include $header_path; ?>
	<?php if (file_exists($content_path)) include $content_path; ?>
	<?php if (file_exists($footer_path)) include $footer_path; ?>
	

</body>
</html>