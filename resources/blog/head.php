<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite head PHP file.
 * Copyright (C) 2019-2020 David \'Alvarez Rosa
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
?>


<title><?php echo $entry['title'] ?> | David Álvarez Rosa | Personal Blog</title>
<meta charset="UTF-8" />
<meta name="description" content="<?php echo strip_tags($abstract); ?>" />
<meta name="keywords" content="<?php
                               for ($i = 0; $i < sizeof($keywords); ++$i) {
                                   if ($i != 0)
                                       echo ', ';
                                   echo $keywords[$i];
                               }
                               for ($i = 0; $i < sizeof($basicKeywords); ++$i) {
                                   if ($i != 0)
                                       echo ', ';
                                   echo $basicKeywords[$i];
                               }
                               ?>" />
<meta name="author" content="<?php echo $authors[$entry['author']]['name']; ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="canonical" href="https://blog.alvarezrosa.com/<?php echo $entryId; ?>" />
<link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
<link rel="manifest" href="/site.webmanifest">
<link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#816363">
<link rel="shortcut icon" href="/img/icons/favicon.ico">
<meta name="msapplication-TileColor" content="#DBDCDC">
<meta name="msapplication-config" content="/img/icons/browserconfig.xml">
<meta name="theme-color" content="#FFFFFF">
<meta name="msapplication-TileColor" content="#DBDCDC" />
<meta name="msapplication-config" content="img/icons/browserconfig.xml" />
<meta name="theme-color" content="#FFFFFF" />

<!-- Meta tags for social media. -->
<meta property="og:title"
      content="<?php echo $entry['title']; ?> | David Álvarez Rosa | Personal Blog" />
<meta property="og:description" content="<?php echo strip_tags($abstract); ?>" />
<meta property="og:image"
      content="https://blog.alvarezrosa.com/<?php echo $entryId; ?>/img/<?php echo $image; ?>" />
<meta property="og:url"
      content="https://blog.alvarezrosa.com/<?php echo $entryId; ?>" />
<meta property="og:type" content="website" />
<meta name="twitter:card" content="summary_large_image" />

<!-- CSS files. -->
<link rel="stylesheet" href="/css/main.css" />
<link rel="stylesheet" href="/css/blog.css" />
<link rel="stylesheet" href="/css/animations.css" />
<link rel="stylesheet" id="fontawesome"
      data-href="/external/fontawesome/css/all.min.css" />
<?php
for ($i = 0; $i < sizeof($cssExtra); ++$i)
    echo '<link rel="stylesheet" href="' . $cssExtra[$i] . '" />';
?>

<!-- Javascript files. -->
<script defer src="/js/main.js"></script>
<script defer src="/js/shortcuts.js"></script>
<script defer src="/js/confetti.js"></script>
<?php
for ($i = 0; $i < sizeof($jsExtra); ++$i)
    echo $jsExtra[$i];
?>
<script defer src="/js/blog.js"></script>

<script type="application/ld+json">
 {
		 "@context":"http://schema.org",
		 "@type": "BlogPosting",
		 "image": "https://blog.alvarezrosa.com/<?php echo $entryId . '/img/' . $image; ?>",
		 "url": "https://blog.alvarezrosa.com/<?php echo $entryId ?>",
		 "headline": "<?php echo $entry['title']; ?>",
		 "dateCreated": "<?php echo $entry['date']; ?>",
		 "inLanguage": "en-US",
		 "isFamilyFriendly": "true",
		 "copyrightYear": "<?php echo date("Y"); ?>",
		 "copyrightHolder": "<?php echo $authors[$entry['author']]['name']; ?>",
		 "author": {
			   "@type": "Person",
			   "name": "<?php echo $authors[$entry['author']]['name']; ?>",
			   "url": "<?php echo $authors[$entry['author']]['webUrl']; ?>"
		 },
		 "creator": {
			   "@type": "Person",
			   "name": "<?php echo $authors[$entry['author']]['name']; ?>",
			   "url": "<?php echo $authors[$entry['author']]['webUrl']; ?>"
		 },
		 "genre": [
         <?php
         $tags = $entry['tags'];
         for ($i = 0; $i < sizeof($tags); ++$i) {
             if ($i != 0)
                 echo ', ';
             echo '"' . $tags[$i] . '"';
         }
         ?>
     ],
		 "articleBody": "<?php echo preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", strip_tags($content)); ?>"
 }
</script>
