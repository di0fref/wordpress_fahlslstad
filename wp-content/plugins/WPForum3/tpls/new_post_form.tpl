<div class="forum-trail">{$trail}</div>
<h3>Reply to: {$thread_name}</h3>

<form name="forum-form-new-post" id="forum-form-new-post" method="post">
	<table class="forum-table" border="0">
		<tr>
			<td>
				<textarea style="width:500px; height:200px" name="text" id="bbcode" required>
{if $quote_data}[quote]{$quote_data.quote_text}[b]{$quote_data.user->display_name}[/b] @ {$quote_data.post.date|date_format:$config.date_format}
{$quote_data.post.text}[/quote]{/if}</textarea></td>
		</tr>
		<tr>
			<td>
				<input type="submit" name="forum-form-new-post" value="Post Reply">
			</td>
		</tr>
	</table>
	<input type="hidden" name="record" value="{$record}">
	<input type="hidden" name="nonce" value="{$nonce}">
</form>
