<?php 
/****************************************************************************
 *																			*
 *	 This file is suppose to replace the apache default index.php.			*
 * 	It displays folders and file in a folder and link to them, it is a 		*
 * 	simple standalone file, move it into your folders.						*
 *																			*
 * 	@Author: Julien Bourdeau <julien@sigerr.org>							*	
 * 	@URI: http://sigerr.org/												*			
 * 	@Since: August 2011														*
 * 	@Version: 1.0															*
 *																			*
 ****************************************************************************/


/*
 *   Set useful variable
 **************************************************/
	define('URL_IMG', 'http://sigerr.org/img/');
	$cur_dir = urldecode(basename($_SERVER['REQUEST_URI']));
	//print_r($_SERVER);
	$url_folder_img = URL_IMG."folder.png";
	$url_file_img = URL_IMG."file.png";
	
/*
 *   Define Functions
 **************************************************/

function name($name) {
	if(strlen($name) > 42) {
		$name = substr($name, 0, 42);
		$name .= "...";
	}
	return $name;
}
 
 
?>

<!DOCTYPE HTML>
<html lang="en_US">
<head>
	<meta charset="UTF-8">
	<title><?php echo $cur_dir; ?></title>
    
    <style type="text/css">
		body {
			background: #f9f9f9;
			color: #262626;
			margin: 0;
		}
		#header {
			background: #262626;
			height: 37px;
		}
		a {
			text-decoration: none;
			color: #999999;
		}
		a:hover {
			color: #F60; /*vert sigerr: #6AB12F; */
		}
		h2 {
			margin: 26px 0;
			font-size: 32px;
			border-bottom: 1px solid #262626;
		}
		.htext {
			font-size: 17px;
			color: #f9f9f9;
		}
		#email {
			margin: 0 16px 0 0;
		}
		#main {
			width: 764px;
			margin: 0 50px;
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
		table a:active,
		table a:hover {
			/*background: #C30;*/
		}
		.tablefooter{
			font-size: 17px;
			color: #999999;
			font-style:italic;
		}
		#footer {
			margin: 22px 0;
			text-align:center;
			color: #999999;
		}
		.odd {background: none;}
		.even {background: #f2f2f2;}
		.right {float: right;}
		.left {float: left;}
		.clear {clear:both;}

		
	</style>
    
</head>
<body>

	  <div id="container">
        <div id="header">
    		<a href="http://sigerr.org/" class="left"><img border='0' src="<?php echo URL_IMG; ?>sigerr.org.png" /></a>
            
            <div id="email" class="right">
            	<table>
                	<tr><td>
            		<img src="<?php echo URL_IMG; ?>email.png" />
                    </td><td>  
                    <a href="mailto:julien@sigerr.org"><span class="htext">julien@sigerr.org</span></a>
                    </td></tr>
                </table>
                    
            </div>
            
        </div>
        
        <div class="clear"></div>
        
        <div id="main" role="main">
    			
                <section id="Directories">
                	<h2>Directories</h2>
                    <table cellspacing="0" cellpadding="0">
                    	<tr>
                        	<th width="24px"></th>
                            <th width="512px"></th>
                            <th width="64px"></th>
                        </tr>
                    	<?php $count = 1;
						foreach(glob('*', GLOB_ONLYDIR) as $dir){
							echo "<tr class='";
									echo ($count%2 == 0)?"even":"odd";
									echo "'> \r\n";
								echo "\t <td> \r\n \t\t <img src='$url_folder_img' />\r\n \t </td> \r\n";
								echo "\t <td> \r\n \t\t <a href='$dir' title='$dir'> ";
									echo name($dir);
									echo "</a>\r\n \t </td> \r\n";
								echo "\t <td>";
									echo "\r\n \t </td> \r\n";
							echo "</tr> \r\n";
							$count++;
						}
						?>
                    </table>
                    <p class="tablefooter">The current folder contains <?php echo $count-1; ?> subfolders.</p>
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
						$count = 1;
						foreach(glob('*.*') as $file){
							if($file == "index.php") continue; //dont display the index.php
							echo "<tr class='";
									echo ($count%2 == 0)?"even":"odd";
									echo "'> \r\n";
								echo "\t <td> \r\n \t\t <img src='$url_file_img' />\r\n \t </td> \r\n";
								echo "\t <td> \r\n \t\t <a href='$file' title='$file'> ";
									echo name($file);
									echo "</a>\r\n \t </td> \r\n";
								echo "\t <td>";
									$size = filesize($file);
									if($size >= 1048576) { // 1024x1024
										echo round($size/1048576, 1); echo ' Mo';
									}else{
										echo round($size/1024, 1); echo ' Ko';
									}
									echo "\r\n \t </td> \r\n";
							echo "</tr> \r\n";
							$count++;
						}
						?>
                    </table>
                    <p class="tablefooter">The current folder contains <?php echo $count-1; ?> files.</p>
                </section>
                
                
        </div> <!--! end of #main -->    
        
        <div id="footer">
    		<p>All rights reserved - <a href="http://sigerr.org/">Julien Bourdeau</a> - http://<?php echo $_SERVER['SERVER_NAME']; ?>
        </div>
      </div> <!--! end of #container -->
	
</body>
</html>