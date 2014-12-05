<div class="forum-header-wrapper">
	<div class="forum-title">{$data.header}</div>
	<div class="forum-buttons">
		<ul>
			{foreach from=$buttons item=button key=name}
				<li>{$button}</li>
			{/foreach}
		</ul>
	</div>
</div>
{if $data.posts}
	{foreach from=$data.posts item=post name=posts_array}
		<div class="forum-post-wrapper">
			<div class="forum-left">
				<figure class="forum-figure">
					{$post.avatar}
					<figcaption><span class="bold">{if $post.user->data->display_name eq ""}
							Guest{else}{$post.user->data->display_name}</span><br><span
								class="small">Posts: {$post.user->data->post_count}</span>{/if}
					</figcaption>
				</figure>
			</div>
			<div class="forum-right {if $smarty.foreach.posts_array.first}forum-post-first{/if}">
				<div class="forum-post-meta">
					<!--<span class="post-author bold">{if $post.user->data->display_name eq ""}Guest{else}{$post.user->data->display_name}{/if}</span><br>-->
					<span class="small post-date">Posted: {$post.date|date_format:$config.date_format}</span>
				</div>
				<div class="forum-post-text">
					{$post.text|nl2br}
				</div>
				<div class="forum-post-signature border-top">
					{$post.user->description|nl2br}
				</div>
			</div>
		</div>
	{/foreach}
{else}
	<p class="bold center">No posts yet.</p>
{/if}
