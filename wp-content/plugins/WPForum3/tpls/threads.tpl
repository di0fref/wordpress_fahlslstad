<table border="1">
	<tr>
		<th width="60%">Threads</th>
		<th class="center">Replies</th>
		<th class="center">views</th>
		<th>Last reply</th>
	</tr>
	{foreach from=$data item=thread}
		<tr>
			<td>
				<a href="{$thread.href}">{$thread.subject}</a><br>
				<span class="small">{$thread.user}</span>
			</td>
			<td class="center">{$thread.post_count}</td>
			<td class="center">{$thread.views}</td>
			<td>{$thread.last_post|date_format}</td>
		</tr>
	{/foreach}
</table>
