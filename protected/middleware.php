<?php

function basic_router($params, $config) {
	$params['method'] = strtolower($_SERVER['REQUEST_METHOD']).'_request';

	if($_SERVER['REQUEST_URI'] == '/') {
		$request_uri = '/index';
	} else {
		$request_uri = $_SERVER['REQUEST_URI'];
	}

	$params['view_path'] = '../views'.$request_uri.'.phtml';
	$params['handler_path'] = '../handlers'.$request_uri.'.php';

	if($config['layout']) {
		$params['layout_path'] = set_layout($config['layout']);
	} else {
		$params['layout_path'] = set_layout('/default');
	}

	return $params;
}
