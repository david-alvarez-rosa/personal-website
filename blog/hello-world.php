<!DOCTYPE html>

<html lang="en">
  <head>
    <title>Neural Networks | David Álvarez Rosa | Personal Blog</title>
    <meta charset="UTF-8" />
    <meta name="description" content="Implementing a Neural Network from scratch
                                      in C++ - Part 1 - The theory | David
                                      Álvarez Rosa | Personal Blog" />
    <meta name="keywords" content="Neural Network, C++, Scratch, Fully
                                   Connected, Artificial Intelligence, Deep
                                   Learning, Implementing, David Álvarez Rosa,
                                   David Álvarez, David, Personal Blog, Blog,
                                   Entry, Mathematics, Engineering, Technology" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://blog.alvarezrosa.com/hello-world.html" />
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
    <link rel="stylesheet" href="fontawesome/css/fontawesome.css" />
    <link rel="stylesheet" href="fontawesome/css/solid.css" />
    <link rel="stylesheet" href="fontawesome/css/brands.css" />
    <link rel="stylesheet" href="highlight/styles/atom-one-dark.css" />
    <script type="application/ld+json">
	    {
		      "@context":"http://schema.org",
		      "@type": "BlogPosting",
		      "image": "https://blog.alvarezrosa.com/neural-network.jpeg",
		      "url": "https://blog.alvarezrosa.com/neural-networks-part1.html",
		      "headline": "Neural Networks - Part 1",
		      "alternativeHeadline": "Neural Networks",
		      "dateCreated": "2019-02-11T11:11:11",
		      "datePublished": "2019-02-11T11:11:11",
		      "dateModified": "2019-02-11T11:11:11",
		      "inLanguage": "en-US",
		      "isFamilyFriendly": "true",
		      "copyrightYear": "2020",
		      "copyrightHolder": "",
		      "author": {
			        "@type": "Person",
			        "name": "David Álvarez Rosa",
			        "url": "https://david.alvarezrosa.com"
		      },
		      "creator": {
			        "@type": "Person",
			        "name": "David Álvarez Rosa",
			        "url": "https://david.alvarezrosa.com/"
		      },
		      "genre":["SEO","JSON-LD"],
		      "articleSection": "Uncategorized posts",
		      "articleBody": "Paste the body of your content in here in plaintext"
	    }
    </script>
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


    <!-- Zoomed images.  -->
    <div id="zoomImgDiv">
      <a href="javascript:closeZoomImg();" title="Close zoomed figure.">
        <i class="fas fa-times fa-2x"></i>
      </a>
      <figure>
        <img id="zoomImg" /><figcaption id="zoomImgCaption"></figcaption>
      </figure>
    </div>


    <!-- Header. -->
    <?php $currentSite = "blog"; include "resources/header.php"; ?>


    <!-- Navigation bar. -->
    <nav id="navBar">
      <div id="navBarDiv">
        <a class="current"
           href="#sec:greeting"
           title="Scroll to greeting section.">
          Greeting
        </a>
      </div>
      <button id="navBarButton"
              onclick="toggleNavBar();"
              title="Expand navigation bar.">
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
          <a class="current"
             href="#sec:greeting"
             title="Scroll to greeting section.">
            Greeting <i class="fas fa-bullhorn"></i>
          </a>
        </li>
        <li>
          <p>
            @David &copy; 2020
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
      <p class="fadeIn" style="margin-top: 2.5em;">
        This website does not (and won't ever) use cookies. I value your
        privacy.
      </p>

      <h1 class="fadeIn">Hello World!</h1>
      <div class="subTitle fadeIn">
        <div class="rightLeftFlex">
          <h4>
            <i class="fas fa-clock"></i> &nbsp;
            Less than one minute to read
          </h4>
          <h4>
            <i class="fas fa-user-edit"></i> &nbsp;
            <a href="https://david.alvarezrosa.com/"
               title="David Álvarez Rosa's personal website.">
              David Álvarez Rosa
            </a>
          </h4>
        </div>
        <div class="rightLeftFlex">
          <h4>
            <i class="fas fa-tags"></i> &nbsp;
            Blog - Entry - Greeting
          </h4>
          <h4>
            <i class="fas fa-calendar-alt"></i> &nbsp;  November 7, 2019
          </h4>
        </div>
        <p class="marginTopAux">
          As when starting anything new in CS, <em>Hello World!</em> This blog
          post is dated the day my personal website <strong>went
          live</strong>. Either way, hello again. If you happen to be
          interested, feel free to <strong>subscribe</strong> using the above
          RSS file or to get in touch by clicking on the link in the homepage.
        </p>
      </div>

      <div class="anchor" id="sec:greeting">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <h2> Hello World!
          <a href="#sec:greeting"
             title="Go to greeting section.">
            <i class="linkIcon fas fa-link"></i>
          </a>
          <i class="rightIcon fas fa-bullhorn"></i>
        </h2>
        <p>
          As when starting anything new in CS, <em>Hello World!</em> This blog
          post is dated the day my personal website <strong>went
          live</strong>. Either way, hello again. If you happen to be
          interested, feel free to <strong>subscribe</strong> using the above
          RSS file or to get in touch by clicking on the link in the homepage.
        </p>
      </section>

      <div id="blogControllers" class="fadeIn">
        <button class="blogButton blogPrevious blogButtonInactive">
          <i class="fas fa-arrow-left"></i> Previous blog entry
        </button>
        <button class="blogButton blogNext"
                onclick="window.location.href = 'neural-network-part1.html';">
          Next blog entry <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </main>


    <!-- License (Creative Commons). -->
    <?php include "resources/license.html"; ?>


    <!-- Footer. -->
    <?php include "resources/footer.html"; ?>


    <!-- Javascript files. -->
    <script src="js/main.js"></script>
    <script src="js/shortcuts.js"></script>
    <script src="js/confetti.js"></script>
    <script src="js/blog.js"></script>
  </body>
</html>
