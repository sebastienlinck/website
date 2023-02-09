<?php
	$fmt = datefmt_create('fr_FR',IntlDateFormatter::FULL,IntlDateFormatter::FULL,'Europe/Paris',IntlDateFormatter::GREGORIAN,'MMMM YYYY');
	function mostRecentModifiedFileTime($dirName, $doRecursive)
	{
		$d = dir($dirName);
		$lastModified = 0;
		while ($entry = $d->read()) {
			if ($entry != "." && $entry != "..") {
				if (!is_dir($dirName . "/" . $entry)) {
					$currentModified = filemtime($dirName . "/" . $entry);
					} else if ($doRecursive && is_dir($dirName . "/" . $entry)) {
					$currentModified = mostRecentModifiedFileTime($dirName . "/" . $entry, true);
				}
				if ($currentModified > $lastModified) {
					$lastModified = $currentModified;
				}
			}
		}
		$d->close();
		return  $lastModified;
	}
?>
<footer>
	Sébastien Linck - <?php  echo ucwords(datefmt_format($fmt, mostRecentModifiedFileTime(".", 1))); ?> - <a href="mentions-legales" rel="nofollow">Mentions légales</a>
</footer>