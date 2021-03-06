<?php

$index = '';
if(isset($_GET['i']))
	$index = $_GET['i'];


if(empty($index)) {
	require './phpscripts/classConnection.php';
	require './phpscripts/classKAControl.php';
} else {
	require './classConnection.php';
	require './classKAControl.php';
}

$_kacontrol = new Connection();
$_kacontrol = new KAControl($_kacontrol);

$list_trend = '';

$query = '
	SELECT
		id, nome_curto, detalhes.valor
	FROM
		produtos
	    LEFT JOIN detalhes ON (produtos.id = detalhes.produto_id)
	    RIGHT JOIN ofertas ON (produtos.id = ofertas.produto_id)
	WHERE 
		ofertas.tipo = ?
';

if(empty($index)) {
	$values = ['destaque'];
	$list_trend = $_kacontrol->read($query, $values);
	$_kacontrol = '';
} else {
	$list = '';
	$values = [$index];

	if($index != 'ultimos') {
		$list = $_kacontrol->read($query, $values);
	} else {
		$query = '
			SELECT id, nome_curto, detalhes.valor
			FROM 
				produtos
			    LEFT JOIN detalhes ON (produtos.id = detalhes.produto_id)
			ORDER BY id DESC
			LIMIT 9
		';
		$list = $_kacontrol->read($query, '');
	}

	array_push($list, $index);

	$_kacontrol = '';
	
	echo json_encode($list);
}
?>