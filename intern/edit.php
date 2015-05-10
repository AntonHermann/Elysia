<?php
	session_start();
	include 'settings.php';
	
	if (!isset($_SESSION['id'])) {
		// Benutzer nicht angemeldet -> Zugriff verweigert!
		return "Zugriff verweigert!";
		exit("Zugriff verweigert!");
	} elseif (isset($_POST["items"])) {
		$items = explode(",", $_POST["items"]);
		foreach ($items as $orderid => $id) {
			mysqli_begin_transaction($mysqli);
			$ergebnis = mysqli_query($mysqli, "UPDATE pics SET orderid = '".intval($orderid)."' WHERE id = '".intval($id)."';");
			if ($ergebnis) {
				//Erfolg!
				mysqli_commit($mysqli);
			} else {
				//Fehler
				mysqli_rollback($mysqli);
			}
		}
		
		if (isset($_POST["deleted"])) {
			$deleteItems = explode(",", $_POST["deleted"]);
			foreach ($deleteItems as $id) {
				mysqli_begin_transaction($mysqli);
				$ergebnis = mysqli_query($mysqli, "DELETE FROM pics WHERE id = '".intval($id)."';");
				if ($ergebnis) {
					//Erfolg!
					mysqli_commit($mysqli);
				} else {
					//Fehler
					echo "error:" . $mysqli->error . " - ";
					mysqli_rollback($mysqli);
				}
			}
		}
	}
?>