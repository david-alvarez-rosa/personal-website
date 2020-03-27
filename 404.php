<!DOCTYPE html>

<html lang="en">
  <head>
    <title>404 Error | David Álvarez Rosa | Personal Website</title>
    <meta charset="UTF-8" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://david.alvarezrosa.com/404.php" />
    <link rel="apple-touch-icon" sizes="180x180" href="/img/icons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/icons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/icons/favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">
    <link rel="mask-icon" href="/img/icons/safari-pinned-tab.svg" color="#816363">
    <link rel="shortcut icon" href="/img/icons/favicon.ico">
    <meta name="msapplication-TileColor" content="#DBDCDC">
    <meta name="msapplication-config" content="/img/icons/browserconfig.xml">
    <meta name="theme-color" content="#FFFFFF">
    <link rel="stylesheet" href="/css/main.css" />
    <link rel="stylesheet" href="/css/animations.css" />
    <link rel="stylesheet" href="/css/404.css" />
    <link rel="stylesheet" href="/fontawesome/css/fontawesome.css" />
    <link rel="stylesheet" href="/fontawesome/css/solid.css" />
    <link rel="stylesheet" href="/fontawesome/css/brands.css" />
  </head>


  <!--
	    Alright, you can look at this code - but only if you solve this maze within
	    10 seconds. David.

	    Start.

	    aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa   a
	    8   8               8               8           8                   8   8
	    8   8   aaaaaaaaa   8   aaaaa   aaaa8aaaa   aaaa8   aaaaa   aaaaa   8   8
	    8               8       8   8           8           8   8   8       8   8
	    8aaaaaaaa   a   8aaaaaaa8   8aaaaaaaa   8aaaa   a   8   8   8aaaaaaa8   8
	    8       8   8               8           8   8   8   8   8           8   8
	    8   a   8aaa8aaaaaaaa   a   8   aaaaaaaa8   8aaa8   8   8aaaaaaaa   8   8
	    8   8               8   8   8       8           8           8       8   8
	    8   8aaaaaaaaaaaaaaa8aaa8   8aaaa   8   aaaaa   8aaaaaaaa   8   aaaa8   8
	    8           8       8   8       8   8       8           8   8           8
	    8   aaaaa   8aaaa   8   8aaaa   8   8aaaaaaa8   a   a   8   8aaaaaaaaaaa8
	    8       8       8   8   8       8       8       8   8   8       8       8
	    8aaaaaaa8aaaa   8   8   8   aaaa8aaaa   8   aaaa8   8   8aaaa   8aaaa   8
	    8           8   8           8       8   8       8   8       8           8
	    8   aaaaa   8   8aaaaaaaa   8aaaa   8   8aaaa   8aaa8   aaaa8aaaaaaaa   8
	    8   8       8           8           8       8   8   8               8   8
	    8   8   aaaa8aaaa   a   8aaaa   aaaa8aaaa   8   8   8aaaaaaaaaaaa   8   8
	    8   8           8   8   8   8   8           8               8   8       8
	    8   8aaaaaaaa   8   8   8   8aaa8   8aaaaaaa8   aaaaaaaaa   8   8aaaaaaa8
	    8   8       8   8   8           8           8   8       8               8
	    8   8   aaaa8   8aaa8   aaaaa   8aaaaaaaa   8aaa8   a   8aaaaaaaa   a   8
	    8   8                   8           8               8               8   8
	    8   8aaaaaaaaaaaaaaaaaaa8aaaaaaaaaaa8aaaaaaaaaaaaaaa8aaaaaaaaaaaaaaa8aaa8

	    End.
	  -->


  <body class="preload">
     <?php
     $currentSite = '';
     $sections = array('Not found', 'Get In Touch');
     $icons = array('fas fa-bomb', 'fas fa-fingerprint');
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
      <div class="anchor" id="sec:not-found">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <h2>Oops! That page couldn't be found
          <a href="#sec:404"
             title="Go to 404 error message.">
            <i class="linkIcon fas fa-link"></i>
          </a>
          <i class="rightIcon fas fa-bomb"></i>
        </h2>
        <p>
          Not to worry. You can either head back to the
          <a href="/" title="David ÁlvarezRosa's personal website.">home page</a>,
          or
          <a href="#sec:touch" title="Scroll to get in touch section.">get in touch</a>
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
    <?php include "resources/license.html"; ?>


    <!-- Footer. -->
    <?php include "resources/footer.html"; ?>


    <!-- Javascript files. -->
    <script src="/js/main.js"></script>
    <script src="/js/shortcuts.js"></script>
    <script src="/js/confetti.js"></script>
  </body>
</html>
