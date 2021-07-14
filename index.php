<?php
/**
 *
 * David \'Alvarez Rosa's personal website index PHP file.
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
    <title>David Álvarez Rosa | Personal Website</title>
    <meta charset="UTF-8" />
    <meta name="description" content="Hi visitor, I am David, welcome to my
                persona website! This site is intended to be a web version of
                my curriculum vitae and be my corner on the Internet."/>
    <meta name="keywords" content="David Álvarez Rosa, David Álvarez, David,
                Curriculum Vitae, Mathematics, Engineering" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://david.alvarezrosa.com" />
    <link rel="apple-touch-icon" sizes="180x180"
          href="img/icons/apple-touch-icon.png" />
    <link rel="icon" type="image/png" sizes="32x32"
          href="img/icons/favicon-32x32.png" />
    <link rel="icon" type="image/png" sizes="16x16"
          href="img/icons/favicon-16x16.png" />
    <link rel="manifest" href="site.webmanifest" />
    <link rel="mask-icon" href="img/icons/safari-pinned-tab.svg"
          color="#816363" />
    <link rel="shortcut icon" href="img/icons/favicon.ico" />
    <meta name="msapplication-TileColor" content="#DBDCDC" />
    <meta name="msapplication-config" content="img/icons/browserconfig.xml" />
    <meta name="theme-color" content="#FFFFFF" />

    <!-- Meta tags for social media. -->
    <meta property="og:title" content="David Álvarez Rosa | Personal Website" />
    <meta property="og:description" content="Personal Website." />
    <meta property="og:image"
          content="https://david.alvarezrosa.com/img/backgrounds/mountain.jpg" />
    <meta property="og:url" content="https://david.alvarezrosa.com/" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />

    <!-- CSS files. -->
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/animations.css" />
    <link rel="stylesheet" id="fontawesome"
          data-href="external/fontawesome/css/all.min.css" />
    <?php
    if (isset($_GET["theme"]) and $_GET["theme"] == "suckless") { ?>
      <link rel="stylesheet" href="css/suckless.css" />
    <?php } ?>

    <!-- Javascript files. -->
    <script defer src="js/main.js"></script>
    <script defer src="js/shortcuts.js"></script>
    <script defer src="js/confetti.js"></script>

    <script type="application/ld+json">
     {
         "@context": "https://schema.org/",
         "@type": "Person",
         "honorificPrefix": "Mr",
         "name": "David Álvarez Rosa",
         "image": "https://david.alvarezrosa.com/img/portrait.png",
         "jobTitle": "Mathematics and Industrial Engineering graduate",
         "disambiguatingDescription": "Mathematics and Industrial Engineering graduate",
         "alumniOf": "Polytechnic University of Catalonia",
         "affiliation": "Polytechnic University of Catalonia",
         "birthPlace": "Navarre, Spain",
         "birthDate": "1998-10-10",
         "gender": "male",
         "memberOf": "Driverless UPC - ETSEIB Motorsport",
         "nationality": "Spanish",
         "url": "https://david.alvarezrosa.com/",
         "knowsLanguage": ["es", "en", "cat"],
         "sameAs": [
             "https://www.linkedin.com/in/david-alvarez-rosa",
             "https://gitlab.com/david-alvarez-rosa",
             "https://github.com/david-alvarez-rosa"
         ]
     }
    </script>
  </head>


  <body class="preload">
    <?php
    $currentSite = '';
    $sections = [
        ['name' => 'Information', 'icon' => 'fas fa-info-circle'],
        ['name' => 'Disclaimer', 'icon' => 'fas fa-exclamation-triangle'],
        ['name' => 'Get In Touch', 'icon' => 'fas fa-fingerprint']
    ];
    include 'resources/bodyPreMain.php';
    include 'resources/functions.php';
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
            <a href="." title="David Álvarez Rosa's personal website.">ENG</a>
            <a href="./es" title="Página web personal de David Álvarez Rosa.">ES</a>
          </div>
        </div>
        <div class="clear"></div>
      </div>


      <!-- Section information. -->
      <section class="hidden">
        <?php sectionHeader(); ?>

        <a href="img/portrait.png"
           title="David Álvarez Rosa headshot portrait.">
          <img id="portrait" src="img/portrait_low.png"
               alt="David Álvarez Rosa headshot portrait." />
        </a>
        <p>
          Welcome to my website! My name is David and I am a Mathematics and
          Industrial Technology Engineering graduate from UPC (BarcelonaTech),
          currently studying the M.Sc. in Advanced Mathematics from UNED.
        </p>
        <p>
          This site is intended to be a web version of my
          <a href="pdf/cv-david-alvarez-rosa.pdf#view=Fit"
             title="My personal Curriculum Vitae.">
            curriculum vitae</a>
          and be my corner on the Internet. I have designed this website myself
          from scratch, if you're curious, you can take a look at the
          <a href="https://gitlab.com/david-alvarez-rosa/personal-website"
             title="Personal website source code - Gitlab.">
            source code <i class="fas fa-external-link-alt"></i></a>.
        </p>
        <p>
          Below is some basic information about me.
        </p>
        <div id="personalData">
          <a href="javascript:showInfo('infoEmail');"
             title="Show my personal email.">
            <div class="data">
              <i class="fas fa-at"></i>
              <p>Email</p>
              <div class="dataUnder">
                <i class="fas fa-at"></i>
                <p>Show email</p>
              </div>
            </div>
          </a>
          <a href="javascript:showInfo('infoPhone');"
             title="Call me.">
            <div class="data">
              <i class="fas fa-phone"></i>
              <p>Phone</p>
              <div class="dataUnder">
                <i class="fas fa-phone"></i>
                <p>Show number</p>
              </div>
            </div>
          </a>
          <a href="pdf/cv-david-alvarez-rosa.pdf#view=Fit"
             title="My personal Curriculum Vitae.">
            <div class="data">
              <i class="fas fa-file-alt"></i>
              <p>CV</p>
              <div class="dataUnder">
                <i class="fas fa-file-alt"></i>
                <p>Show CV</p>
              </div>
            </div>
          </a>
          <a href="javascript:showInfo('infoAddress');"
             title="My personal address.">
            <div class="data">
              <i class="fas fa-envelope"></i>
              <p>Address</p>
              <div class="dataUnder">
                <i class="fas fa-envelope"></i>
                <p>Show address</p>
              </div>
            </div>
          </a>
          <div class="data">
            <i class="fas fa-map-marker-alt"></i>
            <p>Location</p>
            <div class="dataUnder">
              <i class="fas fa-map-marker-alt"></i>
              <p>Barcelona</p>
            </div>
          </div>
          <a href="."
             title="David Álvarez Rosa's personal website.">
            <div class="data">
              <i class="fas fa-link"></i>
              <p>Website</p>
              <div class="dataUnder">
                <i class="fas fa-link"></i>
                <p>Personal web</p>
              </div>
            </div>
          </a>
          <a href="https://gitlab.com/david-alvarez-rosa"
             title="Personal Gitlab page.">
            <div class="data">
              <i class="fab fa-gitlab"></i>
              <p>Gitlab</p>
              <div class="dataUnder">
                <i class="fab fa-gitlab"></i>
                <p>My Gitlab</p>
              </div>
            </div>
          </a>
          <div class="data">
            <i class="fas fa-birthday-cake"></i>
            <p>Birthday</p>
            <div class="dataUnder">
              <i class="fas fa-birthday-cake"></i>
              <p>10/10/1998</p>
            </div>
          </div>
        </div>

        <p>
          I maintain a complete
          <a href="sitemap.xml"
             title="Sitemap of this website.">
            sitemap</a>
          of the website. Main pages are:
        </p>
        <ul>
          <li>
            <a href="#" title="David Álvarez Rosa's home page.">
              Home page</a>:
            introductory page.
          </li>
          <li>
            <a href="about/" title="David Álvarez Rosa's about page.">
              About page</a>:
            a web version of my CV.
          </li>
          <li>
            <a href="https://blog.alvarezrosa.com"
               title="David Álvarez Rosa's blog page.">
              Blog page</a>:
            my personal blog site.
          </li>
          <li>
            <a href="pdf/cv-david-alvarez-rosa.pdf#view=Fit"
               title="David Álvarez Rosa's curriculum vitae.">
              CV</a>:
              my personal curriculum vitae (.pdf).
          </li>
        </ul>
      </section>

      <!-- Section disclaimer. -->
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          This site is powered by free (as in <em>freedom</em>) software and
          does not contain any tracking <em>malware</em>. I have designed this
          website myself from scratch. The
          <a href="https://gitlab.com/david-alvarez-rosa/personal-website"
             title="Personal website source code - Gitlab.">
            source code <i class="fas fa-external-link-alt"></i></a>
          is licensed under the
          <a rel="license"
             href="/COPYING"
             title="GNU General Public License version 3.">
            GNU General Public License</a>.
        </p>
        <p>
          Web content should conform with the
          <a href="https://www.w3.org/TR/WCAG20/"
             title="World Wide Web Consortium Web Content Accessibility
                   Guidelines (WCAG) 2.0 website">
            World Wide Web Consortium Web Content Accessibility Guidelines
            (WCAG) 2.0 <i class="fas fa-external-link-alt"></i></a>
          initially at Level A and increasing to Level AA. However, note that I
          am <strong>not</strong> a professional web developer and I have
          created this website in my spare time, so there may be errors.
        </p>
        <p>
          Except where otherwise noted, content on this website is licensed under a
          <a rel="license"
             href="/LICENSE"
             title="Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International License." >
            Creative Commons Attribution-NonCommercial-ShareAlike 4.0 International</a>
          license, which means that you are <strong>free too</strong>:
        </p>
        <ul style="margin-bottom: .25em; margin-top: -1.25em;">
          <li>
            <strong>Share</strong> &mdash; copy and redistribute the material in
            any medium or format </li>
          <li>
            <strong>Adapt</strong> &mdash; remix, transform, and build upon the material
          </li>
        </ul>
        <p>
          under the <strong>following terms</strong>:
        </p>
        <ul style="margin-top: -1.25em;">
          <li>
            <strong>Attribution</strong> &mdash; You must give appropriate
            credit, provide a link to the license, and indicate if changes were
            made. You may do so in any reasonable manner, but not in any way
            that suggests the licensor endorses you or your use.
          </li>
          <li>
            <strong>NonCommercial</strong> &mdash; You may not use the material
            for commercial purposes.
          </li>
          <li>
            <strong>ShareAlike</strong> &mdash; If you remix, transform, or
            build upon the material, you must distribute your contributions
            under the same license as the original.
          </li>
          <li>
            <strong>No additional restrictions</strong> &mdash; You may not
            apply legal terms or technological measures that legally restrict
            others from doing anything the license permits.
          </li>
        </ul>
        <p>
          This is my personal website. The opinions expressed here are my own
          and not those of my university or employer.
        </p>
      </section>

      <!-- Section get in touch.  -->
      <?php include 'resources/getInTouch.html'; ?>
    </main>


    <!-- License (Creative Commons). -->
    <?php include 'resources/license.php'; ?>


    <!-- Footer. -->
    <?php include 'resources/footer.php'; ?>
  </body>
</html>


<?php
ob_end_flush();
include 'resources/cacheEnd.php';
?>
