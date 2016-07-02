<?php
/*
*	PROV Pingbacks message validator target-12
*
* 	Accepts simple pingbacks of the form in PROV-AQ, example 12 only
*	https://www.w3.org/TR/prov-aq/
*	e.g.
*
*	POST target-12.php HTTP/1.1
*	Content-Type: text/uri-list
*
*	http://coyote.example.org/contraption/provenance
*	http://coyote.example.org/another/provenance
*
*
*/
include 'validator.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$headers = getallheaders();
	$body = trim(file_get_contents("php://input"));
	$parseresult = parse_proms_msg($headers, $body);
	if (!$parseresult[0]) {		
		http_response_code(400);
		header('Content-Type: text/plain');
		print $parseresult[1];
	} else {
		// parsed successfully
		http_response_code(201);
		header('Content-Type: text/plain');
		print 'Created ' . $parseresult[1] . ' triples';
	}
} else {
	// only accept POST requests
	http_response_code(405); 
	header('Content-Type: text/plain');
	print 'Method not allowed'."\n".'This is the PROMS message validator endpoint but it only accepts HTTP POST messages.';
}

// every entry in the message must be URI, one per line
function parse_proms_msg($headers, $body) {
	$errors = array();
	$triple_count = 0;
	// validate header
	$valid_header = is_valid_proms_header($headers);
	if (!$valid_header[0]) {
		$errors[] = $valid_header[1];
	}

	// validate body
	$valid_body = is_valid_proms_body($headers['Content-Type'], $body);
	if (!$valid_body[0]) {
		$errors[] = $valid_body[1];
	} else {
		$triple_count = $valid_body[1];
	}
	
	if (count($errors) > 0) {
		return array(false,'ERROR: your PROMS pingback message is invalid for the following reasons.' . "\n" . implode("\n", $errors));
	} else {	
		return array(true, $triple_count);
	}
}
?>