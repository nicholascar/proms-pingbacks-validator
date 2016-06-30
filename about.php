<!--<h3 style="color:red;">NOTE: the validator is not yet operating. ETA is November 20, 2015</h2>-->
<h2>About the Validator</h2>
<p>This pingbacks message validator has been established to help people validate <a href="http://www.w3.org/TR/prov-aq/">PROV-AQ</a> and <a href="http://promsns.org">PROMS</a> 'pingback' messages. These messages are used to communicate the provenance of things between provenance mangament systems.</p>
<p>PROV-AQ is the <a href="https://www.w3.org/">W3C</a> technical note (hopefully soon a W3C recommendation) in the <a href="https://www.w3.org/TR/prov-dm/">PROV Data Model</a> family of documents. PROMS is the Australian extension to PROV. Regarding pingbacks, PROMS just allows the sending of provenance 'bundles' as well as links to further provenance recources as PROV-AQ does.</p>

<h2>How to use the Validator</h2>
<ol>
	<li>
		formulate your message
		<ul>
			<li>you can use any system that can send HTTP requests like <a href="http://curl.haxx.se/">cURL</a></li>
			<li>you can also use the purpose-built <a href="http://promsns.org/repo/proms-pingbacks-message-generator">PROMS Pingbacks message generator</a> which is a small Python toolkit</li>
		</ul>
	<li>
		send the message, via an HTTP POST request, to one of the validator endpoints
		<ul>
			<li>use the validator form (<a href="http://promsns.org/pingbacks/validator/form">http://promsns.org/pingbacks/validator/form</a>) for manual input</li>
			<li>send POST mesasages directly to the endpoints for machine-to-machine</li>
		</ul>
	</li>
	<li>
		review the validator results
		<ul>
			<li>valid messages will receive an HTTP status code response of 204 and no message body, as per PROV-AQ</li>
			<li>error messages will be given with an appropriate HTTP status code (usually 400 - Bad Request) and a text string describing the error</li>
		</ul>
	</li>
</ol>
<h3>Validator Endpoint</h3>
<p>The validator endpoint is:</p>
<ul>
	<li><strong>http://promsns.org/pingbacks/validator/</strong></li>
</ul>
<p>Yes, it is the same URI as this web page! This URI accepts GET requests, giving this page, and POST requests, for sending pingbacks messages.</p>

<h2>Pingback messages described</h2>
<p>This web page tells you how to use the pingback validator service to validate provenance pingbacks messages. Pingbacks messages are HTTP messages from a client to a server informing the server about provenance information for a target resource that the recieving pingbacks endpoint has been set up to process.</p>

<h3>Formulating a pingbacks message</h3>
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
