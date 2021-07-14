<?php
/**
 *
 * David \'Alvarez Rosa's personal website cache start PHP file.
 * Copyright (C) 2019-2021 David \'Alvarez Rosa
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 **/


$isMobile = is_numeric(stripos($_SERVER['HTTP_USER_AGENT'], 'mobile'));
$device = 'computer';
if ($isMobile)
    $device = 'mobile';
$theme = 'normal';
if ((isset($_GET['theme']) and $_GET['theme'] == 'suckless') or strpos(parse_url($_SERVER['REQUEST_URI'])['query'], 'theme=suckless') !== false)
    $theme =  'suckless';
$cacheFile = $_SERVER['DOCUMENT_ROOT'] . '/.cache/' . $_SERVER['HTTP_HOST'] .
             '-'. str_replace('/', '-', substr($_SERVER['PHP_SELF'], 1)) .
             '-' . $device . '-' . $theme;
$cacheTime = 3600;

if (file_exists($cacheFile) &&
    time() - $cacheTime <= filemtime($cacheFile)) {
    ob_clean();
    readfile($cacheFile);
    exit;
}
else {
    unlink($cacheFile);
}


ob_start();
?>
