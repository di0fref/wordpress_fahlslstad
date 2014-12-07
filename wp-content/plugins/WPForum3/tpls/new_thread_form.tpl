<h3>Start New Topic</h3>
<form name="forum-form-new-thread" id="forum-form-new-thread" method="post" action="{$action}">
	<table class="forum-table" border="0">
		<tr>
			<td>Subject:<br><input style="width:200px;" type="text" name="subject" required></td>
		</tr>
		<tr>
			<td><textarea style="width:500px; height:200px" name="text" id="bbcode" required></textarea></td>
		</tr>
		<tr>
			<td>
				<input type="submit" name="forum-form-new-thread" value="Submit New Topic">
			</td>
		</tr>
	</table>
	<input type="hidden" name="record" value="{$record}">

</form>

