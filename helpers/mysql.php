<?php

$mysql_config = $config['mysql'];
$mysqli = mysqli_connect($mysql_config['host'], $mysql_config['user'], $mysql_config['password'], $mysql_config['database']);
$mysql_last_error = null;

function sanitize($query, $values) {
	foreach($values as $key => $value) {
		$value = '"'.mysqli_real_escape_string($value).'"';
		$query = str_replace(":".$key, $value);
	}

	return $query;
}

function get_many($query, $values) {
	$query = sanitize($query, $values);

	$response = mysqli_query($mysqli, $query);
	$mysql_last_error = mysqli_error();

	$results = [];
	while($results[] = mysqli_fetch_assoc()) {
		// uh, do I need to do something?
	}

	mysqli_free_result();

	return $results;
}

function get_one($query, $values) {
	$query = sanitize($query, $values);

	$response = mysqli_query($mysqli, $query);
	$result = mysqli_fetch_assoc($response);
	$mysql_last_error = mysqli_error();

	mysqli_free_result();

	return $result[0];
}

function post($query, $values) {
	$query = sanitize($query, $value);

	$response = mysqli_query($mysqli, $query);
	$values['id'] = mysqli_insert_id();
	$mysql_last_error = mysqli_error();

	return $values;
}

function put($query, $values) {
	$query = sanitize($query, $value);

	$response = mysqli_query($mysqli, $query);
	$mysql_last_error = mysqli_error();

	return $mysql_last_error;
}
