<?php
defined('CFX_APPPATH') OR exit('No direct script access allowed');
?>

<!DOCTYPE html>
<html lang="ko">
	<head>

		<!-- Basic -->
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">	

		<title>하루 (HARU)</title>	

		<meta name="keywords" content="" />
		<meta name="description" content="">
		<meta name="author" content="">

		<!-- Favicon -->
		<link rel="shortcut icon" href="/assets/icon/favicon.ico" type="image/x-icon" />
		<link rel="apple-touch-icon" href="/assets/icon/apple-touch-icon.png">

		<!-- Mobile Metas -->
		<meta name="viewport" content="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

		<!-- Vendor CSS -->
		<link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
		<link rel="stylesheet" href="/assets/vendor/font-awesome/css/font-awesome.min.css">
		<link rel="stylesheet" href="/assets/vendor/animate/animate.min.css">
		<link rel="stylesheet" href="/assets/vendor/simple-line-icons/css/simple-line-icons.min.css">
		<link rel="stylesheet" href="/assets/vendor/owl.carousel/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="/assets/vendor/owl.carousel/assets/owl.theme.default.min.css">
		<link rel="stylesheet" href="/assets/vendor/magnific-popup/magnific-popup.min.css">

		<!-- Theme CSS -->
		<link rel="stylesheet" href="/assets/css/theme.css">
		<link rel="stylesheet" href="/assets/css/theme-elements.css">

		<!-- Current Page CSS -->

		<link rel="stylesheet" href="/assets/vendor/rs-plugin/css/settings.css">
		<link rel="stylesheet" href="/assets/vendor/rs-plugin/css/layers.css">
		<link rel="stylesheet" href="/assets/vendor/rs-plugin/css/navigation.css">

		<!-- Admin Extension Specific Page Vendor CSS -->

		<link rel="stylesheet" href="/adm/assets/vendor/bootstrap-datepicker/css/bootstrap-datepicker3.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/jquery-ui/jquery-ui.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/jquery-ui/jquery-ui.theme.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/select2/css/select2.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/select2-bootstrap-theme/select2-bootstrap.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/bootstrap-multiselect/bootstrap-multiselect.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/bootstrap-colorpicker/css/bootstrap-colorpicker.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/bootstrap-timepicker/css/bootstrap-timepicker.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/dropzone/basic.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/dropzone/dropzone.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/bootstrap-markdown/css/bootstrap-markdown.min.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/summernote/summernote.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/codemirror/lib/codemirror.css" />
		<link rel="stylesheet" href="/adm/assets/vendor/codemirror/theme/monokai.css" />

		<!-- Admin Extension CSS -->
		<link rel="stylesheet" href="/adm/assets/stylesheets/theme-admin-extension.css">

		<!-- Admin Extension Skin CSS -->
		<link rel="stylesheet" href="/adm/assets/stylesheets/skins/extension.css">

		<!-- Skin CSS -->
		<link rel="stylesheet" href="/assets/css/skins/skin-app-landing.css"> 

		<!-- Head Libs -->
		<script src="/assets/vendor/modernizr/modernizr.min.js"></script>

		<!-- Vendor -->
		<script src="/assets/vendor/jquery/jquery.min.js"></script>
		<script src="/assets/vendor/jquery.appear/jquery.appear.min.js"></script>
		<script src="/assets/vendor/jquery.easing/jquery.easing.min.js"></script>
		<script src="/assets/vendor/jquery-cookie/jquery-cookie.min.js"></script>
		<script src="/assets/vendor/bootstrap/js/bootstrap.min.js"></script>
		<script src="/assets/vendor/common/common.min.js"></script>
		<script src="/assets/vendor/jquery.validation/jquery.validation.min.js"></script>
		<script src="/assets/vendor/jquery.easy-pie-chart/jquery.easy-pie-chart.min.js"></script>
		<script src="/assets/vendor/jquery.gmap/jquery.gmap.min.js"></script>
		<script src="/assets/vendor/jquery.lazyload/jquery.lazyload.min.js"></script>
		<script src="/assets/vendor/isotope/jquery.isotope.min.js"></script>
		<script src="/assets/vendor/owl.carousel/owl.carousel.min.js"></script>
		<script src="/assets/vendor/magnific-popup/jquery.magnific-popup.min.js"></script>
		<script src="/assets/vendor/vide/vide.min.js"></script>
		
<!-- head -->
<?php
// Add any stylesheets
if( isset( $view_data['stylesheets'] ) )
{
	foreach( $view_data['stylesheets'] as $js )
	{
		echo '<link rel="stylesheet" href="' . $js . '">' . "\n";
	}
}

// Add any javascripts
if( isset( $view_data['javascripts'] ) )
{
	foreach( $view_data['javascripts'] as $css )
	{
		echo '<script src="' . $css . '"></script>' . "\n";
	}
}
?>
	</head>
	<body data-spy="scroll" data-target=".header-nav-main nav" data-offset="65">