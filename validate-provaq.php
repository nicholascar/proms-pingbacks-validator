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
	$parseresult = parse_provaq_msg($headers, $body);
	if (!$parseresult[0]) {		
		http_response_code(400);
		header('Content-Type: text/plain');
		print $parseresult[1];
	} else {
		// parsed successfully
		http_response_code(204);
	}
} else {
	// only accept POST requests
	http_response_code(405); 
	header('Content-Type: text/plain');
	print 'Method not allowed'."\n".'This is the PROV-AQ message validator endpoint but it only accepts HTTP POST messages.';
}

// every entry in the message must be URI, one per line
function parse_provaq_msg($headers, $body) {
	$errors = array();
	// validate header
	$valid_header = is_valid_provaq_header($headers);
	if (!$valid_header[0]) {
		$errors[] = $valid_header[1];
	}

	// cater for the case of a Link header and no body content
	// must declare Content-Length: 0
	if ($headers['Content-Length'] != 0) {
		// validate body
		$valid_body = is_valid_provaq_body($body);
		if (!$valid_body[0]) {
			$errors[] = $valid_body[1];
		}
	}
	
	if (count($errors) > 0) {
		return array(false,'ERROR: your PROV-AQ pingback message is invalid for the following reasons.' . "\n" . implode("\n", $errors));
	} else {	
		return array(true);
	}
}
