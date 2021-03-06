<?php
/**
 *
 * David \'Alvarez Rosa's personal website 404 error PHP file.
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
    <title>404 Error | David Álvarez Rosa | Personal Website</title>
    <meta charset="UTF-8" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://david.alvarezrosa.com/404.php" />
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
    <link rel="manifest" href="/site.webmanifest">
    <link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#816363">
    <link rel="shortcut icon" href="/img/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#DBDCDC">
    <meta name="msapplication-config" content="/img/icons/browserconfig.xml">
    <meta name="theme-color" content="#FFFFFF">

    <!-- CSS files. -->
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/animations.css" />
    <link rel="stylesheet" href="/css/404.css" />
    <link rel="stylesheet" id="fontawesome"
          data-href="/external/fontawesome/css/all.min.css" />
    <?php
    if (strpos(parse_url($_SERVER['REQUEST_URI'])['query'], 'theme=suckless') !== false) {
    ?>
      <link rel="stylesheet" href="/css/suckless.css" />
    <?php } ?>

    <!-- Javascript files. -->
    <script defer src="/js/main.js"></script>
    <script defer src="/js/shortcuts.js"></script>
    <script defer src="/js/confetti.js"></script>
  </head>


  <body class="preload">
    <?php
    $currentSite = '';
    $sections = [
        [
            'name' => 'Oops! That page couldn\'t be found',
            'shortName' => 'Not found',
            'icon' => 'fas fa-bomb'
        ],
        ['name' => 'Get In Touch', 'icon' => 'fas fa-fingerprint'],
    ];
    include 'resources/functions.php';
    include 'resources/bodyPreMain.php';
    ?>


    <!-- Main. -->
    <main>
      <div id="initial">
        <p class="fadeIn" style="float: left;">
          This website does not (and won't ever) use cookies. I value your
          privacy.
        </p>
        <div id="selectLanguage">
          <button id="dropButton">
            LANG <i class="fas fa-caret-down"></i>
          </button>
          <div id="languages">
            <a href="/404.php" title="404 error page in English.">ENG</a>
            <a href="/es/404.html" title="Página de error 404 en Español.">ES</a>
          </div>
        </div>
        <div class="clear"></div>
      </div>

      <!-- Section 404 error. -->
      <section class="hidden">
        <?php sectionHeader(0); ?>
        <p>
          Not to worry. You can either head back to the
          <a href="/" title="David ÁlvarezRosa's personal website.">home page</a>,
          or
          <a href="#sec:get-in-touch" title="Scroll to get in touch section.">get in touch</a>
          if this is an error.
        </p>
        <div class="error404page">
          <div class="newcharacter404">
            <div class="chair404"></div>
            <div class="leftshoe404"></div>
            <div class="rightshoe404"></div>
            <div class="legs404"></div>
            <div class="torso404">
              <div class="body404"></div>
              <div class="leftarm404"></div>
              <div class="rightarm404"></div>
              <div class="head404">
                <div class="eyes404"></div>
              </div>
            </div>
            <div class="laptop404"></div>
          </div>
        </div>
        <!-- <p style="text-align: center;"> -->
        <!--   <i class="fas fa-people-carry fa-7x"></i> -->
        <!-- </p> -->
        <p>
          Error code: 404.
        </p>
      </section>

      <!-- Section get in touch.  -->
      <?php include "resources/getInTouch.html"; ?>
    </main>


    <!-- License (Creative Commons). -->
    <?php include "resources/license.php"; ?>


    <!-- Footer. -->
    <?php include "resources/footer.php"; ?>
  </body>
</html>


<?php
ob_end_flush();
include 'resources/cacheEnd.php';
?>
