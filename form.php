<p>This form allows you to test out pingback messages you create elsewhere and submit manually.</p>
<h3>Paste your pingback message into the text area below and then select options.</h3>
<textarea id="msg" name="msg" style="width:800px;min-height:300px;"></textarea>
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
			<input type="radio" name="msgtype" id="typeprovaq" value="provaq" checked="checked" /> PROV-AQ<br />
			<input type="radio" name="msgtype" id="typeproms"  value="proms" /> PROMS<br />
		</td>
		<td id="contenttype" style="padding-left:30px;">
			Content Type: <input type="radio" name="contenttype" id="urilist" value="text/uri-list" checked="checked" /><code>text/uri-list</code>
		</td>
	</tr>
</table>
<br />
<input type="hidden" name="ct" id="ct" value="text/uri-list" />
<input type="hidden" name="loc" id="loc" value="/pingbacks/validator/validate-provaq.php" />
<input id="submit" type="submit" value="Validate" /><div id="loading"><img src="<?php print $WEB_SUBFOLDER; ?>/theme/img/spinner.gif" style="height:1em" /> processing...</div>
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
				$('#loc').attr('value', '/pingbacks/validator/validate-proms.php');
			} else {
				$('#contenttype').html('Content Type: <input type="radio" name="contenttype" id="urilist" value="text/uri-list" checked="checked" /><code>text/uri-list</code>');
				$("#ct").attr('value', 'text/uri-list');
				$('#loc').attr('value', '/pingbacks/validator/validate-provaq.php');
			}
		});
		
		jQuery('body').on('click', 'input[name=contenttype]:radio', function () {
			$("#ct").attr('value', $(this).val());
		});

		jQuery('#submit').click(function(){
			// do post
			$.ajax({
				url: $('#loc').val(),
				type: "POST",
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