<?php
/**
 *
 * David \'Alvarez Rosa's personal website index PHP file.
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
    <title>David Álvarez Rosa | Personal Website | Math &amp; Engineering</title>
    <meta charset="UTF-8" />
    <meta name="description" content="I'm an undergraduate student in my final
                                      year towards a double degree in
                                      Mathematics and Industrial Technology
                                      Engineering, under the CFIS program of the
                                      Polytechnic University of Catalonia." />
    <meta name="keywords" content="David Álvarez Rosa, David Álvarez, David,
                                   Curriculum Vitae, Mathematics, Engineering" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://david.alvarezrosa.com/" />
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
    <link rel="stylesheet" href="css/animations.css" />
    <link rel="stylesheet" href="fontawesome/css/fontawesome.css" />
    <link rel="stylesheet" href="fontawesome/css/solid.css" />
    <link rel="stylesheet" href="fontawesome/css/brands.css" />
    <script type="application/ld+json">
      {
          "@context": "https://schema.org/",
          "@type": "Person",
          "honorificPrefix": "Mr",
          "name": "David Álvarez Rosa",
          "image": "img/icons/icon.png",
          "jobTitle": "Mathematics and Industrial Engineering student",
          "disambiguatingDescription": "Mathematics and Industrial Engineering student",
          "alumniOf": "Polytechnic University of Catalonia",
          "affiliation": "Polytechnic University of Catalonia",
          "birthPlace": "Navarre, Spain",
          "birthDate": "1998-10-10",
          "gender": "male",
          "memberOf": "Driverless UPC - ETSEIB Motorsport",
          "nationality": "Spanish",
          "url": "https://david.alvarezrosa.com/",
          "award": "",
          "knowsAbout": "",
          "knowsLanguage": ["es", "en", "cat"]
      }
    </script>
  </head>


  <?php include "resources/comment.html" ?>


  <body class="preload">
     <?php
     $currentSite = 'home';
     $sections = [
         ['name' => 'About me', 'shortName' => 'About', 'icon' => 'fas fa-address-card'],
         ['name' => 'Education', 'icon' => 'fas fa-graduation-cap'],
         ['name' => 'Courses', 'icon' => 'fas fa-book'],
         ['name' => 'Projects', 'icon' => 'fas fa-lightbulb'],
         ['name' => 'Inspiration', 'icon' => 'fas fa-quote-right'],
         ['name' => 'Skills', 'icon' => 'fas fa-rocket'],
         ['name' => 'Languages', 'icon' => 'fas fa-language', 'iconStyle' =>
     'font-size: 1.1em; padding-top: .525em;', 'iconStyleNav' => 'font-size: 1.2em;'],
         ['name' => 'Awards', 'icon' => 'fas fa-trophy'],
         ['name' => 'Others', 'icon' => 'fas fa-asterisk'],
         ['name' => 'Interests', 'icon' => 'fas fa-search'],
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


      <!-- Section about. -->
      <div class="anchor" id="sec:about">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(0); ?>
        <p>
          I'm an undergraduate student in my final year towards a double degree
          in Mathematics and Industrial Technology Engineering, under the
          <a href="https://cfis.upc.edu"
             rel="nofollow noopener"
             target="_blank"
             title="CFIS official website.">
            CFIS <i class="fas fa-external-link-alt"></i>
          </a>
          program of the
          <a href="https://upc.edu/en"
             rel="nofollow noopener"
             target="_blank"
             title="Polytechnic University of Catalonia website.">
            Polytechnic University of Catalonia <i class="fas fa-external-link-alt"></i>
          </a>
          in Barcelona (Spain).
        </p>
        <p>
          This site is intended to be a web version of my
          <a href="pdf/cv-david-alvarez-rosa.pdf#view=Fit"
             title="My personal Curriculum Vitae.">
            curriculum vitae
          </a> and be my corner on the Internet. I designed this website myself
          from scratch, if you're curious, you can take a look at the
          <a href="https://gitlab.com/david-alvarez-rosa/personal-website"
              rel="noopener"
             target="_blank"
             title="Personal website source code - Gitlab.">
            source code <i class="fas fa-external-link-alt"></i>
          </a>.
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
              rel="noopener"
             target="_blank"
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
          Next academic year I will do research on artificial intelligence (with
          focus on computer vision) as a visiting student at the
          <a href="https://www.utoronto.ca/"
             rel="nofollow noopener"
             target="_blank"
             title="University of Toronto official website.">
            University of Toronto <i class="fas fa-external-link-alt"></i>
          </a>
          (Canada), where I will do my Final Degree Thesis.
        </p>
      </section>

      <!-- Section education. -->
      <div class="anchor" id="sec:education">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(1); ?>
        <p>
          Below, you will find the details of my education summarized and listed
          in reverse chronological order.
        </p>
        <div class="card">
          <h3>
            <a href="https://www.upc.edu/en/bachelors/mathematics-barcelona-fme"
               rel="nofollow noopener"
               target="_blank"
               title="Degree in Mathematics website.">
              Degree in Mathematics
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-infinity"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://fme.upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Faculty of Mathematics and Statistics website.">
                FME
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 240
              <a href="https://en.wikipedia.org/wiki/European_Credit_Transfer_and_Accumulation_System"
                 rel="nofollow noopener"
                 target="_blank"
                 title="European Credit Transfer and Accumulation System - Wikipedia.">
                ECTS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2016 &nbsp;&ndash;&nbsp; Present
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            A rigorous and proof-oriented course with a robust mathematical base
            and providing a solid knowledge in its applications (algorithms,
            computing).
          </p>
          <ul>
            <li>Relevant <strong>subjects</strong>: Linear Algebra, Calculus,
              Mathematical Programming, Algorithmics, Abstract Algebra,
              Geometry, Analysis, Differential Equations, Probability and
              Statistics.
            </li>
            <li>
              Current <strong>grade</strong>: 8.04/10.
            </li>
            <li>
              <strong>Honors</strong> or excellence in 7 subjects.
            </li>
            <li>
              Expected date to <strong>finish</strong> the degree: February
              2021.
            </li>
          </ul>
        </div>
        <div class="card">
          <h3>
            <a href="https://www.upc.edu/en/bachelors/industrial-technology-engineering-barcelona-etseib"
               rel="nofollow noopener"
               target="_blank"
               title="Degree in Industrial Technology Engineering website.">
              Degree in Industrial Technology Engineering
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-cogs"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Barcelona School of Industrial Engineering website.">
                ETSEIB <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 240
              <a href="https://en.wikipedia.org/wiki/European_Credit_Transfer_and_Accumulation_System"
                 rel="nofollow noopener"
                 target="_blank"
                 title="European Credit Transfer and Accumulation System - Wikipedia.">
                ECTS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2016 &nbsp;&ndash;&nbsp; Present
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            Multidisciplinary and integrative vision of industrial
            engineering. Acquired knowledge and skills essential for future
            technological development.
          </p>
          <ul>
            <li>Relevant <strong>subjects</strong>: Mechanics, Thermodynamics,
              Electromagnetism, Electrotechnics, Fluid Mechanics, Materials and
              Electronics.
            </li>
            <li>
              Current <strong>grade</strong>: 8.05/10.
            </li>
            <li>
              <strong>Honors</strong> or excellence in 12 subjects.
            </li>
            <li>
              Expected date to <strong>finish</strong> the degree: February
              2021.
            </li>
          </ul>
        </div>
        <div class="card">
          <h3>
            Scientific and Technological Baccalaureate
            <i class="titleIcon fas fa-school"></i>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://www.irabia-izaga.org/"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Irabia-Izaga school website.">
                Irabia-Izaga school <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 2 years
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2014 &nbsp;&ndash;&nbsp; June 2016
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Burlada, Navarre
            </h4>
          </div>
          <ul class="marginTop">
            <li>Final grade: 9.47/10.</li>
            <li>
              University access exam grade (<!--
              --><a href="https://en.wikipedia.org/wiki/Selectividad"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Search Selectividad in Wikipedia."><em>Selectividad <i class="fas fa-external-link-alt"></i></em>
              </a>)
              : 12.76/14.
            </li>
          </ul>
        </div>
        <div class="card">
          <h3>Compulsory Secondary Education
            <i class="titleIcon fas fa-school"></i>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://www.irabia-izaga.org/"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Irabia-Izaga school website.">
                Irabia-Izaga school <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 4 years
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2010 &nbsp;&ndash;&nbsp; June 2014
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Burlada, Navarre
            </h4>
          </div>
          <p class="marginTop"></p>
        </div>
      </section>

      <!-- Section courses. -->
      <div class="anchor" id="sec:courses">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(2); ?>
        <p>
          I have attended the following extracurricular courses.
        </p>
        <div class="card">
          <h3>Mathematical Game Theory<i class="titleIcon fas fa-dice"></i></h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://cfis.upc.edu"
                 rel="nofollow noopener"
                 target="_blank"
                 title="CFIS official website.">
                CFIS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>&nbsp;
              20 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; April 2019
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            Game theory is the study of <strong>mathematical models</strong> of
            strategic interaction among <strong>rational</strong>
            decision-makers. It has applications in fields such as economics,
            logic and computer science.
          </p>
        </div>
        <div class="card">
          <h3>
            <a href="https://sites.google.com/view/dlcfis2019/home"
               rel="nofollow noopener"
               target="_blank"
               title="Machine Learning & Deep Learning CFIS website.">
              Machine Learning & Deep Learning
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-database"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://cfis.upc.edu"
                 rel="nofollow noopener"
                 target="_blank"
                 title="CFIS official website.">
                CFIS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>&nbsp;
              20 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; January 2019
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            Machine learning is the scientific study of algorithms and
            statistical models that computer systems use to perform a specific
            task without using explicit instructions, relying on patterns and
            inference instead.
          </p>
          <ul>
            <li>
              Basic principles of machine learning and classical methods.
            </li>
            <li>
              Introduction to deep learning from both an algorithmic and
              computational point of view.
            </li>
            <li>
              Study of its applications to reinforced learning and the analysis
              of multimedia content.
            </li>
          </ul>
        </div>
      </section>

      <!-- Section projects. -->
      <div class="anchor" id="sec:projects">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(3); ?>
        <p>
          Here are a few projects I've worked on recently. Do you have any
          questions about them?
          <a href="#sec:touch" title="Scroll to get in touch section.">
            Get in touch</a>. These projects have helped me to improve my skills
          in software development and programming, which are a great complement
          to my technical knowledge acquired at university.
        </p>
        <div class="card">
          <h3>Driverless - Motorsport<i class="titleIcon fas fa-car"></i></h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Barcelona School of Industrial Engineering website.">
                ETSEIB <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 20 hours/week &nbsp;&ndash;&nbsp; 6 months
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2019 &nbsp;&ndash;&nbsp; February 2020
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            I have been part of the <strong>Perception</strong> section of
            Driverless UPC team, which is a team formed by undergraduate
            engineers in charge of the designing, manufacturing and testing of
            a <strong>fully autonomous</strong> car that will participate in
            national and international competitions between universities.
          </p>
        </div>
        <div class="card">
          <h3>
            <a href="tres-en-raya" title="Go to the project website.">
              Robotic Arm - Tic-tac-toe <i class="titleIcon fas fa-th"></i>
            </a>
          </h3>
          <div class="clear"></div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Barcelona School of Industrial Engineering website.">
                ETSEIB <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 75 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; January 2019 &nbsp;&ndash;&nbsp; June 2019
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            Creation of virtual animation of a <strong>robotic arm</strong> able
            to play the Tic Tac Toe game and <strong>never lose</strong>. The
            source code of this project can be found in my
            <a href="https://gitlab.com/david-alvarez-rosa"
               rel="noopener"
               target="_blank"
               title="Personal Gitlab page.">
              personal Gitlab page <i class="fas fa-external-link-alt"></i></a>.
            You may want to check out the
            <a href="/tres-en-raya/"
               title="Robotic arm - Tic Tac Toe website.">
              project website</a>.
          </p>
        </div>
        <div class="card">
          <h3>Email server<i class="titleIcon fas fa-server"></i></h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              Personal project
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 15 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; August 2018 &nbsp;&ndash;&nbsp; September 2018
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            Email is perhaps the most successful federated, decentralized
            protocol to ever exist. It's a shame we've allowed a centralized,
            monolithic advertising company to obtain a near monopoly on such a
            great technology. That's the reason why I decided to run
            my <strong>own email server</strong>. After all, that is how email
            is <em>designed to work</em>.
          </p>
          <ul>
            <li>
              Configuration of a modern, secure personal email server based on
              free software (<!--
              --><a href="http://www.postfix.org/"
                 rel="nofollow noopener"
                 target="_blank"
                 title="The Postfix Home Page.">Postfix <i class="fas fa-external-link-alt"></i>
              </a>
              and
              <a href="https://www.dovecot.org/"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Dovecot, The Secure IMAP server.">
                Dovecot <i class="fas fa-external-link-alt"></i>
              </a>).
            </li>
            <li>
              Supports opportunistic
              <a href="https://en.wikipedia.org/wiki/Transport_Layer_Security"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Transport Layer Security - Wikipedia.">
                TLS <i class="fas fa-external-link-alt"></i>
              </a>
              and
              <a href="https://en.wikipedia.org/wiki/Internet_Message_Access_Protocol"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Internet Message Access Protocol - Wikipedia.">
                IMAP <i class="fas fa-external-link-alt"></i>
              </a>
               access.
            </li>
            <li>
              <a href="https://en.wikipedia.org/wiki/DomainKeys_Identified_Mail"
                 rel="nofollow noopener"
                 target="_blank"
                 title="DomainKeys Identified Mail - Wikipedia.">
                DKIM <i class="fas fa-external-link-alt"></i>
              </a>,
              <a href="https://en.wikipedia.org/wiki/Sender_Policy_Framework"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Sender Policy Framework - Wikipedia.">
                SPF <i class="fas fa-external-link-alt"></i>
              </a>, and
              <a href="https://en.wikipedia.org/wiki/DMARC"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Domain-based Message Authentication, Reporting and Conformance - Wikipedia.">
                DMARC <i class="fas fa-external-link-alt"></i>
              </a>
              records to ensure outgoing mail is not spam-filtered.
            </li>
          </ul>
        </div>
        <div class="card">
          <h3>
            <a href="http://david.alvarezrosa.com:5000" title="Go to the project website.">
              Study Schedule <i class="titleIcon fas fa-calendar-alt"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Barcelona School of Industrial Engineering website.">
                ETSEIB <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 60 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; January 2018 &nbsp;&ndash;&nbsp; June 2018
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Barcelona, Catalonia
            </h4>
          </div>
          <p class="marginTop">
            Creation of a customized study schedule generator adapted to
            students, through <strong>data analysis</strong>:
            clustering/k-nearest-neighbours. In particular, based on past
            grades, desired performance and schedule restrictions we were able
            to create the best study schedule possible
            to <strong>maximize</strong> user <strong>performance</strong>.
          </p>
          <ul>
            <li>
              This is the project
              <a href="http://david.alvarezrosa.com:5000"
                 title="Go to the project website.">
                website
              </a>.
            </li>
            <li>
              Here is the
              <a href="https://gitlab.com/david-alvarez-rosa/horario"
                 rel="noopener"
                 target="_blank"
                 title="Source code of the project - Gitlab.">
                source code <i class="fas fa-external-link-alt"></i>
              </a>.
            </li>
          </ul>
        </div>
      </section>

      <!-- Section inspiration. -->
      <div class="anchor" id="sec:inspiration">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(4); ?>
        <blockquote>
          <p>
            <i class="fas fa-pencil-alt fa-lg"></i>
            <em>The science of today is the technology of tomorrow.</em>
          </p>
          <p class="quoteAuthor">
            <a href="https://en.wikipedia.org/wiki/Edward_Teller"
               rel="nofollow noopener"
               target="_blank"
               title="Edward Teller - Wikipedia.">
              ~Edward Teller <i class="fas fa-external-link-alt"></i>
            </a>
          </p>
          <div class="clear"></div>
        </blockquote>
      </section>

      <!-- Section skills. -->
      <div class="anchor" id="sec:skills">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(5); ?>
        <p>
          In recent years I have learned various programming languages and have
          worked in different computer environments both in university and as a
          personal hobby. Listed below are the skills I consider most relevant,
          but possibly outdated, because I am always trying to learn new things!
        </p>
        <ul id="skillsList">
          <li>
            <strong>Programming languages.</strong>
            <ul style="margin: .75em 0;">
              <li>Solid knowledge of:
                <ul class="skills">
                  <li>C++ <i class="fas fa-code"></i></li>
                  <li>Python <i class="fab fa-python"></i></li>
                  <li>Octave/Matlab <i class="fas fa-chart-area"></i></li>
                </ul>
              </li>
              <li>Notions of:
                <ul class="skills">
                  <li>
                    <a href="https://en.wikipedia.org/wiki/AMPL"
                       rel="nofollow noopener"
                       target="_blank"
                       title="A Mathematical Programming Language - Wikipedia.">
                      AMPL <i class="fas fa-code"></i>
                      <i class="hide fas fa-external-link-alt"></i>
                    </a>
                  </li>
                  <li>Bash <i class="fas fa-terminal"></i></li>
                </ul>
              </li>
            </ul>
          </li>
          <li>Experience in <strong>web development</strong>.
            <ul class="skills">
              <li>HTML <i class="fab fa-html5"></i></li>
              <li>CSS <i class="fab fa-css3-alt"></i></li>
              <li>JavaScript <i class="fab fa-js-square"></i></li>
              <li>PHP <i class="fab fa-php"></i></li>
            </ul>
          </li>
          <li>Others.
            <ul class="skills">
              <li>Linux <i class="fab fa-linux"></i></li>
              <li>Git <i class="fab fa-git-alt"></i></li>
              <li>
                <a href="https://www.ros.org/"
                   rel="nofollow noopener"
                   target="_blank"
                   title="Robot Operating System website.">
                  ROS <i class="fas fa-robot"></i>
                  <i class="hide fas fa-external-link-alt"></i>
                </a>
              </li>
              <li>LaTeX <i class="fas fa-file-alt"></i></li>
            </ul>
          </li>
        </ul>
      </section>

      <!-- Section languages. -->
      <div class="anchor" id="sec:languages">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(6); ?>
        <p>
          These are the languages I know.
        </p>
        <div class="language">
          <div class="languageName">Spanish</div>
          <div class="languageLevel languageLevel0"></div>
        </div>
        <p class="languageText">
          Mother tongue.
        </p>
        <div class="language">
          <div class="languageName">English</div>
          <div class="languageLevel languageLevel1"></div>
        </div>
        <p class="languageText">
          Advanced C1 level accreditation (August 2017).
        </p>
        <div class="language">
          <div class="languageName">Catalan</div>
          <div class="languageLevel languageLevel2"></div>
        </div>
        <p class="languageText">
          Very good understanding of the language. Intermediate level in oral
          and written expression.
        </p>
      </section>

      <!-- Section awards. -->
      <div class="anchor" id="sec:awards">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(7); ?>
        <p>
          I participated in some of the Spanish knowledge Olympics. More
          specifically I participated in Physics and Mathematics. These Olympics
          are competitions among young students, whose primary objective is to
          stimulate the study of Science (Mathematics, Biology, Chemistry,
          Physics) and the development of young talents in these sciences.
        </p>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-medal fa-lg"></i>
          </div>
          <div class="award">
            <h3>
              <a href="https://rsef.es/olimpiada-espanola-de-fisica"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Spanish Physics Olympiad website.">
                Spanish Physics Olympiad
                <i class="hide fas fa-external-link-alt"></i>
                <i class="titleIcon fas fa-vial"></i>
              </a>
            </h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; April 2016
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Seville, Andalusia
              </h4>
            </div>
            <p class="awardText">
              Silver medal in the national phase.
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-medal fa-lg"></i>
          </div>
          <div class="award">
            <h3>
              <a href="http://www.olimpiadamatematica.es/platea.pntic.mec.es/_csanchez/olimmain.html"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Spanish Mathematical Olympiad website.">
                Spanish Mathematical Olympiad
                <i class="hide fas fa-external-link-alt"></i>
                <i class="titleIcon fas fa-puzzle-piece"></i>
              </a>
            </h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; April 2016
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Barcelona, Catalonia
              </h4>
            </div>
            <p class="awardText">
              Competitor in the national phase.
            </p>
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-medal fa-lg"></i>
          </div>
          <div class="award">
            <h3>
              <a href="https://rsef.es/olimpiada-espanola-de-fisica"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Spanish Physics Olympiad website.">
                Spanish Physics Olympiad
                <i class="hide fas fa-external-link-alt"></i>
                <i class="titleIcon fas fa-vial"></i>
              </a>
            </h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; March 2016
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Pamplona, Navarre
              </h4>
            </div>
            <p class="awardText">
              Winner of the local phase.
            </p>
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-medal fa-lg"></i>
          </div>
          <div class="award">
            <h3>
              <a href="http://www.olimpiadamatematica.es/platea.pntic.mec.es/_csanchez/olimmain.html"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Spanish Mathematical Olympiad website.">
                Spanish Mathematical Olympiad
                <i class="hide fas fa-external-link-alt"></i>
                <i class="titleIcon fas fa-puzzle-piece"></i>
              </a>
            </h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; January 2016
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Pamplona, Navarre
              </h4>
            </div>
            <p class="awardText">
              Second position in the local phase.
            </p>
          </div>
        </div>
      </section>

      <!-- Section others.  -->
      <div class="anchor" id="sec:others">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(8); ?>
        <p>
          Here is some more extra information about me.
        </p>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-music fa-lg"></i>
          </div>
          <div class="award">
            <h3>Former violinist <i class="titleIcon fas fa-compact-disc"></i></h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; 2008 &nbsp;&ndash;&nbsp; 2015
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Pamplona, Navarre
              </h4>
            </div>
            <p class="awardText">
              I played the violin in the conservatory in its first courses.
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-users fa-lg"></i>
          </div>
          <div class="award">
            <h3>Tutor <i class="titleIcon fas fa-chalkboard-teacher"></i></h3>
            <p class="awardText">
              Academic classes of support and preparation for the Mathematical
              Olympiad.
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fab fa-linux fa-lg"></i>
          </div>
          <div class="award">
            <h3>Linux user <i class="titleIcon fas fa-laptop"></i></h3>
            <p class="awardText">
              Years of use of Linux distributions
              (<a href="https://www.archlinux.org/"
                  rel="nofollow noopener"
                  target="_blank"
                  title="Arch Linux official website.">
                Arch <i class="fas fa-external-link-alt"></i></a>,
              <a href="https://www.debian.org/"
                  rel="nofollow noopener"
                  target="_blank"
                  title="Debian official website.">
                Debian <i class="fas fa-external-link-alt"></i>
              </a> and
              <a href="https://ubuntu.com/"
                  rel="nofollow noopener"
                  target="_blank"
                  title="Ubuntu official website.">
                Ubuntu <i class="fas fa-external-link-alt"></i>
              </a>).
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-server fa-lg"></i>
          </div>
          <div class="award">
            <h3>System administrator <i class="titleIcon fas fa-terminal"></i></h3>
            <p class="awardText">
              Administrator of a personal
              <a href="https://en.wikipedia.org/wiki/Virtual_private_server"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Virtual private server - Wikipedia.">
                Virtual Private Server
                <i class="fas fa-external-link-alt"></i></a>:
              data synchronization between devices,
              <a href="https://david.alvarezrosa.com/"
                 title="David Álvarez Rosa's personal website.">
                website</a>, email, personal
              <a href="https://en.wikipedia.org/wiki/Git"
                 rel="nofollow noopener"
                 target="_blank"
                 title="Git - Wikipedia.">
                Git <i class="fas fa-external-link-alt"></i>
              </a> server.
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-heartbeat fa-lg"></i>
          </div>
          <div class="award">
            <h3>Athlete <i class="titleIcon fas fa-running"></i></h3>
            <p class="awardText">
              Regular sportsman, runner and ex-triathlete.
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-car fa-lg"></i>
          </div>
          <div class="award">
            <h3>Driving license <i class="titleIcon fas fa-id-card"></i></h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; October 2017
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Pamplona, Navarre
              </h4>
            </div>
            <p class="awardText">
              Spanish driving license (B).
          </div>
        </div>
      </section>

      <!-- Section interests. -->
      <div class="anchor" id="sec:interests">
        <i class="bouncingHand fas fa-hand-point-right"></i>
        <a onclick="javascript:takeMeBack();"
           class="undoAnchor"
           title="Take me back where I was.">
          <i class="fas fa-fast-backward"></i>
        </a>
      </div>
      <section class="hidden">
        <?php sectionHeader(9); ?>
        <p>
          This is a list of my current interests.
        </p>
	      <div id="interestsChart">
		      <canvas id="interestsChartArea"></canvas>
	      </div>
      </section>

      <!-- Section get in touch.  -->
      <?php include 'resources/getInTouch.html'; ?>
    </main>


    <!-- License (Creative Commons). -->
    <?php include 'resources/license.html'; ?>


    <!-- Footer. -->
    <?php include 'resources/footer.php'; ?>


    <!-- Javascript files. -->
    <script src="js/chart.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/home.js"></script>
    <script src="js/shortcuts.js"></script>
    <script src="js/confetti.js"></script>
  </body>
</html>
