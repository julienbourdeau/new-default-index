<?php 
/****************************************************************************
 *																			*
 *	 This file is suppose to replace the apache default index.php.			*
 * 	It displays folders and files in the current folder and link to them.   *
 * 	It is a simple standalone file, move it into your folders.				*
 *																			*
 * 	@Author: Julien Bourdeau <julien@sigerr.org>							*	
 * 	@URI: http://sigerr.org/												*			
 * 	@Since: August 2011														*
 * 	@Version: 2.0															*
 *																			*
 ****************************************************************************/


/*
 *   Set useful variable
 **************************************************/
	define('CDN_URL', 'http://cdn.sigerr.org/assets/');
	define('CUR_URL', 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME']);

	if ( isset($_GET['dir']) ){
		define('RELATIVE_BASE_PATH', $_GET['dir']);
		define('CUR_DIR', __DIR__.'/'.$_GET['dir']);
	}
	elseif ( isset($_GET['path']) ) {
		define('RELATIVE_BASE_PATH', 'TODO');
		define('CUR_DIR', $_GET['path']);
	}
	else {
		define('RELATIVE_BASE_PATH', '.');
		define('CUR_DIR', __DIR__);
	}

	$folder_icon_list = array(
							'std' => CDN_URL.'folder.png',
							'wp' => CDN_URL.'wp.png',
						);
	$file_icon_list = array('aac', 'ai', 'aiff', 'avi', 'css', 'doc', 'docx', 'gif', 'gzip', 'html', 'jpeg', 'jpg', 'js', 'ma', 'mov', 'mp', 'mpeg', 'mpg', 'mv', 'pdf', 'php', 'png', 'psd', 'raw', 'rtf', 'tar', 'tiff', 'txt', 'wav', 'wmv', 'zip');
	
/*
 *   Define Functions
 **************************************************/

function get_file_info( $file ) {
	global $file_icon_list;
	$data = array();

	//Set Name
	$filename = pathinfo($file);
	$data['name'] = shorten_name($filename['filename'], $filename['extension']);

	//Set Icon
	if ( in_array($filename['extension'], $file_icon_list) ) {
		$data['icon'] = CDN_URL . $filename['extension'] . ".png";
	} else {
		$data['icon'] = CDN_URL . "generic.png";
	}

	
	//Set Size
	$size = filesize($file);
	if ($size >= 1073741824) { // 1024x1024x1024
		$size_str = round($size/1073741824, 1) . ' Go';
	} elseif ($size >= 1048576) { // 1024x1024
		$size_str = round($size/1048576, 1) . ' Mo';
	} else {
		$size_str = round($size/1024, 1) . ' Ko';
	}
	//$data['size'] = $size;
	$data['size_str'] = $size_str;

	return $data;
}

function get_wp_version( $wp_folder ) {
	preg_match("/'(.*?)'/", exec( "grep wp_version ".$wp_folder."/wp-includes/version.php" ), $matches);
	return $matches[1];
}

function get_wordpress_last_version_number() {
	$link = `curl http://wordpress.org | grep '<a class="button download-button button-large"'`;
	preg_match_all("/\<a.*href=\"(.*?)\".*?\>(.*)\<\/a\>+/", $link, $matches, PREG_SET_ORDER);
	$last_version = str_replace("Download&nbsp;WordPress&nbsp;", '', $matches[0][2]);
	return $last_version;
}

function is_wordpress( $folder ) {
	if(
		file_exists($folder.'/wp-config.php')
		&&
		file_exists($folder.'/wp-admin/')
		&&
		file_exists($folder.'/wp-includes/')
	) return true;
	else
		return false;
}

function shorten_name( $name, $extension ) {
	if(strlen($name) > 42) {
		$str = substr($name, 0, 36);
		$str .= "...";
		$str .= substr($name, -3, 3);
		$str .= ".".$extension;
		return $str;
	} else {
		return $name.'.'.$extension;
	}
}

?>

<!DOCTYPE HTML>
<html lang="en_US">
<head>
	<meta charset="UTF-8">
	<title><?php echo basename(CUR_DIR); ?></title>
    
    <style type="text/css">
		
		@font-face {font-family: 'Museo-300';src: url('<?php echo CDN_URL;?>2429BD_1_0.eot');src: url('<?php echo CDN_URL;?>2429BD_1_0.eot?#iefix') format('embedded-opentype'),url('<?php echo CDN_URL;?>2429BD_1_0.woff') format('woff'),url('<?php echo CDN_URL;?>2429BD_1_0.ttf') format('truetype');}
		@font-face {font-family: 'Museo-500';src: url('<?php echo CDN_URL;?>2429BD_2_0.eot');src: url('<?php echo CDN_URL;?>2429BD_2_0.eot?#iefix') format('embedded-opentype'),url('<?php echo CDN_URL;?>2429BD_2_0.woff') format('woff'),url('<?php echo CDN_URL;?>2429BD_2_0.ttf') format('truetype');}
		@font-face {font-family: 'Museo-700';src: url('<?php echo CDN_URL;?>2429BD_0_0.eot');src: url('<?php echo CDN_URL;?>2429BD_0_0.eot?#iefix') format('embedded-opentype'),url('<?php echo CDN_URL;?>2429BD_0_0.woff') format('woff'),url('<?php echo CDN_URL;?>2429BD_0_0.ttf') format('truetype');}  

		body {
			background: #f9f9f9;
			color: #262626;
			margin: 0;
			font-family: 'Museo-300';
		}

		::selection{background:#222;color:#fff}
		::-moz-selection{background:#222;color:#fff}

		a {
			text-decoration: none;
			color: #999999;
		}
		a:hover {
			color: #F60 !important; /*vert sigerr: #6AB12F; */
		}
		#header {
			background: #262626;
			color: #f9f9f9;
			height: 40px;
		}
		#header a {
			color: #f9f9f9;
		}
		h2 {
			margin: 26px 0;
			font-size: 32px;
			border-bottom: 1px solid #262626;
			font-family: 'Museo-500';
		}
		#main {
			width: 672px;
			margin: 0 0 0 32px;
		}
		table {
			margin: 0 0 0 32px;
		}
		table tr td {
			overflow: hidden;
		}
		table a {
			margin: 0 0 0 6px;
			padding: 2px 4px;
			font-size: 21px;
			color: #333333;
		}
		table tr:odd {background: none;}
		table tr:even {background: #f2f2f2;}
		.tablefooter{
			font-size: 17px;
			color: #999999;
			font-style:italic;
		}
		#footer {
			margin: 42px 0;
			text-align:center;
			color: #999999;
		}
		.right {float: right;}
		.left {float: left;}
		.clear {clear:both;}

		
	</style>
    
</head>
<body>

	  <div id="wrapper">
        <div id="header">
    		<a href="http://sigerr.org/" class="left"><img border='0' src="<?php echo CDN_URL; ?>sigerr.org.png" /></a>
            
            <div id="email" class="right">
            	<table>
                	<tr><td>
            		<img src="<?php echo CDN_URL; ?>email.png" />
                    </td><td>  
                    <a href="mailto:julien@sigerr.org"><span class="htext">julien@sigerr.org</span></a>
                    </td></tr>
                </table>
                    
            </div>
        </div>
        
        <div class="clear"></div>
        
        <div id="main" role="main" class="container">
    			
                <section id="Directories">
                	<h2>Directories</h2>
                    <table cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th width="24px"></th>
                            <th width="512px"></th>
                            <th width="64px"></th>
                        </tr>
                    	<?php
                    	$count = 0;
						foreach (glob(CUR_DIR.'/*', GLOB_ONLYDIR ) as $dir) :
							$dirname = basename($dir);
							if (is_wordpress( $dir ))
								$type = 'wp';
							else
								$type = 'std';
						?>
							<tr>
								<td>
									<img src='<?php echo $folder_icon_list[$type]; ?>' />
								</td>
								<td>
									<a href="<?php echo CUR_URL.'?dir='.urlencode(RELATIVE_BASE_PATH.'/'.$dirname); ?>" title='<?php echo $dir; ?>'>
										<?php echo $dirname; ?>
									</a>
								</td>
								<td>
									<?php
										if ( $type == 'wp')
											echo 'v'.get_wp_version($dir);
									?>
								</td>
							</tr>
						<?php $count++; endforeach; ?>
                    </table>
                    <p class="tablefooter">The current folder contains <?php echo $count; ?> subfolders.</p>
                </section>
                
                <section id="files">
                	<h2>Files</h2>
                    <table cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th width="24px"></th>
                            <th width="512px"></th>
                            <th width="64px"></th>
                        </tr>
                    	<?php
						$count = 0;
						foreach (glob(CUR_DIR.'/*.*') as $file):
							if ( $file == "index.php" ) continue; //dont display the index.php
							if ( is_dir($file) ) continue; //dont display directories

							$data = get_file_info($file);
						?>
							<tr>
								<td>
									<img src='<?php echo $data['icon']; ?>' />
								</td>
								<td>
									<a href='<?php echo RELATIVE_BASE_PATH.'/'.$data['name']; ?>' title='<?php echo $file; ?>'>
										<?php echo $data['name']; ?>
									</a>
								</td>
								<td>
									<?php $data['size_str']; ?>
								</td>
							</tr>
						<?php $count++; endforeach; ?>
                    </table>
                    <p class="tablefooter">The current folder contains <?php echo $count; ?> files.</p>
                </section>
                
                
        </div> <!--! end of #main -->    
        
        <div id="footer">
    		<p>All rights reserved - <a href="http://sigerr.org/">Julien Bourdeau</a> - <a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>">http://<?php echo $_SERVER['SERVER_NAME']; ?></a>
        </div>
      </div> <!--! end of #container -->
	
</body>
</html>
