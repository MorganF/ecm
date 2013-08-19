<?php
require_once('../includes.php');

$showForm = $Ecm_session->getUser() == NULL;
$erreurs = array();

if (isset($_POST['signup']))
{
	$Ecm_session->saveUser(Ecm_QueryAuthor::getAuthorFromLoginPassword($_POST['login'], $_POST['password']));
	
	if ($Ecm_session->getUser())
		$showForm = FALSE;
	else
		$erreurs[] = "Le couple login/mot de passe fourni ne correspond pas.";
}
?>
<html>
	<head>
		<meta charset="UTF-8" />
	</head>
	
	<body>
		<?php
		if (count($erreurs)) :
		?>
			<ul class="erreurs">
				<li>
					<?php echo implode('</li><li>', $erreurs); ?>
				</li>
			</ul>
		<?php
		endif;
		
		if ($showForm) :
		?>
			<form method="post">
				<p>
					<label for="login">Login :</label>
					<input type="text" name="login" id="login" />
				</p>
				
				<p>
					<label for="password">Mot de passe :</label>
					<input type="password" name="password" id="password" />
				</p>
				
				<p><input type="submit" name="signup" value="Se connecter" /></p>
			</form>
			
			<a href="sign-in.php">Inscription</a>
		<?php
		else :
		?>
			ACCUEIL ADMIN
		<?php
		endif;
		?>
	</body>
</html>