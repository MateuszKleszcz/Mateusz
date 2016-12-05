<!-- Latest compiled and minified CSS 	-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<div class="container"><div class="bg-success"><br>
<?php
session_start();

if ((!isset($_SESSION['zalogowany'])))
	{
		header('Location: index.php');
		exit();
	}

$nazwa = $_SESSION['user'];
$email = $_SESSION['email'];
$imie = $nazwa;
$_SESSION['imza'] = $imie;
$_SESSION['emza'] = $email;
echo 'nazwa: '. $nazwa.'  '.'e-mail : '.$email.'<br><hr>';
?>
<center><h3>To twoja strona <?php echo $imie;?>, możesz teraz dodawać swoje ogłoszenia.</h></center>
<form action="nowe_ogloszenie.php" method="post" >
<div class="block-inline"><input type="submit" value="DODAJ NOWE OGŁOSZENIE" class="btn btn-info"/>
</form>
<form name="logout" action="logout.php" method="post" class="pull-right" >
<input type="submit" value="Wyloguj" class="btn btn-info">
</form>
   </div></div></div>

<div name="czat" class="container"><h4>Komunikator</h5>
 <div name="wybor_rozmowcy">
 <form id="wyb_roz" action="" method="post">
 
 Wybierz rozmówcę : <select name="rozmowca" >
		 <?php 		
		 require_once "connect.php";

			$polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
			if ($polaczenie->connect_errno!=0)
			{
				throw new Exception(mysqli_connect_errno());
			}
			else
			{	
			
					$licz = $polaczenie->query("SELECT id FROM users ORDER BY id DESC LIMIT 1 ");
					$liczba_wierszy = $licz->fetch_assoc();
					$i = $liczba_wierszy['id'];
					$limit = 0;
					$_SESSION['i'] = $i;

					while($i>0)
					{
						
						$h = $polaczenie->query("SELECT id FROM users ORDER BY id DESC LIMIT 1 ");
						$k = $h->fetch_assoc();
						$id = $k['id'];
						$id = $id-$limit;
						$pozycja = $polaczenie->query("SELECT * FROM users WHERE id = '$id' ");
						$wiersz = $pozycja->fetch_assoc();
						$_SESSION['rozmowca'] = $wiersz['nazwa'];
						$limit++;
						$i--;
						
						if($_SESSION['rozmowca'] != '' )
						{echo '<option>'.$_SESSION['rozmowca'].'</option>';}
					
				}

			}
				
		 $polaczenie->close();	?>
	</select>
	<input type="submit" value="Wybierz" class="btn btn-info">
	</form>
 </div>
 
 <div name="tresc" class="bg-info">
 <?php

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
			{		if(isset($_POST['rozmowca']))
				{$rozmowca=$_POST['rozmowca'];
					$licz = $polaczenie->query("SELECT * FROM czat WHERE nazwa='$rozmowca' ORDER BY id DESC LIMIT 1 ");
					$liczba_wierszy = $licz->fetch_assoc();
					$i = $liczba_wierszy['id'];
					$limit = 0;
					$_SESSION['i'] = $i;

					while($i>0)
					{
						$hm= $polaczenie->query("SELECT id FROM czat WHERE nazwa='$imie' ORDER BY id DESC LIMIT 1 ");
						$km = $hm->fetch_assoc();
						$idm = $km['id'];
						$idm = $idm-$limit;
						
						$pozycjam = $polaczenie->query("SELECT * FROM czat WHERE id = '$idm' AND nazwa='$imie' AND do='$rozmowca' ");
						$wierszm = $pozycjam->fetch_assoc();
						$_SESSION['nazwam'] = $wierszm['nazwa'];
						$_SESSION['trescm'] = $wierszm['tresc'];

						
						if($_SESSION['trescm'] != '')
						{echo $_SESSION['nazwam'].' : '.$_SESSION['trescm'].'<br>';}
						
						$h = $polaczenie->query("SELECT id FROM czat WHERE nazwa='$rozmowca' ORDER BY id DESC LIMIT 1 ");
						$k = $h->fetch_assoc();
						$id = $k['id'];
						$id = $id-$limit;
						
						$pozycja = $polaczenie->query("SELECT * FROM czat WHERE id = '$id' AND nazwa='$rozmowca' AND do='$imie' ");
						$wiersz = $pozycja->fetch_assoc();
						$_SESSION['nazwa'] = $wiersz['nazwa'];
						$_SESSION['tresc'] = $wiersz['tresc'];

						
						if($_SESSION['tresc'] != '')
						{echo $_SESSION['nazwa'].' : '.$_SESSION['tresc'].'<br>';}
						
					
						
						$limit++;
						$i--;

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
 
 
 ?>

 </div>
 <div name="pisz" class="bg-success"> <br>
 <form action="czat.php" method="post" >
 <b>Napisz do : </b> <?php if(isset($_POST['rozmowca'])) {echo $_POST['rozmowca']; 
 $_SESSION['adresat'] = $_POST['rozmowca']; }?>
<input type="text" name="wiad" class="form-control"/>
 <input type="submit" value="Wyślij" class="btn btn-info"/>
 </form>

 
 </div>
</div>
   
<div class="container">

<?php
echo '<div class="container" ><hr><h2 class="text-center">OGŁOSZENIA</h><hr></div>';?>
<center><form action="" method="post"><b>Kategoria : </b><select name="kategoria">
<option>meble</option>
<option>motoryzacja</option>
<option>sport</option>
<option>inne</option>
	<input type="submit" value="szukaj" class="btn btn-info"/>
</select></form></center>
<?php

if(isset($_POST['kategoria'])) {$kategoria = $_POST['kategoria'];

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
									
									echo  "<img src= obrazy/'$obraz'.jpg class='img-thumbnail' />".'<div class="pull-right">';	
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
		
		
//==================================================================================================================
		
		

}?>
</div>
