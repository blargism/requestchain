<?php

function debugr($var) {
	echo '<pre><code>';
	print_r($var);
	echo '</code></pre>';
}

function fourohfour() {
	echo "page not found";
}

function set_layout($slug) {
	return '../layouts'.$slug.'.phtml';
}

