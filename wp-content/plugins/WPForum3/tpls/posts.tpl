<div class="forum-trail">{$trail}</div>
<div class="forum-header-wrapper">
	<div class="forum-title">{$data.prefix}{$data.header}</div>
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
					<figcaption><span class="bold">{if $post.user->display_name eq ""}
							Guest{else}{$post.user->display_name}</span><br><span
								class="small">Posts: {$post.user->post_count|number_format:0}</span>{/if}
					</figcaption>
				</figure>
			</div>
			<div class="forum-right {if $smarty.foreach.posts_array.first}forum-post-first{/if}">
				<div class="forum-post-meta">
					<!--<span class="post-author bold">{if $post.user->display_name eq ""}Guest{else}{$post.user->display_name}{/if}</span><br>-->
					<span class="small post-date">Posted: {$post.date|date_format:$config.date_format}</span>
				</div>
				<div class="forum-post-text">
					{$post.text|nl2br}
				</div>
				{if $post.user->meta.description}
					<div class="forum-post-signature border-top">
						{$post.user->meta.description|nl2br}
					</div>
				{/if}
				<div class="forum-post-links">
					<ul>
						{foreach from =$post.post_links item=link key=name}
							<li class="small">
								<a href="{$link.href}">{$link.text}</a>
							</li>
						{/foreach}
					</ul>
				</div>
			</div>
		</div>
	{/foreach}
{else}
	<p class="bold center">No posts yet.</p>
{/if}
