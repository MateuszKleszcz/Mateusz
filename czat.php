<?php
 session_start();
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
				if(isset($_POST['wiad']))
				{	
				$adresat = $_SESSION['adresat'];
				$tresc = $_POST['wiad'];
				$imie = $_SESSION['imza'];
				$email = $_SESSION['emza'];
				$pisz=$polaczenie->query("INSERT INTO czat(nazwa,email,tresc,do) VALUES('$imie', '$email', '$tresc', '$adresat') ");
				
				}	
			}
			
			
		

			
				
				$polaczenie->close();
			
		}
		
		catch(Exception $e)
		{
			echo '<span style="color:red;">Błąd serwera! Przepraszamy za niedogodności i prosimy o skorzystanie z usługi w innym terminie!</span>';
			echo '<br />Informacja developerska: '.$e;
		}
		$_SESSION['aktywny'];
		header("Location: strona_usera.php");
 
 ?>

 
 
 