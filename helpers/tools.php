<?php

function debugr($var) {
	echo '<pre><code>';
	print_r($var);
	echo '</code></pre>';
}

function debugdump($var) {
	echo '<pre><code>';
	var_dump($var);
	echo '</code></pre>';
}

function set_layout($slug) {
	return '../layouts'.$slug.'.phtml';
}

