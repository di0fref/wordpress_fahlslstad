<table border="{$border}" class="{$forum_table_class}">
	<tr>
		<th width="60%">Threads</th>
		<th class="center">Replies</th>
		<th class="center">views</th>
		<th>Last reply</th>
	</tr>
	{if $data}
		{foreach from=$data item=thread}
			<tr  class="{cycle values="odd,even"}">
				<td>
					<a class="bold" href="{$thread.href}">{$thread.subject}</a><br>
					<span class="small">{$thread.user}</span>
				</td>
				<td class="center">{$thread.post_count}</td>
				<td class="center">{$thread.views}</td>
				<td>{$thread.last_post|date_format:$config.date_format}</td>
			</tr>
		{/foreach}
	{else}
		<tr><td colspan="4" class="center bold">No threads yet.</td></tr>
	{/if}
</table>
