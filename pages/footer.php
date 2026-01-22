<?php
$cacheFile = sys_get_temp_dir() . '/site_ts.cache';
$ts = (is_file($cacheFile) && date('Ym', filemtime($cacheFile)) === date('Ym')) 
    ? (int)file_get_contents($cacheFile) 
    : 0;

if ($ts <= 0) {
    $it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(__DIR__ . '/..', FilesystemIterator::SKIP_DOTS));
    foreach ($it as $file) {
        $ts = max($ts, $file->getMTime());
    }
    file_put_contents($cacheFile, $ts);
}

$fmt = new IntlDateFormatter('fr_FR', 3, -1, 'Europe/Paris', 1, 'MMMM yyyy');
$displayDate = mb_convert_case($fmt->format($ts), MB_CASE_TITLE, "UTF-8");
?>

<footer>
    Sébastien Linck - <?= htmlspecialchars($displayDate) ?> - <a href="mentions-legales" rel="nofollow">Mentions légales</a>
</footer>