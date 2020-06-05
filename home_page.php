<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" content="width=device-width, initial-scale=1">
    <title>Project</title>
	<style>
	html {
		background: url(UBC.jpg) no-repeat center center fixed;
		-webkit-background-size: cover;
		-moz-background-size: cover;
		-o-background-size: cover;
		background-size: cover;
	}
	ul {
	list-style-type: none;
	margin: 0;
	padding: 0;
	overflow: hidden;
	background-color: #333;
	}

	li {
	float: left;
	}

	li a, .dropbtn {
	display: inline-block;
	color: white;
	text-align: center;
	padding: 14px 16px;
	text-decoration: none;
	}

	li a:hover, .dropdown:hover .dropbtn {
	background-color: skyblue;
	}

	li.dropdown {
	display: inline-block;
	}

	.dropdown-content {
	display: none;
	position: absolute;
	background-color: #f9f9f9;
	min-width: 160px;
	box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	z-index: 1;
	right: 0;
	}

	.dropdown-content a {
	color: black;
	padding: 12px 16px;
	text-decoration: none;
	display: block;
	text-align: left;
	}

	.dropdown-content a:hover {background-color: #f1f1f1;}

	.dropdown:hover .dropdown-content {
	display: block;
	}
	.header{
    margin-top: 1%;
    font-size: 400%;
    text-align: center;
	}
	</style>
</head>
<body>
<nav>
    <div class="nav-wrapper">
        <ul>
            <li><a href="https://www.students.cs.ubc.ca/~zpengwei/home_page.php">Home</a></li>
			<li><a href="https://www.students.cs.ubc.ca/~zpengwei/game_page.php">Game</a></li>
			<li><a href="https://www.students.cs.ubc.ca/~zpengwei/forum_page.php">Forum</a></li>
			<li><a href="https://www.students.cs.ubc.ca/~zpengwei/article_page.php">Article</a></li>
			<li><a href="https://www.students.cs.ubc.ca/~zpengwei/about_page.php">About</a></li>
			<li class="dropdown" style="float:right">
				<a class="dropbtn">My Account</a>
				<div class="dropdown-content">
				<a href="https://www.students.cs.ubc.ca/~zpengwei/mySetting_page.php">Setting</a>
				<a href="https://www.students.cs.ubc.ca/~zpengwei/myFriend_page.php">Friend</a>
				<!-- TODO: My game has Game Record and Achievement in it -->
				<a href="https://www.students.cs.ubc.ca/~zpengwei/myGame_page.php">Game Owned</a> 
				<a href="https://www.students.cs.ubc.ca/~zpengwei/myFollowUp_page.php">FollowUp</a>
				<a href="https://www.students.cs.ubc.ca/~zpengwei/myArticle_page.php">My Article</a>
				<a href="https://www.students.cs.ubc.ca/~zpengwei/myForum_page.php">My Forum</a>
				</div>
			</li>

        </ul>
    </div>
</nav>
<div class="header">Welcome to</div>
<div class="header">Totally Gamer</div>
</body>
</html>
