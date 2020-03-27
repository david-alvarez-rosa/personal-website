<!DOCTYPE html>

<html lang="en">
  <head>
    <title>404 Error | David Álvarez Rosa | Personal Website</title>
    <meta charset="UTF-8" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://david.alvarezrosa.com/404.html" />
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
    <!-- Loading spinner. -->
    <div id="loadingSpinner"></div>

    <!-- Information divs. -->
    <?php include "resources/information.html"; ?>


    <!-- Heart. -->
    <div id="heart"><i class="fas fa-heart"></i></div>


    <!-- Header. -->
    <?php $currentSite = ""; include "resources/header.php"; ?>


    <!-- Navigation bar. -->
    <nav id="navBar">
      <div id="navBarDiv">
        <a class="current" href="#sec:404" title="Scroll to 404 error message.">
          Not found
        </a>
        <a href="#sec:touch" title="Scroll to get in touch section.">
          Get In Touch
        </a>
      </div>
      <button id="navBarButton" onclick="toggleNavBar()" title="Expand navigation bar.">
        <i class="fas fa-bars fa-2x"></i>
      </button>
    </nav>

    <!-- Navigation bar expanded. -->
    <nav id="navBarExpanded">
      <button id="navBarExpandedButton"
              onclick="toggleNavBar();"
              title="Hide expanded navigation bar.">
        <i class="fas fa-times fa-2x"></i>
      </button>
      <ul>
        <li class="title">
          Sections
        </li>
        <li>
          <a class="current" href="#sec:404" title="Scroll to 404 error message.">
            Not found <i class="fas fa-bomb"></i>
          </a>
        </li>
        <li>
          <a href="#sec:touch" title="Scroll to get in touch section.">
            Get In Touch <i class="fas fa-fingerprint"></i>
          </a>
        </li>
        <li>
          <p>
            @David 2020
          </p>
        </li>
      </ul>
    </nav>


    <!-- Scroll back to top button. -->
    <div id="scrollTop"
         onmouseover="document.getElementById('scrollTopSpan').style.display = 'inline-block';"
         onmouseout="document.getElementById('scrollTopSpan').style.display = 'none';">
      <a href="#" title="Scroll back to top.">
        <span id="scrollTopSpan">Go to top</span>
        <i class="fas fa-arrow-circle-up fa-2x"></i>
      </a>
    </div>


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
            <a href="/404.html" title="404 error page in English.">ENG</a>
            <a href="/es/404.html" title="Página de error 404 en Español.">ES</a>
          </div>
        </div>
        <div class="clear"></div>
      </div>

      <!-- Section 404 error. -->
      <div class="anchor" id="sec:404">
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
      <div class="anchor" id="sec:touch">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <h2>
          Get In Touch
          <a href="#sec:touch"
             title="Go to get in touch section.">
            <i class="linkIcon fas fa-link"></i>
          </a>
          <i class="rightIcon fas fa-fingerprint"></i>
        </h2>
        <p>
          If you want to send me a message, my inbox is always open. Whether for
          a potential project or just to say hi, I'll try my best to answer your
          email!
        </p>
        <form method="post" action="javascript:showInfo('welcomeUser');">
          <label for="inputName">Name</label>
          <label for="inputEmail">Email</label>
          <label for="inputMessage">Message</label>
          <div id="getInTouch">
            <input type="text"
                   id="inputName"
                   placeholder="&#xF007;  Name"
                   style="width: calc(50% - .5em); float: left;" />
            <input type="email"
                   id="inputEmail"
                   placeholder="&#xF1FA;  Email"
                   style="width: calc(50% - .5em); float: right; margin-right: 0;" />
            <textarea id="inputMessage"
                      placeholder="&#xF044;  Message"
                      rows="5"
                      style="width: 100%;"></textarea>
            <input type="submit" value="Send Message" class="primary" />
            <input type="reset" value="Reset" />
          </div>
          <div id="getInTouchRight">
            <ul>
              <li>
                <i class="fas fa-envelope fa-lg"></i>
                <a href="javascript:showInfo('infoAddress');"
                   title="Show my personal address.">
                  Show address.
                </a>
              </li>
              <li>
                <i class="fas fa-phone fa-lg"></i>
                <a href="javascript:showInfo('infoPhone');"
                   title="Show my personal phone number.">
                  Show phone number.
                </a>
              </li>
              <li>
                <i class="fas fa-at fa-lg"></i>
                <a href="javascript:showInfo('infoEmail');"
                   title="Show my personal email.">
                  Show email.
                </a>
              </li>
            </ul>
          </div>
          <!-- Give parent size (the above divs are floating). -->
          <div class="clear"></div>
        </form>
      </section>
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
