<?php

$hello = function ($name) { echo "Hello {$name}<br>\n"; };

class test {
	static function hello () {
		return function ($name) {
			echo "Hello {$name}<br>\n";
		};
	}
}


$hello('John');

$hello('Monica');


$hello = test::hello();

$hello('John');

$hello('Monica');

?>