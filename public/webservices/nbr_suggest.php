<?php
	include("connec_bdd.php");

	$result_request = array();
	$dates = array();
	$values = array();
	$mois = array("","Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"); 


	$query = "
		SELECT MONTH(date_created) as 'month', count( * ) as 'nb' 
		FROM element_suggest 
		GROUP BY MONTH(date_created)
	";

	$result = mysqli_query($conn, $query);
	
	while ($row = mysqli_fetch_array($result)) {
		// $date = str_replace("-",",",$row[0]);
		// // var_dump($date);
		// $tab = $date.",".$row[1];
		// array_push($result_request, [$tab]);
		// var_dump($row['month'], $row['nb']);
		if( isset($lastMonth) && ( intval($row['month']) != ($lastMonth + 1)) ){
			array_push($dates, $mois[$lastMonth + 1]);
			array_push($values, 0);
			array_push($dates, $mois[$row['month']]);
			array_push($values, intval($row['nb']));	
		} else {
			array_push($dates, $mois[$row['month']]);
			array_push($values, intval($row['nb']));
		}
		
		$lastMonth = intval($row['month']);
	}

	array_push($result_request, $dates);
	array_push($result_request, $values);

	mysqli_free_result($result);

	// Déconnexion de la BDD
	mysqli_close($conn);
	
	// Renvoyer le résultat au javascript
	echo json_encode($result_request);

?>