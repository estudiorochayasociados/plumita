<?php

// A list of permitted file extensions
$allowed = array('png', 'jpg', 'gif', 'zip');

if (isset($_FILES['upl']) && $_FILES['upl']['error'] == 0) {

	mkdir('../../assets/archivos/productos');
	chmod('../../assets/archivos/productos', 0777);

	$extension = pathinfo($_FILES['upl']['name'], PATHINFO_EXTENSION);

	if (!in_array(strtolower($extension), $allowed)) {
		echo '{"status":"error"}';
		exit;
	}

	if (move_uploaded_file($_FILES['upl']['tmp_name'], '../../assets/archivos/productos/' . $_FILES['upl']['name'])) {
		echo '{"status":"success"}';
		exit;
	}
}

echo '{"status":"error"}';
exit;
