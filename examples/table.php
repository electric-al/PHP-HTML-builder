<?php

/**
 *	Generate TABLE
 */

require('../src/HtmlBuilder.php');
 
$rows = array(
	array('Row 1', 'Hello', 'World'),
	array('Row 2', 'Hello1', 'World1')
);

$h = new HtmlBuilder();

$h->push('table')
	->push('tr')
		->insert('th', 'Column 1')
		->insert('th', 'Column 2')
		->insert('th', 'Column 3')
	->pop();
	
foreach($rows as $row){
	$h->push('tr')
		->insert('td', $row[0])
		->insert('td', $row[1])
		->insert('td', $row[2])
		->pop();
}

echo $h->asHtml();