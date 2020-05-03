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
<link rel="stylesheet" href="/css/main.css" />
<link rel="stylesheet" href="/css/blog.css" />
<link rel="stylesheet" href="/css/animations.css" />
<link rel="stylesheet" href="/external/fontawesome/css/fontawesome.min.css" />
<link rel="stylesheet" href="/external/fontawesome/css/solid.min.css" />
<link rel="stylesheet" href="/external/fontawesome/css/brands.min.css" />
<?php
for ($i = 0; $i < sizeof($cssExtra); ++$i)
    echo '<link rel="stylesheet" href="' . $cssExtra[$i] . '" />';
?>
<script type="application/ld+json">
 {
		 "@context":"http://schema.org",
		 "@type": "BlogPosting",
		 "image": "https://blog.alvarezrosa.com/img/blog/<?php echo $entryId . '/' . $image; ?>"
		 "url": "https://blog.alvarezrosa.com/hello-world.php",
		 "headline": "<?php echo $entry['title']; ?>",
		 "dateCreated": "2019-02-11T11:11:11",
		 "datePublished": "2019-02-11T11:11:11",
		 "dateModified": "2019-02-11T11:11:11",
		 "inLanguage": "en-US",
		 "isFamilyFriendly": "true",
		 "copyrightYear": "2020",
		 "copyrightHolder": "",
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
		 "genre": ["SEO", "JSON-LD"],
		 "articleSection": "Uncategorized posts",

		 "articleBody": "<?php echo preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", strip_tags($content)); ?>"
 }
</script>
