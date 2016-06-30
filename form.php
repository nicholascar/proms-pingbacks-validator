<h3>Paste your pingback message into the text area below and then select options.</h3>
<textarea name="msg" id="msg" style="width:700px;min-height:300px;">
</textarea>
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
			Content Type <code>text/uri-list</code>
		</td>
	</tr>
</table>
<br />
<input type="submit" value="Validate" />

<script src="jquery-3.0.0.min.js"></script>
<script>
	jQuery(document).ready(function(){
		jQuery('input[name=msgtype]:radio').click(function(){
			if($('#typeproms').is(':checked')) { 
				var contenttypes = 'Content Type:<br />' +
									'<input type="radio" name="contenttype" id="turtle" value="turtle" checked-"checked" /><code>text/turtle</code><br />' + 
									'<input type="radio" name="contenttype" id="n3" value="n3" /><code>text/n3</code><br />' +
									'<input type="radio" name="contenttype" id="xml" value="xml" /><code>application/rdf+xml</code><br />' +
									'<input type="radio" name="contenttype" id="jsonld" value="ld" /><code>application/ld+json</code>';
				$('#contenttype').html(contenttypes);
			} else {
				$('#contenttype').html('Content Type <code>text/uri-list</code>');
			}
		});
	});
</script>