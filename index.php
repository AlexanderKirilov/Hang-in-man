<?php
require_once('utility.php');
require_once('game.php');
require_once('dictionary.php');

//I could move it to a class Game of itself, but dont want to make you look at too many files
//
//Gona use session for the learning purpose as well as to enable:
//Site refresh
//Game statesaving
//(!)Store game state for longer
//
//(!) === TODOs

//Do not want to burden any class ( specialy Game class) with session management
if(!is_session_started()){
	session_start();
}


//!!! USED EXCLUSIVLY WHILE DEVELOPING !!!
if(isset($_GET['dest'])){
	session_destroy();
	die();
}

//Persistently store main Game object in Session
if(!isset($_SESSION['gameObj'])){
	$game = new Game();

	$_SESSION['gameObj'] = $game;
}else{
	$game = $_SESSION['gameObj'];
}

/**
 * Catch User input
 */
if(!empty($_POST)){
	//reset input
	if(isset($_POST['reset'])){
		$game->reset();

		//clean up global object
		foreach ($_POST as $key => $value) {
			unset($_POST[$key]);
		}
	}

	//User input
	if(isset($_POST['usrInput']) && $_POST['usrInput'] != ''){
		$game->registerInput($_POST['usrInput']);

		unset($_POST['usrInput']);
	}
}
?>
<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<!-- !!! USED EXCLUSIVLY WHILE DEVELOPING !!! -->
	<!--  cheating hint in game title -->
	<title>Hang (in) Man <?= $game->getWord() ?></title>
	<link rel="stylesheet" type="text/css" href="css/main.css">
	<link href="https://fonts.googleapis.com/css?family=Open+Sans" rel="stylesheet">
</head>

<body>
	<main>
		<div id="word">
			<?php
			$wordLen = $game->wordLen;
			for($x=0;$x < $wordLen;$x++){
				echo '<span id="letter" type="text" name="'.$x.'" >'.$game->renderLetter($x).'</span>';
			}
			?>
			<br>
			<form method="post" >
				<input id="usrInput" name="usrInput" type="text" maxlength="1" size="1">
				<input id="subButt" type="submit" value="try to guess">
				<input id="resButt" type="submit" name="reset" value="reset">
			</form>

			<!-- Used letters (!)TODO: style -->
			<p><?= implode(' ',$game->usedLetters)?></p>

			<!-- (!) TODO: ASSOSIATE LEFT TRIES WITH IMAGE -->
			<p>left:<?= $game->getTriesLeft(); ?></p>
		</div>
	</main>
</body>