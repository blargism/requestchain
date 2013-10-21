<?php

function render($vars, $params, $config) {
	// set the layout
	if(!$params['layout_path']) {
		$params['layout_path'] = set_layout($config['layout']);
	}

	// Create local variables from the string values of the keys
	foreach($vars as $key => $value) {
		${$key} = $value;
	}

	// Capture the base view output
	ob_start();
	include($params['view_path']);
	$page_contents = ob_get_clean();

	// merge the base view output with the configured layout
	include($params['layout_path']);
}

function fourohfour($params, $config) {
	//
}
