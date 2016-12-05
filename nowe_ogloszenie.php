<!-- Latest compiled and minified CSS 	-->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<?php

session_start();
if ((!isset($_SESSION['zalogowany'])))
	{
		header('Location: index.php');
		exit();
	}


function sprawdz_bledy()
{
  if ($_FILES['obrazek']['error'] > 0)
  {
    echo 'problem: ';
    switch ($_FILES['obrazek']['error'])
    {
      // jest większy niż domyślny maksymalny rozmiar,
      // podany w pliku konfiguracyjnym
      case 1: {echo 'Rozmiar pliku jest zbyt duży.'; break;} 
	  
      // jest większy niż wartość pola formularza 
      // MAX_FILE_SIZE
      case 2: {echo 'Rozmiar pliku jest zbyt duży.'; break;}
	  
      // plik nie został wysłany w całości
      case 3: {echo 'Plik wysłany tylko częściowo.'; break;}
	  
      // plik nie został wysłany
      case 4: {echo 'Nie wysłano żadnego pliku.'; break;}
	  
      // pozostałe błędy
      default: {echo 'Wystąpił błąd podczas wysyłania.';
        break;}
    }
    return false;
  }
  return true;
}



function sprawdz_typ()
{
	if ($_FILES['obrazek']['type'] != 'image/jpeg')
		return false;
	return true;
}

function zapisz_plik()
{


  $len = 20;
  $r = substr(sha1(rand(1,10000)),0,$len);
  $_SESSION['r']=$r;
  
  $lokalizacja ="obrazy\'$r'.jpg";
  
	
  if(is_uploaded_file($_FILES['obrazek']['tmp_name']))
  {
    if(!move_uploaded_file($_FILES['obrazek']['tmp_name'], $lokalizacja))
    {
      echo 'problem: Nie udało się skopiować pliku do katalogu.';
        return false;  
    }
  }
  else
  {
    echo 'problem: Możliwy atak podczas przesyłania pliku.';
	echo 'Plik nie został zapisany.';
    return false;
  }
  return true;
}


if(isset($_POST['np']))
{
sprawdz_bledy();
sprawdz_typ();
zapisz_plik();


$np = $_POST['np'];
$opis = $_POST['opis'];
$data_r = $_POST['data_w'];
$cena = $_POST['cena'];
$kontakt = $_POST['kontakt'];
$wlasciciel = $_POST['wlasciciel'];
$data_w = $_POST['data_w'];
$kategoria = $_POST['kategoria'];
$obraz = $_SESSION['r'];
$uwlasciciel = $_SESSION['user'];

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
				
				$rezultat = $polaczenie->query("SELECT id FROM ogloszenia WHERE obraz='$obraz'");
				
				if (!$rezultat) throw new Exception($polaczenie->error);
				
				$ile_takich_obrazow = $rezultat->num_rows;
				if($ile_takich_obrazow>0)
				{
					$wszystko_OK=false;
					$_SESSION['e_obraz']="Nie udało się, spróbuj dodać przedmiot jeszcze raz!";
				}		
				
				if ($wszystko_OK==true)
				{
					
					
					if ($polaczenie->query("INSERT INTO ogloszenia (nazwa,data_w,cena,opis,wlasciciel,obraz,kontakt,kategoria) VALUES ('$np', '$data_w', '$cena', '$opis', '$wlasciciel', '$obraz','$kontakt', '$kategoria' )"))
					{
						echo 'Udało się dodać ogłoszenie!';
					}
					else
					{
						throw new Exception($polaczenie->error);
					}
					
				}
				
				$polaczenie->close();
			}
			
		}
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z usługi w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		
	
}
?>

<html>
<br>
<div class="bg-info"><div class="container"><div class="container"><div class="container">

<form action="" method="post" name="ogloszenia" enctype="multipart/form-data" class="form-group">

NAZWA PRZEDMIOTU : <input type="text" name="np" required  class="form-control" /><br>
WŁAŚCICIEL (wystawiający) : <input type="text" name="wlasciciel" required  class="form-control"/><br>
KONTAKT (telefon lub e-mail) : <input type="text" name="kontakt" required  class="form-control"/><br>
KRÓTKI OPIS (max 500 znaków) :<input type="text" name="opis"  class="form-control"/> <br>
KATEGORIA :<select name="kategoria"  class="form-control"><option>meble</option><option>motoryzacja</option><option>sport</option><option>inne</option></select> <br>
DATA I GODZINA WYSTAWIENIA : <input type="datetime-local" name="data_w"required  class="form-control"/><br>
CENA : <input type="number" name="cena" min="0"  class="form-control"/><br>
<br>
PROSZĘ WSTAWIĆ ZDIĘCIE PRZEDMIOTU :
<input type="hidden" name="MAX_FILE_SIZE" value="512000" class="form-control"/>
<input type="file" name="obrazek" required  class="form-control"/><br>
<input type="submit" value="WYŚLIJ" class="btn btn-info" class="pull-right"/>
</form>

<form action="strona_usera.php" method="post"><input type="submit" value="WRÓĆ DO MOJEJ STRONY"  class="btn btn-info" ></form>
</div></div></div></div>

</html>