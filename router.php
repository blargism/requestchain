<?php
/**
 * The MIT License (MIT)
 *
 * Copyright (c) 2013 Joe Mills
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

// Load the config
$config = require('config.php');

// load all the things we will always need
foreach($config['requirements'] as $requirement) {
	require_once($requirement);
}

/**
 * The params are a key to the request chain. They allow values to be passed
 * through each of the middleware, building out what the last step of the chain
 * needs.
 */
$params = [];

/**
 * Run each of the middleware the config file asks for
 * Each middleware gets the params which it should add to or adjust as is
 * required, and the config array which should not change.
 *
 * NOTE: These calls are not fault tolerant.  Be a good programmer and make
 * sure you only ask for provided middleware.
 */
if($config['middleware']) {
	foreach($config['middleware'] as $middleware) {
		$params = $middleware($params, $config);
	}
}

/**
 * The handler is a special breed of middleware, it follows rest rules and
 * should include methods to handle each supported rest verb.  The method 
 * titles map predictably to these verbs as follows:
 * GET => get_request()
 * POST => post_request()
 * PUT => post_request()
 * DELETE => delete_request()
 */
include($params['handler_path']);

/**
 * Call the final request based on the rest verb.  This get passed the params
 * and config, just like other middleware, but what is returned should be the
 * list of variables that the view will need.  Of note, the params and config
 * are still available since they are in this context  See inline comments for
 * how this works.
 */
if(function_exists($params['method'])) {
	// get the array of variables for the view
	$vars = $params['method']($params, $config);

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
} else {
	// call the fourohfour method, recommended this gets adjusted to
	// something better
	fourohfour();
}

