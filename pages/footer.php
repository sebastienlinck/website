<?php
// Formatter pour afficher le mois et l'année en français
$fmt = datefmt_create(
	'fr_FR',
	IntlDateFormatter::FULL,
	IntlDateFormatter::NONE,
	'Europe/Paris',
	IntlDateFormatter::GREGORIAN,
	'MMMM yyyy'
);

/**
 * Retourne le timestamp (int) du fichier le plus récemment modifié dans $dirName.
 * Si erreur ou aucun fichier, retourne 0.
 */
function mostRecentModifiedFileTime($dirName, $doRecursive)
{
	$dirName = rtrim($dirName, '/\\');
	if (!is_dir($dirName)) {
		return 0;
	}

	$lastModified = 0;

	if ($doRecursive) {
		try {
			$iterator = new RecursiveIteratorIterator(
				new RecursiveDirectoryIterator($dirName, FilesystemIterator::SKIP_DOTS)
			);
			foreach ($iterator as $fileInfo) {
				if ($fileInfo->isFile()) {
					$mtime = $fileInfo->getMTime();
					if ($mtime > $lastModified) {
						$lastModified = $mtime;
					}
				}
			}
		} catch (Exception $e) {
			// En cas d'erreur (permissions, etc.), retourner 0
			return 0;
		}
	} else {
		$entries = scandir($dirName);
		if ($entries === false) {
			return 0;
		}
		foreach ($entries as $entry) {
			if ($entry === '.' || $entry === '..') continue;
			$path = $dirName . DIRECTORY_SEPARATOR . $entry;
			if (is_file($path)) {
				$mtime = filemtime($path);
				if ($mtime > $lastModified) $lastModified = $mtime;
			}
		}
	}

	return $lastModified;
}
// Récupération et affichage de la date la plus récente (mois + année)
// Utilise un cache temporaire pour éviter de rescanner le disque à chaque requête
$cacheFile = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'slinck_footer_ts.cache';
$cacheTtl = strtotime('first day of next month midnight') - time();
// garantir un TTL minimal (au cas où la computation serait erronée)
$cacheTtl = $cacheTtl > 60 ? $cacheTtl : 60;
$ts = 0;
// lire le cache si valide
if (is_file($cacheFile)) {
	$cached = @file_get_contents($cacheFile);
	if ($cached !== false) {
		$cached = intval($cached);
		if ($cached > 0) {
			// Si le timestamp mis en cache appartient au même mois/année que le mois courant,
			// on peut réutiliser la valeur sans rescanner : le libellé mois+année resterait identique.
			if (date('Ym', $cached) === date('Ym')) {
				$ts = $cached;
			} elseif (time() - filemtime($cacheFile) < $cacheTtl) {
				// sinon, respecter le TTL existant
				$ts = $cached;
			}
		}
	}
}
// recalculer si pas de cache valide
if ($ts === 0) {
	// utiliser le dossier parent de pages/ comme racine du site
	$ts = mostRecentModifiedFileTime(__DIR__ . '/..', true);
	if ($ts > 0) {
		@file_put_contents($cacheFile, (string)$ts, LOCK_EX);
	}
}

if ($ts <= 0) {
	$displayDate = 'Date inconnue';
} else {
	$displayDate = ucwords(strtolower(datefmt_format($fmt, $ts)));
}
?>
<footer>
	Sébastien Linck - <?php echo htmlspecialchars($displayDate, ENT_QUOTES, 'UTF-8'); ?> - <a href="mentions-legales" rel="nofollow">Mentions légales</a>
</footer>