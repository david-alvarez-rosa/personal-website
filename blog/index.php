<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite index PHP file.
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


include 'resources/cacheStart.php';
?>


<!DOCTYPE html>

<html lang="en">
  <?php
  include 'resources/comment.html';
  include 'resources/minify.php';
  ?>

  <head>
    <title>David Álvarez Rosa | Personal Blog</title>
    <meta charset="UTF-8" />
    <meta name="description" content="My personal blog site. Stuff I've written,
                mainly about Technology and
                Mathematics. By David Álvarez Rosa." />
    <meta name="keywords" content="David Álvarez Rosa, David Álvarez, David,
                Blog, Mathematics, Engineering, Technology,
                Blogsite, Entry, Entries" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://blog.alvarezrosa.com" />
    <link rel="apple-touch-icon" sizes="180x180" href="img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="img/icons/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="img/icons/safari-pinned-tab.svg" color="#816363">
    <link rel="shortcut icon" href="img/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#DBDCDC">
    <meta name="msapplication-config" content="img/icons/browserconfig.xml">
    <meta name="theme-color" content="#FFFFFF">
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/blog.css" />
    <link rel="stylesheet" href="css/animations.css" />
    <link rel="stylesheet" id="fontawesome"
          data-href="external/fontawesome/css/all.min.css" />
    <?php
    if (isset($_GET['theme']) and $_GET['theme'] == 'suckless') { ?>
      <link rel="stylesheet" href="css/suckless.css" />
    <?php } ?>
  </head>


  <body class="preload">
    <?php
    $currentSite = 'blog';
    $sections = [
        ['name' => 'Subscribe', 'icon' => 'fas fa-rss-square'],
        ['name' => 'Posts', 'icon' => 'fas fa-blog'],
    ];
    include 'resources/functions.php';
    include 'resources/bodyPreMain.php';
    ?>


    <!-- Main. -->
    <main>
      <p class="fadeIn" style="margin-top: 2.5em;">
        This website does not (and won't ever) use cookies. I value your
        privacy.
      </p>

      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          You can <strong>subscribe</strong> to this blog by using this
          <a href="rss.xml"
             title="RSS subscription file.">
            RSS file <i class="fas fa-rss-square"></i></a>.
        </p>
        <p>
          <a href="http://www.whatisrss.com/"
             title="What Is RSS? RSS Explained.">
            RSS <i class="fas fa-external-link-alt"></i></a>
          (Rich Site Summary) is a format for <strong>delivering</strong>
          regularly changing <strong>web content</strong>. It allows you to
          easily stay informed by retrieving the latest content from the sites
          you are interested in and allows you to ensure your
          <strong>privacy</strong>, by not needing to join each site's email
          newsletter.
        </p>
        <p>
          If you are not familiar with RSS readers and you are an
          <a href="https://www.gnu.org/software/emacs/"
             title="GNU Emacs official website.">
            Emacs <i class="fas fa-external-link-alt"></i></a>
          user, I recommend you
          <a href="https://nullprogram.com/blog/2013/09/04/"
             title="Introducing Elfeed, an Emacs Web Feed Reader.">
            Elfeed <i class="fas fa-external-link-alt"></i></a>
          (created by
          <a href="https://nullprogram.com/about/"
             title="Chris Wellons (skeeto) personal website.">
            Chris Wellons <i class="fas fa-external-link-alt"></i></a>).

        </p>
      </section>

      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          Below, you will find the latest blog posts summarized and listed in
          reverse chronological order.
        </p>

        <?php include 'resources/blog/entries.php'; ?>

      </section>
    </main>


    <!-- License (Creative Commons). -->
    <?php include 'resources/license.php'; ?>


    <!-- Footer. -->
    <?php include 'resources/footer.php'; ?>


    <!-- Javascript files. -->
    <script src="js/main.js"></script>
    <script src="js/shortcuts.js"></script>
    <script src="js/confetti.js"></script>
  </body>
</html>


<?php
ob_end_flush();
include 'resources/cacheEnd.php';
?>
