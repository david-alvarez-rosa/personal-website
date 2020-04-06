<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite index PHP file.
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


<!DOCTYPE html>

<html lang="en">
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
     <link rel="stylesheet" href="fontawesome/css/fontawesome.css" />
     <link rel="stylesheet" href="fontawesome/css/solid.css" />
     <link rel="stylesheet" href="fontawesome/css/brands.css" />
  </head>


  <?php include "resources/comment.html" ?>


  <body class="preload">
     <?php
     $currentSite = 'blog';
     $sections = array('Subscribe', 'Posts');
     $icons = array('fas fa-rss-square', 'fas fa-blog');
     include 'resources/bodyPreMain.php';
     ?>


    <!-- Main. -->
    <main>
      <p class="fadeIn" style="margin-top: 2.5em;">
        This website does not (and won't ever) use cookies. I value your
        privacy.
      </p>

      <div class="anchor" id="sec:subscribe">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <h2> Subscribe
          <a href="#sec:subscribe"
             title="Go to subscribe section.">
            <i class="linkIcon fas fa-link"></i>
          </a>
          <i class="rightIcon fas fa-rss-square"></i>
        </h2>

        <p>
          You can <strong>subscribe</strong> to this blog by using this
          <a href="rss.xml"
             title="RSS subscription file.">
            RSS file <i class="fas fa-rss-square"></i></a>.
        </p>
        <p>
          <a href="http://www.whatisrss.com/"
             rel="nofollow noopener"
             target="_blank"
             title="What Is RSS? RSS Explained.">
            RSS <i class="fas fa-external-link-alt"></i>
          </a>
          (Rich Site Summary) is a format for <strong>delivering</strong>
          regularly changing <strong>web content</strong>. It allows you to
          easily stay informed by retrieving the latest content from the sites
          you are interested in and allows you to ensure
          your <strong>privacy</strong>, by not needing to join each site's
          email newsletter.
        </p>
        <p>
          If you are not familiar with RSS readers and you are an
          <a href="https://www.gnu.org/software/emacs/"
             rel="nofollow noopener"
             target="_blank"
             title="GNU Emacs official website.">
            Emacs <i class="fas fa-external-link-alt"></i>
          </a>
          user, I recommend you
          <a href="https://nullprogram.com/blog/2013/09/04/"
             target="_blank"
             rel="nofollow noopener"
             title="Introducing Elfeed, an Emacs Web Feed Reader.">
            Elfeed <i class="fas fa-external-link-alt"></i>
          </a>
          (created by
          <a href="https://nullprogram.com/about/"
             target="_blank"
             rel="nofollow noopener"
             title="Chris Wellons (skeeto) personal website.">
            Chris Wellons <i class="fas fa-external-link-alt"></i>
          </a>).

        </p>
      </section>

      <div class="anchor" id="sec:posts">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <h2> Latest Blog Posts
          <a href="#sec:posts"
             title="Go to latest blog posts section.">
            <i class="linkIcon fas fa-link"></i>
          </a>
          <i class="rightIcon fas fa-blog"></i>
        </h2>

        <p>
          Below, you will find the latest blog posts summarized and listed in
          reverse chronological order.
        </p>

        <a href="neural-network-part1.php"
           title="Read more: Implementing a Neural Network from scratch - Part 1.">
          <div class="card">
            <h3>
              Implementing a Neural Network from scratch &ndash; Part 1
              <i class="titleIcon fas fa-project-diagram"></i>
            </h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-clock"></i> &nbsp;
                10 to 15 minutes to read
              </h4>
              <h4>
                <i class="fas fa-user-edit"></i> &nbsp;
                David Álvarez Rosa
              </h4>
            </div>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-tags"></i> &nbsp;
                Neural Network - AI - Deep Learning
                <i class="moreTags fas fa-plus"></i>
              </h4>
              <h4>
                <i class="fas fa-calendar-alt"></i> &nbsp;  March 9, 2020
              </h4>
            </div>
            <p class="marginTopAuxAux">
              <strong class="abstract">Abstract</strong>. The first entry of
              this blog series of implementing a Neural Network in C++ will be
              covering the <strong>mathematical theory</strong> behind the fully
              connected layered artificial neural networks. We will start by
              defining its topology and its
              core <strong>components</strong>. Then we will dicuss how a neural
              network works (namely forward propagation) This blog entry will
              finish by
              <strong>reformulating</strong> the learning problem from a
              mathematical optimization point of view and deriving
              the <em>well-known</em> <strong>backward propagation</strong>
              formula...
            </p>
            <p class="readMore">
              <i class="fas fa-book-reader fa-lg"></i>
            </p>
          </div>
        </a>

        <a href="hello-world.php"
           title="Read more: Hello World!">
          <div class="card">
            <h3>Hello world! <i class="titleIcon fas fa-bullhorn"></i></h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-clock"></i> &nbsp;
                Less than one minute to read
              </h4>
              <h4>
                <i class="fas fa-user-edit"></i> &nbsp;
                David Álvarez Rosa
              </h4>
            </div>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-tags"></i> &nbsp; Blog - Entry
              </h4>
              <h4>
                <i class="fas fa-calendar-alt"></i> &nbsp;  November 7, 2019
              </h4>
            </div>
            <p class="marginTopAuxAux">
              As when starting anything new in CS, <em>Hello World!</em> This
              blog post is dated the day my personal website <strong>went
              live</strong>. Either way, hello again. If you happen to be
              interested, feel free to <strong>subscribe</strong> using the
              above RSS file or to get in touch by clicking on the link in the
              homepage.
            </p>
            <p class="readMore">
               <i class="fas fa-book-reader fa-lg"></i>
            </p>
          </div>
        </a>
      </section>
    </main>


    <!-- License (Creative Commons). -->
    <?php include "resources/license.html"; ?>


    <!-- Footer. -->
    <?php include "resources/footer.php"; ?>


    <!-- Javascript files. -->
    <script src="js/main.js"></script>
    <script src="js/shortcuts.js"></script>
    <script src="js/confetti.js"></script>
  </body>
</html>
