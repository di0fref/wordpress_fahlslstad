<div class="forum-header-wrapper">
	<div class="forum-buttons">
		<ul>
			{foreach from=$buttons item=button key=name}
				<li>{$button}</li>
			{/foreach}
		</ul>
	</div>
</div>

<table border="{$border}" class="{$forum_table_class}">
	<tr>
		<th></th>
		<th width="60%">Threads</th>
		<th class="center">Replies</th>
		<th class="center">views</th>
		<th class="align-right">Last reply</th>
	</tr>
	{if $data}
		{foreach from=$data item=thread}
			<tr class="{cycle values="odd,even"}">
				<td class="forum-thread-image">
					<span title="" class="thread-icon {$thread.icon}"></span>
				</td>
				<td>
					{if $thread.status == "sticky"}<span class="bold ">Sticky:</span>{/if} <a class="bold bigger"
																							  href="{$thread.href}">{$thread.subject}</a><br>
					<span class="small">{if $thread.last_post eq ""}No posts yet.{else}{$thread.last_post|date_format:$config.date_format}{/if}</span>
				</td>
				<td class="center">{$thread.post_count}</td>
				<td class="center">{$thread.views}</td>
				<td class="align-right small">
					by {if $thread.last_poster.display_name eq ""}Guest{else}{$thread.last_poster.display_name}{/if} {$thread.last_poster.avatar}</td>
			</tr>
		{/foreach}
	{else}
		<tr>
			<td colspan="4" class="center bold">No threads yet.</td>
		</tr>
	{/if}
</table>

