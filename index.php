<!DOCTYPE HTML>
<html lang="pl">
<head>
	<meta charset="utf-8" />
	<title>Portal ogłoszeniowy</title>

	
<!-- Latest compiled and minified CSS 	-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

</head>

<body>
<?php

session_start();

	if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
	{
		header('Location: strona_usera.php');
		exit();
	}
		if ((isset($_SESSION['udanarejestracja'])) )
	{
		echo 'Rejestracja powiodła się! Możesz się zalogować.';
		unset($_SESSION['udanarejestracja']);
	}

	
?>
<div class=" container "><div class="bg-success"> <center><h1>Portal ogłoszeniowy dla każdego</h1></center>
	<form action="logowanie.php" method="post" class="form-inline, container">
	<br><br>
		Login: <input type="text" name="nazwa" /> 
		Hasło: <input type="password" name="haslo" /> 
		<input type="submit" class="btn btn-info" value="Zaloguj się" /><img src="megafon.jpg" class="pull-right" /><br>

	<?php
	if(isset($_SESSION['blad']))	echo $_SESSION['blad'];
	if(isset($_SESSION['bl'])) echo '<br/>'.$_SESSION['bl'];
?>
	

	Nie masz konta?  <a href="rejestracja.php"><b>Zarejestruj się!</b></a>
	</form>


</div></div>
<div class="container" ><hr><h2 class="text-center">OGŁOSZENIA</h><hr></div>
<center><form action="" method="post"><b>Kategoria : </b><select name="kategoria">
<option>meble</option>
<option>motoryzacja</option>
<option>sport</option>
<option>inne</option>
	<input type="submit" value="szukaj" class="btn btn-info"/>
</select></form></center>
<div class='container'>
<?php

if(isset($_POST['kategoria'])){ $kategoria = $_POST['kategoria'];

require_once "connect.php";
		mysqli_report(MYSQLI_REPORT_STRICT);
		
		$wszystko_OK=true;
		
		try 
		{
			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{	
			
					$licz = $polaczenie->query("SELECT * FROM ogloszenia WHERE kategoria='$kategoria' ORDER BY id DESC LIMIT 1 ");
					$liczba_wierszy = $licz->fetch_assoc();
					$i = $liczba_wierszy['id'];
					$limit = 0;
					$_SESSION['i'] = $i;

					while($i>0)
				{
						
						$_SESSION['ogloszenia'] = TRUE;
						$h = $polaczenie->query("SELECT id FROM ogloszenia WHERE kategoria='$kategoria' ORDER BY id DESC LIMIT 1 ");
						$k = $h->fetch_assoc();
						$id = $k['id'];
						$id = $id-$limit;
						$pozycja = $polaczenie->query("SELECT * FROM ogloszenia WHERE id = '$id' AND kategoria='$kategoria' ");
						$wiersz = $pozycja->fetch_assoc();
						$_SESSION['nr'] = $wiersz['id'];
						$_SESSION['nazwa'] = $wiersz['nazwa'];
						$_SESSION['opis'] = $wiersz['opis'];
						$_SESSION['data_w'] = $wiersz['data_w'];
						$_SESSION['cena'] = $wiersz['cena'];
						$_SESSION['obraz'] = $wiersz['obraz'];
						$_SESSION['kontakt'] = $wiersz['kontakt'];
						$_SESSION['wlasciciel'] = $wiersz['wlasciciel'];
						$limit++;
						$i--;
						if($_SESSION['nazwa'] != ''){
						echo '<div class="bg-info">'.'<div class="panel panel-info">'.'<div class="panel-heading">';
									echo 	'<p class="container">'.'Nazwa : '.$_SESSION['nazwa'].'<br/></div><div class="panel-body" class="text-capitalize">';
									$obraz = $_SESSION['obraz'];
									echo 	"<img src= obrazy\'$obraz'.jpg class='img-thumbnail' />".'<div class="pull-right" >';
									echo 	'Opis: '.$_SESSION['opis'].'<br>';
									echo 	'Data wystawienia: '.$_SESSION['data_w'].'<br/>';
									echo 	'Kontakt: '.$_SESSION['kontakt'].'<br/>';
									echo 	'Właściciel: '.$_SESSION['wlasciciel'].'<br/>';
									echo 	'Cena: '.$_SESSION['cena'].' zł<br/>';
									echo '</p>';
								
						echo '</div></div></div></div>';
					}
				}

				
			}
		
				
				$polaczenie->close();
		}	
			
		
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z usługi w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
		

}?>

</div>
	
</body>
</html>