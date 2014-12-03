<?php
class fref_Themes{
	
	protected $thumbs_path = "wp-content/theme_pics/";
	protected $download_path = "wp-content/downloads/";
	protected $themes;
	protected $output = "Enjoy.";
	protected $option_prefix = "themes_";
	protected $demo = "demo?theme=";
	protected $url = "http://fahlstad.se/";
	protected $tbl = "themes";
	
	public function __construct()
	{
	
	}
	
	public function init()
	{
		//wp_register_script("front_script", plugins_url('/js/front.js', __FILE__));
		//wp_enqueue_script("front_script");
	}
	

	public function display($content)
	{
		if(!preg_match('|<!--THEMES-->|', $content))	
			return $content;

		$this->loadThemes();
		$this->output .= '<div id="themes_wrap">';
		

		
		foreach($this->themes as $theme){

			$download_link = "/wp-content/plugins/themes/download.php?dl_theme=$theme->id";

			//$download_link = add_query_arg(array("file" => "11"), "/wp-content/plugins/themes/download.php");

			
			$thumb = "<img src='".IMAGE_URL."$theme->thumb&amp;height=".get_option("themes_options_thumb_height")."&amp;width=".get_option("themes_options_thumb_width")."' alt='image' />";
			$full = FULL_URL.$theme->thumb;
			$this->output .= "
				<table width='100%' border=0 cellspacing=0 cellpadding=0>
					<tr>
						<td class='theme_thumb' valign='top'><div class='shadow'>$thumb</div></td>
						<td class='theme_name' valign='top'><span>$theme->name</span><br /><i><small>Updated: ".date("F jS, Y", strtotime($theme->date_updated))."<br>Version: $theme->version</small></i></td>
						<td class='theme_desc' valign='top'>$theme->description <p><br><a class='download_link' id='$theme->id' href='$download_link'>Download</a></p></td>
						";
						//$this->output .=" | <a href='#'>Demo</a>(Soon)</td>";
						//$this->output .= " | <a href='http://demo.fahlstad.se?wptheme=$theme->name'>Demo</a>";
					$this->output .= "</tr></table>";
	
		}
		$this->output .= "</div>";
		return preg_replace("|<!--THEMES-->|", $this->output, $content);
	}
	
	protected function loadThemes()
	{
		global $table_prefix, $wpdb;
		$sql = "SELECT * FROM {$table_prefix}themes ORDER BY date_added DESC";
		$this->themes = $wpdb->get_results($sql);
	}

	public function head()
	{
		?>
		<style type="text/css" media="screen">
			#themes_wrap{
				__background:#f4f4f4;
			}
			#themes_wrap td{
				_border:1px solid #ccc;
				border-collapse:collapse;
				padding:5px;
			}
			#themes_wrap td{
				
			}
			#themes_wrap td.theme_thumb img{
				padding:5px;
				background:none;
				_border:none;
			}
			#themes_wrap td.theme_thumb{
				width:<?php echo get_option("themes_options_thumb_width");?>px;
			}
			#themes_wrap td.theme_name{
				width:150px;
			}
			#themes_wrap td.theme_name span{
				font-weight:bold;
			}
			#themes_wrap td.theme_desc{
				width:230px;
				font-size:small;
				font-style:italic;
			}
			#themes_wrap hr{
				border-bottom:1px solid #eee;
				margin:15px 0 15px 0;
			}
			
			#themes_wrap .shadow {
				-moz-box-shadow: 2px 2px 5px #bbb;
				-webkit-box-shadow: 2px 2px 5px #bbb;
				box-shadow: 2px 2px 5px #bbb;
				width: <?php echo get_option("themes_options_thumb_width")+10; ?>px;
				/* For IE 8 */
				-ms-filter: "progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#999999')";
				/* For IE 5.5 - 7 */
				filter: progid:DXImageTransform.Microsoft.Shadow(Strength=4, Direction=135, Color='#99999');
			}
			.download_link {
				color: #ffffff;
				text-shadow: 0 -1px 0 rgba(0,0,0,0.25);
				background-image: linear-gradient(to bottom,#62c462,#51a351);
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#ff62c462',endColorstr='#ff51a351',GradientType=0);
				background-color: #5bb75b;
				background-image: -moz-linear-gradient(top,#62c462,#51a351);
				background-image: -ms-linear-gradient(top,#62c462,#51a351);
				background-image: -webkit-gradient(linear,0 0,0 100%,from(#62c462),to(#51a351));
				background-image: -webkit-linear-gradient(top,#62c462,#51a351);
				background-image: -o-linear-gradient(top,#62c462,#51a351);
				background-image: linear-gradient(top,#62c462,#51a351);
				background-repeat: repeat-x;
				filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#62c462',endColorstr='#51a351',GradientType=0);
				border-color: #51a351 #51a351 #387038;
				border-color: rgba(0,0,0,0.1) rgba(0,0,0,0.1) rgba(0,0,0,0.25);
				filter: progid:DXImageTransform.Microsoft.gradient(enabled = false);
				padding: 5px 7px;
				margin-top: 4px;;
				/*font-size: 17.5px;*/
				-webkit-border-radius: 3px;
				-moz-border-radius: 3px;
				border-radius: 3px;
			}
		</style>

		<?php
	}
}



?>