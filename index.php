<?php



	if(isset($_GET['page']) and !empty($_GET['page'])){

		$page = $_GET['page'];

		switch($page){
			case 'home':
				include("home.php");
				break;
			case 'blog':
				include("blog.php");
				break;
			case 'contact':
				include("contact.php");
				break;
			case 'course':
				include("course.php");
				break;
			case 'library':
				include("library.php");
				break;
			case 'login':
				include("login.php");
				break;
			case 'team':
				include("team.php");
				break;
			default:
				include("404.php");
				break;

		}

	} else {
		include("home.php");
	}

?>