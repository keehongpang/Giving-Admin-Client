<?php 
    $ch = curl_init();

    $encoded = '';
    foreach($_POST as $name => $value) {
        $encoded .= urlencode($name).'='.urlencode($value).'&';
    }
    // chop off last ampersand
    $encoded = substr($encoded, 0, strlen($encoded)-1);

    curl_setopt($ch, CURLOPT_URL, 'http://172.30.5.47/ws/insiteWS.php?'.$encoded); 
	
    curl_setopt( $ch, CURLOPT_HEADER, 0);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
    curl_setopt( $ch, CURLOPT_HTTPAUTH, 0);

    $result = curl_exec($ch);
    curl_close($ch);
	
    echo json_encode($result);
//    echo json_encode(json_encode($result));

?>
