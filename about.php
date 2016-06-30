<h2>About the Validator</h2>
<p>This pingbacks message validator has been established to help people validate PROV-AQ and PROMS 'pingback' messages. These messages are used to communicate provenance information between provenance mangament systems.</p>

<h2>About pingback messages</h2>
<p>Pingback messages are HTTP messages from a client to a server informing the server about provenance information for a target resource that the recieving pingbacks endpoint has been set up to process.</p>
<p>PROV-AQ messages are as per the <a href="http://www.w3.org/TR/prov-aq/">PROV-AQ</a> <a href="https://www.w3.org/">W3C</a> technical note (hopefully soon a W3C recommendation) in the <a href="https://www.w3.org/TR/prov-dm/">PROV Data Model</a> family of documents. <a href="http://promsns.org">PROMS</a> messages are bundles (<code>prov:Bundle</code>) of provenance information that follow certain rules that one provenance system may send to another. PROMS messages were formulated since the authors of the <a href="http://promsns.org">PROMS Server</a> wanted the ability to 'pingback' provenance information but PROV-AQ does not provide for this.</p>

<p>See below in the 'Pingback messages description' section, after the description of 'How to use the Validator' for fuller descriptions of PROV-AQ and PROMS messages.</p>

<h2>How to use the Validator</h2>
<h3>Form input</h3>
<p>This validator contains a web form you can use to send pingback messages to the validation system, see <a href=<?php print $WEB_SUBFOLDER; ?>"/about"></a>. All of the various message type options are present on the form page and results, as per the PROV-AQ or the PROMS specs are displayed after message processing on screen.</p>
<p>To use the form input method, you will need to formulate your pingback message body first and then enter it into the form.</p>
<h3>Script/command line input</h3>
<ol>
	<li>
		formulate your message
		<ul>
			<li>manually, or:</li>
			<li>use the purpose-built <a href="http://promsns.org/repo/proms-pingbacks-message-generator">PROMS Pingbacks message generator</a> which is a small Python toolkit</li>
		</ul>
	<li>
		send the message, via an HTTP POST request, to one of the validator endpoints
		<ul>
			<li>you can use any system that can send HTTP requests like <a href="http://curl.haxx.se/">cURL</a></li>
			<li>use the validator form (<a href="http://promsns.org/pingbacks/validator/form">http://promsns.org/pingbacks/validator/form</a>) for manual input</li>
			<li>the <a href="http://promsns.org/repo/proms-pingbacks-message-generator">PROMS Pingbacks message generator</a> can send POSt requests using Python's <em>requests</em> module</li>
			<li><strong>send PROV-AQ messages to <a href="http://promsns.org/pingbacks/validator/validate-provaq.php">http://promsns.org/pingbacks/validator/validate-provaq.php</a></strong></li>
			<li><strong>send PROMS messages to <a href="http://promsns.org/pingbacks/validator/validate-proms.php">http://promsns.org/pingbacks/validator/validate-proms.php</a></strong></li>
		</ul>
	</li>
	<li>
		review the validator results
		<ul>
			<li>success/error messages are returned as HTTP codes and response body text, as per the PROV-AQ &amp; PROMS specs.</li>
		</ul>
	</li>
</ol>
<h3>Validator Endpoints</h3>
<ul>
	<li><strong>PROV-AQ messages:</strong> <a href="http://promsns.org/pingbacks/validator/validate-provaq.php">http://promsns.org/pingbacks/validator/validate-provaq.php</a></li>
	<li><strong>PROMS messages:</strong> <a href="http://promsns.org/pingbacks/validator/validate-proms.php">http://promsns.org/pingbacks/validator/validate-proms.php</a></li>
</ul>

<h2>Pingback messages description</h2>
<h3>PROV-AQ messages</h3>
<p>According to the <a href="http://www.w3.org/TR/prov-aq/">PROV-AQ</a> specification (Section 5) a pingbacks message is :</p>
<ul>
	<li>
		an HTTP 1.1 POST request with certain headers and body
		<ul>
			<li>header: must have <strong>Content-Type: text/uri-list</strong></li>
			<li>body: must have a list of URIs, formatted as per text/uri-list (see <a href="http://amundsen.com/hypermedia/urilist/">http://amundsen.com/hypermedia/urilist/</a> for a description)
		</ul>
	</li>
	<li>the URIs in the body list are for locations that deliver provenance for the resource being pingbacked for</li>
	<li>URIs for provenance information for resources other than that for the resource being pingbacked for can be given, see "Further Links" below</li>
	<li>URIs for provenance query services, rather than for provenance resources, can be given, see "Further Links" below</li>
</ul>
<h4>Example 1 - a basic pingbacks message</h4>
<p>
	<span class="mono">
		POST http://example.com/target-resource/pingback HTTP/1.1<br />
		Content-Type: text/uri-list<br />
		<br />
		http://something.com/some-provenance.rdf<br />
		http://somethingelse.com/provenance?_format=ttl<br />
	</span>
</p>

<h3>Further Links</h3>
<p>A basic prinbacks message contains just a list of URIs indicating where provenance information for the target resource is to be found.</p>
<p>"Further Links" can be specified that:</p>
<ul>
	<li>give <span class="mono">has_query_service</span> links indicating provenance query services for the target resource</li>
	<li>give <span class="mono">has_query_service</span> or <span class="mono">has_provenance</span> links for resources other than the target resource</li>
</ul>
<p>Both these types of further links are indicated by adding the URIs of these links to the message body and adding descriptive HTTP <span class="mono">Link</span> headers to the pingbacks message as follows:</p>
<ul>
	<li>
		for a <span class="mono">has_query_service</span> link for the target resource:
		<ul>
			<li>
				<span class="mono">
					Link: &lt;http://example.com/target/resource&gt;; rel="http://www.w3.org/ns/prov#has_query_service"; anchor="http://example.com/has/query/service/endpoint"
				</span>
			</li>
		</ul>
	</li>
	<li>
		for a <span class="mono">has_provenance</span> or <span class="mono">has_query_service</span> link for resources other than the target resource:
		<ul>
			<li>
				<span class="mono">
					Link: &lt;http://example.com/non-target/resource&gt;; rel="http://www.w3.org/ns/prov#has_provenance"; anchor="http://example.com/has/other/provenance"
				</span>
			</li>
		</ul>
		<ul>
			<li>
				<span class="mono">
					Link: &lt;http://example.com/non-target/resource&gt;; rel="http://www.w3.org/ns/prov#has_query_service"; anchor="http://example.com/has/other/query/service/endpoint"
				</span>
			</li>
		</ul>
	</li>
</ul>

<h4>Example 2 - a pingbacks message with a query service link for the target resource</h4>
	<pre class="mono">
POST http://example.com/target-resource/pingback HTTP/1.1
Link: &lt;http://other.org/some-query-service&gt;;
rel="http://www.w3.org/ns/prov#has_provenance";
anchor="http://example.com/target-resource"
Content-Type: text/uri-list
Content-Length: 0
	</pre>
<p>Note that, in this example, no <span class="mono">has_provenance</span> links have been given so that this message only includes a single <span class="mono">has_query_service</span> link in the <span class="mono">Link</span> header. The <span class="mono">Content-Length</span> header has been set to zero to ensure the server understands that there is no body content. There could have been in which case the <span class="mono">Content-Length</span> header would not have been set.</p>
<h4>Example 3 - a pingbacks message with a has_provenance link for resources other than the target resource</h4>
	<pre class="mono">
POST http://example.com/target-resource/pingback HTTP/1.1
Link: &lt;http://other.org/provenance-resource.rdf&gt;;
rel="http://www.w3.org/ns/prov#has_provenance";
anchor="http://example.com/other-resource"
Content-Type: text/uri-list

http://something.com/some-provenance.rdf
http://somethingelse.com/provenance?_format=ttl
http://other.org/provenance-resource.rdf
	</pre>
<p>Note that the new link to the provenance resource in the <span class="mono">Link</span> header for the non-target resource, <span class="mono">http://other.org/provenance-resource.rdf</span>, may or may not be also given in the in the message body. In this case it is. It depends on whether the provenance resource is relecant to both the target resource and the non-terget resources, or both.</p>	
<p><span class="mono">has_query_service</span> links may be specified for non-target resources too.</p>

<h4>PROV-AQ responses</h4>
<p>Valid messages recieve and HTTP 204 'No Content' status code and no response body. Invalid messages receive an HTTP 400 'Bad Command' status code and one or more detailed error messages in the response body as plain text.</p>

<h3>PROMS messages</h3>
<p>These messages are RDF documents that must be sent with one of the following Content-Type headers:</p>
<ul>
	<li>text/turtle</li>
	<li>text/n3</li>
	<li>application/rdf+xml</li>
	<li>application/ld+json</li>
</ul>
<p>Once successfully parsed, the RDF document is checked according to the following rules:</p>
<style>
	table.plain {
		border: none;
	}
	table.plain td, 
	table.plain th {
		vertical-align:top;
		padding-left:10px;
	}
</style>
<table class="plain">
	<tr><th></th><th>Name</th><th>Description</th></tr>
	<tr><th>Rule&nbsp;1</th><td>PROV-O</td><td>The RDF document must be a valid <a href="https://www.w3.org/TR/prov-o/">PROV-O</a> document as per the <a href="https://www.w3.org/TR/prov-constraints/">PROV constraints</a>.<br /><em>(not implemented yet)</em></td></tr>
	<tr><th>Rule&nbsp;2</th><td>Bundle</td><td>The RDF document must declare itself a <code>prov:Bundle</code></td></tr>
	<tr><th>Rule&nbsp;3</th><td>Pingback Property</td><td>At least one <code>prov:Entity</code> (or subclass) must declare a <code>prov:pingback</code> property pointing to the pingback target. In this validator's case, an Entity must have a property pointing to <code>&lt;http://promsns.org/pingbacks/validator/validator-proms.php&gt;</code></td></tr>
</table>

<h4>PROMS responses</h4>
<p>Valid messages recieve and HTTP 201 'Inserted' status code and a resonse body, in plain text, of the phrase "Inserted {COUNT} triples." reflecting the number of triples inserted into the receiving triplestore. Invalid messages receive an HTTP 400 'Bad Command' status code and one or more detailed error messages in the response body as plain text.</p>