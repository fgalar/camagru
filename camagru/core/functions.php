<?PHP

function debug($variable) {
	$debug = debug_backtrace();
	echo '<p>&nbsp;</p><a href="#"><strong>' . $debug[0]['file'] . ' </strong> l. ' . $debug[0]['line'] . '</a> ';

	echo '<ol>';
	foreach($debug as $k=>$v) {
		if ($k > 0) {
			echo '<li><strong>' . $v['file'] . ' </strong> l. ' . $v['line'] . '</li>';
		}
	}
	echo '</ol>';
	echo '<pre>' . print_r($variable, true) . '</pre>';
}
