{foreach from=$data item=cat}
	<table border="1">
		<tr>
			<th width="60%">{$cat.name}</th>
			<th class="center">Threads</th>
			<th class="center">Posts</th>
			<th>Last Post</th>
		</tr>
		{foreach from=$cat.forums item=forum}
			<tr>
				<td>
					<a href="{$forum.href}">{$forum.name}</a><br>
					<span class="small">{$forum.description}</span>
				</td>
				<td class="center">{$forum.thread_count}</td>
				<td class="center">{$forum.post_count}</td>
				<td>{$forum.last_post|date_format}</td>
			</tr>
		{/foreach}
	</table>
{/foreach}