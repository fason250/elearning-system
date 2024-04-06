<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="Jey fason" />
	<link rel="shortcut icon" href="../images/logo.png" type="image/x-icon">
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet"/>
	<link rel="stylesheet" href="../css/bootstrap.css"/>
	<link rel="stylesheet" href="../style.css"/>
	<link rel="stylesheet" href="../css/dark.css"/>
	<link rel="stylesheet" href="../css/font-icons.css"/>
	<link rel="stylesheet" href="../css/animate.css"/>
	<link rel="stylesheet" href="../css/magnific-popup.css"/>
	<link rel="stylesheet" href="../css/calendar.css"/>
	<link rel="stylesheet" href="../css/responsive.css"/>
	<link rel="stylesheet" href="../css/custom.css">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>intellistudy</title>
</head>
<style>
	#left_sidebar{
	position: fixed;
	top: 0;
	left: 0;
	width: 240px;
	z-index: 99;
	background-color: #f9f9f9;
	border-right: 1px solid #EEE;
	height: 100%;
	overflow: auto;
	padding: 140px 0 40px;
	/* -webkit-transition: padding .4s ease;
	-o-transition: padding .4s ease; */
	transition: padding .4s ease;
}

.sticky-header + #left_sidebar { 
	padding-top: 100px; 
}

#left_sidebar nav ul {
	margin-bottom: 0;
	list-style: none;
}

#left_sidebar nav li a {
	display: block;
	padding: 10px 30px;
	color: darkslategray;
	text-transform: capitalize;
	font-size: 13px;
	font-weight: 700;
	letter-spacing: 2px;
	font-family: 'Raleway', sans-serif;
}

#left_sidebar nav li i {
	font-size: 14px;
	width: 16px;
	text-align: center;
}

#left_sidebar nav li i:not(.icon-angle-down) {
	margin-right: 8px;
	position: relative;
	top: 1px;
}

#left_sidebar nav li a i.icon-angle-down {
	width: auto;
	margin-left: 5px;
}

#left_sidebar nav li:hover > a,
#left_sidebar nav li.current > a,
#left_sidebar nav li.active > a {
	background-color: darkslategray;
	color: white;
}

#left_sidebar nav ul ul { display: none; }

#left_sidebar nav ul ul a {
	font-size: 12px;
	letter-spacing: 1px;
	padding-left: 45px;
	font-family: 'Lato', sans-serif;
}

#left_sidebar nav ul ul a i.icon-angle-down { font-size: 12px; }

#left_sidebar nav ul ul ul a { padding-left: 60px; }
#left_sidebar nav ul ul ul ul a { padding-left: 75px; }

@media only screen and (min-width: 992px) {

	#header { z-index: 199; }

	#page-title,
	#content,
	#footer { margin-left: 240px; }

	#page-title .container,
	#content .container,
	#footer .container {
		width: auto;
		padding: 0 60px;
	}

	#left_sidebar .container {
		width: 100%;
		padding: 0;
	}

	#page-title .breadcrumb { right: 60px !important; }

}


@media (max-width: 991px) {

	#left_sidebar {
		position: relative;
		width: 100%;
		z-index: auto;
		border: none;
		border-bottom: 1px solid #EEE;
		height: auto;
		padding: 0;
	}

	#left_sidebar .container { padding: 10px 20px; }

	#left_sidebar nav li a { padding: 10px 0; }

	#left_sidebar nav li:hover > a,
	#left_sidebar nav li.current > a,
	#left_sidebar nav li.active > a { background-color: transparent; }

	#left_sidebar nav ul ul a { padding-left: 15px; }
	#left_sidebar nav ul ul ul a { padding-left: 30px; }
	#left_sidebar nav ul ul ul ul a { padding-left: 45px; }

}

.custom_label{
	font-size: 15px;
	text-transform: capitalize;
}

.custom_input{
	border-radius: 35px;
	outline: none;
	border: 1px solid darkslateblue;
}
.bg_slate{
	background-color: darkslategrey;
	color: white;
}
.bg_slate:hover{
	color: whitesmoke;
}
</style>

<body class="stretched">
