<p>This form allows you to test out pingback messages you create elsewhere and submit manually.</p>
<h3>Paste your pingback message into the text area below and then select options.</h3>
<div class="provaq">
	<p><strong><em>OPTIONAL:</em></strong> HTTP 'Link' headers, as per PROV-AQ:</p>
	<textarea id="link-header" class="provaq" name="link-header" style="width:800px;min-height:50px;"></textarea>
</div><!-- #link -->
<p><strong><em>REQUIRED:</em></strong> HTTP message body, <span class="provaq">URIs, one per line:</span><span class="proms" style="display:none;">an RDF document for PROMS messages:</span></p>
<textarea id="msg" name="msg" style="width:800px;min-height:100px;"></textarea>
<style>
table#formoptions th,
table#formoptions td {
	vertical-align: top;
}
</style>
<table id="formoptions" style="border:none;">
	<tr>
		<th>
			Options:
		</th>
		<td style="padding-left:30px;">
			Message type:<br />
			<input type="radio" name="msgtype" id="typeprovaq" value="provaq" checked /> PROV-AQ<br />
			<input type="radio" name="msgtype" id="typeproms"  value="proms" /> PROMS<br />
		</td>
		<td id="contenttype" style="padding-left:30px;">
			Content Type: <input type="radio" name="contenttype" id="urilist" value="text/uri-list" checked /><code>text/uri-list</code>
		</td>
	</tr>
</table>
<br />
<input type="hidden" name="ct" id="ct" value="text/uri-list" />
<input type="hidden" name="loc" id="loc" value="/validate-provaq" />
<input id="submit" type="submit" value="Validate" /> <div id="loading" style="display:none;"><img src="/theme/img/spinner.gif" style="height:1em" /> processing...</div>
<div id="results" style="width:800px; margin-top:10px; display:none; border:solid 1px black;">
	<div id="result" name="result" style="padding:5px; overflow:auto;"></div>
</div>
<script src="jquery-3.0.0.min.js"></script>
<script>
	jQuery(document).ready(function(){
		jQuery('input[name=msgtype]:radio').click(function() {
			if($('#typeproms').is(':checked')) { 
				var contenttypes = 'Content Type:<br />' +
									'<input type="radio" name="contenttype" id="turtle" value="text/turtle" /><code>text/turtle</code><br />' + 
									'<input type="radio" name="contenttype" id="n3" value="text/n3" /><code>text/n3</code><br />' +
									'<input type="radio" name="contenttype" id="xml" value="application/rdf+xml" /><code>application/rdf+xml</code><br />' +
									'<input type="radio" name="contenttype" id="jsonld" value="application/ld+json" /><code>application/ld+json</code>';
				$('#contenttype').html(contenttypes);
				$("#ct").attr('value', 'text/turtle');
				$("#turtle").attr('checked', 'checked');
				$('#loc').attr('value', '/validate-proms');
				$('.provaq').hide();
				$('.proms').show();
			} else {
				$('#contenttype').html('Content Type: <input type="radio" name="contenttype" id="urilist" value="text/uri-list" checked /><code>text/uri-list</code>');
				$("#ct").attr('value', 'text/uri-list');
				$('#loc').attr('value', '/validate-provaq');
				$('.provaq').show();
				$('.proms').hide();
			}
		});
		
		jQuery('body').on('click', 'input[name=contenttype]:radio', function () {
			$("#ct").attr('value', $(this).val());
		});

		jQuery('#submit').click(function(){
			var link_header = '';
			if ($('#link-header').css('display') != 'none') {
				link_header = $('#link-header').val();
			}
			// do post
			$.ajax({
				url: $('#loc').val(),
				type: "POST",
				headers: {
					"Link": link_header
				},
				contentType: $('#ct').val(),
				data: $('#msg').val(),
				dataType: "text",
				success: function(xml, textStatus, xhr) {
					$('#result').html('Response status code: ' + xhr.status + '\nSuccess, your pingback message is Valid!');
					$('#result').css('color', 'black');
					$('#results').show();
				},
				error: function(xhr, textStatus, err) {
					$('#result').html('Response status code: ' + xhr.status + '<br />' + xhr.responseText.replace(/\n/g, '<br />')); //
					$('#result').css('color', 'red');
					$('#result').css('height', '6em');					
					$('#results').show();
				},				
				complete: function(xhr, textStatus) {
					//
				} 
			});
		});
	
		var $loading = $('#loading').hide();
		$(document)
		  .ajaxStart(function () {
			$loading.show();
		  })
		  .ajaxStop(function () {
			$loading.hide();
		  });
	});
</script>