<?php
	function array_prefix_values($prefix, $array)
	{
		$callback = create_function('$s','return "'.$prefix.'".$s;');
		return array_map($callback,$array);
	}
	
	function get_last_update()
	{
		if (func_num_args()< 1) return 0;
		$dirs = func_get_args();
		$files = array();
		foreach ($dirs as $dir)
		{
			$subfiles = scandir($dir);
			$subfiles = array_prefix_values($dir,$subfiles);
			$subfiles = array_filter($subfiles,"is_file");
			$files = array_merge($files,$subfiles);
		}
		$maxtimestamp = 0;
		foreach ($files as $file)
		{
			$timestamp = filemtime($file);
			if ($timestamp> $maxtimestamp)
			{
				$maxtimestamp = $timestamp;
			}
		}
		error_reporting(E_ALL);
		ini_set('display_errors', TRUE);
		setlocale (LC_TIME, 'fr_FR.utf8','fr_FR','fra');//pas en localhost
		return ucwords(strftime("%B %Y",$maxtimestamp));
	}
	
?>
<footer>
	Sébastien Linck - <?php echo get_last_update(".", "./css/", "./pages/");?> - <a href="mentions-legales">Mentions légales</a>
</footer>