
<a name="ref_parser_SetWikiURL"></a>
<div class='api'>
	<div class='api_head'>void <b>BBCode::SetWikiURL</b> ( string $<tt>url</tt> )</div>
	<div class='api_descr'>This function changes the current wiki URL to the provided
		string.  When a [wiki="foo"] tag is encountered in the input (or its equivalent
		tag, [[foo]]), NBBC will use this URL to turn that tag into a <tt>&lt;a&gt;</tt>
		link:  This URL will be taken first, and the <a href="#ref_parser_Wikify">wiki-fied</a>
		page name ("foo") will be appended to it (with no characters added in between).
		See the section on <a href="usage_wiki">wiki-links</a> for more information on
		how to use this function.</div>
	<div class='api_info'><b>Parameters:</b>
		<ul>
		<li><i>url</i>:  A string to be inserted in the resulting <tt>&lt;a&nbsp;href=...&gt;</tt>
			element before the <a href="#ref_parser_Wikify">wiki-fied</a> page name.  For example,
			if this is "<tt>http://www.example.com/wiki.php?page=</tt>" and the page name given by
			the user is "<tt>George Washington</tt>", the resulting complete URL would be
			<tt>&lt;a&nbsp;href=\"http://www.example.com/wiki.php?page=George_Washington&gt;</tt>.
		</ul></div>
	<div class='api_info'><b>Return values:</b>  None.</div>
</div>

<a name="ref_parser_GetWikiURL"></a>
<div class='api'>
	<div class='api_head'>string <b>BBCode::GetWikiURL</b> ( )</div>
	<div class='api_descr'>This function returns the current wiki URL, as set by
		<a href="ref_parser_SetWikiURL">SetWikiURL()</a>.  If no wiki URL has been
		set, the return value is the same as that of
		<a href="ref_parser_GetDefaultWikiURL">GetDefaultWikiURL()</a>.</div>
	<div class='api_info'><b>Parameters:</b> None.</div>
	<div class='api_info'><b>Return values:</b>  The current wiki URL.</div>
</div>

<a name="ref_parser_GetDefaultWikiURL"></a>
<div class='api'>
	<div class='api_head'>string <b>BBCode::GetDefaultWikiURL</b> ( )</div>
	<div class='api_descr'>This function returns the default wiki URL, which is
		always <tt>"/?page=</tt>" --- this URL, when a wiki page name like "foo" is
		appended to the end of it, results in links that look like this:
		<tt>&lt;a&nbsp;href="/?page=foo"&gt;...&lt;/a&gt;</tt></div>
	<div class='api_info'><b>Parameters:</b> None.</div>
	<div class='api_info'><b>Return values:</b>  The default wiki URL, which
		is always <tt>"/?page=</tt>".</div>
</div>

<a name="ref_parser_Wikify"></a>
<div class='api'>
	<div class='api_head'>string <b>BBCode::Wikify</b> ( string $<tt>rawstring</tt> )</div>
	<div class='api_descr'>This function takes a raw string and cleans it up for use as
		a wiki page name.  To do this, it replaces all of the following characters with
		underscores:<br />
		<br />
		&nbsp;&nbsp;&nbsp;&nbsp;<tt>, ! ? ; @ # $ % \ ^ &amp; * &lt; &gt; = + ` ~ ' _ -</tt> &nbsp; &nbsp; and all whitespace (0x00-0x20).<br />
		<br />
		In addition, multiple successive underscores are condensed to a single underscore,
		and all initial and trailing underscores are removed.  Also, characters in the extended
		range (0x7F-0xFF) are replaced with their URL-encoded equivalents.
	</div>
	<div class='api_info'><b>Parameters:</b>
		<ul>
		<li><i>rawstring</i>: The string to clean up and make "wiki-friendly."</li>
		</ul>
	</div>
	<div class='api_info'><b>Return values:</b>  The resulting "clean" string.</div>
	<div class='api_info_block'><b>Notes:</b><br /><br />
		For example, "Washington, D.C."
		would become "<tt>Washington_D.C.</tt>", which is a string that can be safely used as
		part of a URL; while "&eacute;" might become "<tt>%E9</tt>", and "&eacute;glise" might
		become "<tt>%E9glise</tt>", depending on your current character encoding.  The
		output of this function is always URL-safe and 8-bit clean.<br /><br />
		
		Note that because accented characters like &eacute; and &uuml; and
		non-Roman characters like <span style='font-size:13pt;'>&#1488;</span> and <span style='font:13pt Batang,"MS Gothic","MS Mincho",Arial'>&#23470;</span> are converted to
		URL-encoded-equivalents, this function may not suitable for use with some non-Roman
		languages.<br /><br />
		
		The rationale behind URL-encoding the non-English characters is that some web servers
		and some web browsers are <i>not</i> 8-bit clean and may misbehave if they are
		sent a direct code value in this range.
	</div>
</div>

