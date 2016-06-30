<?php

// is the Link header valid
function is_valid_provaq_header($headers) {
	$errors = array();
	
	// not valid if Content-Type not set to text/uri-list
	if (!isset($headers['Content-Type']) or $headers['Content-Type'] != 'text/uri-list') {
		return array(false, 'The Content-Type must be set to text/uri-list');
	}
	
	// valid if Link header not set
	if (!isset($headers['Link'])) {
		return array(true);
	} else {
		$parts = explode(';', $headers['Link']);
		if (count($parts) != 3) {
			$errors[] = 'It doesn\'t have 3 parts';
		}
		
		// TODO: match the <>
		if (!is_valid_uri(trim($parts[0], "<>"))) {
			$errors[] = 'The first part (inside "<" & ">") is not a valid URI';
		}
		
		if (!in_array($parts[1], array('rel="http://www.w3.org/ns/prov#has_provenance"', 'rel="http://www.w3.org/ns/prov#has_query_service"'))) {
			$errors[] = 'The rel part must be either rel="http://www.w3.org/ns/prov#has_provenance" or rel="http://www.w3.org/ns/prov#has_query_service"';
		}

		if (substr($parts[2], 0, 7) != 'anchor=' or !is_valid_uri(trim(substr($parts[2], 7), "\""))) {			
			$errors[] = 'The anchor part must start with "anchor=" and then a valid URI';
		}		
		
		if (count($errors) > 0) {
			return array(false,'The header doesn\'t validate, specifically: ' . "\n" . implode("\n", $errors));
		} else {	
			return array(true);
		}
	}
}

// is the body valid (a list of URIs)
function is_valid_provaq_body($body) {
	$errors = array();
	
	$uris = explode("\n", $body);
	if (count($uris) < 1) {
		$errors[] = 'Body contains no URIs';
	}	
	
	foreach ($uris as $uri) {
		if (!is_valid_uri($uri)) {
			$errors[] = $uri . ' is not a valid URI';
		}
	}
	
	if (count($errors) > 0) {
		return array(false,'The message body header doesn\'t validate, specifically: ' . "\n" . implode("\n", $errors));
	} else {
		return array(true);
	}
}

// is the candidate a valid URI
function is_valid_uri($uri_candidate) {
	return (filter_var($uri_candidate, FILTER_VALIDATE_URL) == true);
}

function is_valid_proms_header($headers) {
	// not valid if Content-Type not set to an RDF datatype (text/turtle, text/n3, application/rdf+xml, application/ld+json)
	$allowed_content_types = array(
		'text/turtle', 
		'text/n3', 
		'application/rdf+xml', 
		'application/ld+json'
	);
	if (!isset($headers['Content-Type']) or !in_array($headers['Content-Type'], $allowed_content_types)) {
		return array(false, 'The Content-Type must be set to one of the following: ' . implode(', ', $allowed_content_types));
	} else {
		return array(true);
	}
}

function is_valid_proms_body($content_type, $body) {
	switch ($content_type) {
		case 'text/turtle':
			$parser = 'turtle';
		break;
		case 'text/n3':
			$parser = 'ntriples';
		break;
		case 'application/rdf+xml':
			$parser = 'rdfxml';
		break;
		case 'application/ld+json':
			$parser = 'jsonld';
		break;
		default:
			$parser = 'turtle';
		break;		
	}
	// interpret the body
	// get the required parser from the header, which we know at this point is valid
	set_include_path(get_include_path() . PATH_SEPARATOR . 'easyrdf-0.9.0/lib/');
	require_once "EasyRdf.php";
	$graph = new EasyRdf_Graph(null);
	try {
		$graph->parse($body, $parser, null);
	} catch (Exception $e) {
		return array(false,'The message body could not be parsed. ' . $e->getMessage());
	}
	
	if (!isset($graph)) {
		return array(false,'The message body could not be parsed');
	} else {
		return array(true);
	}
}
?>