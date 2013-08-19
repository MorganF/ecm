<?php
require_once('../includes.php');

$showForm = TRUE;

if (isset($_POST['signin']))
{
	$erreurs = array();	
	$login = isset($_POST['login']) && preg_match('#^[a-z0-9-]{5,}$#i', $_POST['login']) ? $_POST['login'] : NULL;
	$email = isset($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) ? $_POST['email'] : NULL;
	$password = isset($_POST['password']) && preg_match('#^.{5,}$#i', $_POST['password']) ? $_POST['password'] : NULL;
	$password2 = isset($_POST['password2']) && $_POST['password'] == $_POST['password2'];
	
	if (!$login)
		$erreurs[] = "Le login doit faire au minimum 5 caractères, seuls les caractères alpha-numériques et les tirets sont autorisés.";
	else if (!Ecm_QueryAuthor::isLoginAvailable($login))
		$erreurs[] = "Ce login est déjà utilisé.";
	
	if (!$email)
		$erreurs[] = "Veuillez fournir une adresse e-mail valide.";
	else if (!Ecm_QueryAuthor::isMailAvailable($email))
		$erreurs[] = "Cette adresse e-mail est déjà utilisée.";
	
	if (!$password)
		$erreurs[] = "Le mot de passe doit faire au moins 5 caractères.";
	
	if (!$password2)
		$erreurs[] = "Le second mot de passe saisi ne correspond pas au premier.";
	
	if (!count($erreurs))
	{
		if (Ecm_QueryAuthor::insert($login, $password, $email))
			$showForm = FALSE;
		else
			$erreurs[] = "Une erreur est survenue lors de la création de votre compte.";
	}
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
					<label for="email">E-mail :</label>
					<input type="text" name="email" id="email" />
				</p>
				
				<p>
					<label for="password">Mot de passe :</label>
					<input type="password" name="password" id="password" />
				</p>
				
				<p>
					<label for="password2">Mot de passe (répéter) :</label>
					<input type="password" name="password2" id="password2" />
				</p>
				
				<p><input type="submit" name="signin" value="Valider" /></p>
			</form>
		<?php
		else :
		?>
			<p>Votre compte a bien été créé.</p>
		<?php
		endif;
		?>
	</body>
</html>