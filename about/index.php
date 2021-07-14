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
    <title>About | David Álvarez Rosa | Personal Website</title>
    <meta charset="UTF-8" />
    <meta name="description" content="Graduate in Mathematics and Industrial
                Technology Engineering from UPC (BarcelonaTech), currently
                studying the M.Sc. in Advanced Mathematics from UNED." />
    <meta name="keywords" content="David Álvarez Rosa, David Álvarez, David,
                Curriculum Vitae, Mathematics, Engineering" />
    <meta name="author" content="David Álvarez Rosa" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="canonical" href="https://david.alvarezrosa.com/about" />
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

    <!-- Meta tags for social media. -->
    <meta property="og:title" content="David Álvarez Rosa | Personal Website" />
    <meta property="og:description" content="Personal Website." />
    <meta property="og:image"
          content="https://david.alvarezrosa.com/img/backgrounds/mountain.jpg" />
    <meta property="og:url" content="https://david.alvarezrosa.com/" />
    <meta property="og:type" content="website" />
    <meta name="twitter:card" content="summary_large_image" />

    <meta name="msapplication-TileColor" content="#DBDCDC" />
    <meta name="msapplication-config" content="img/icons/browserconfig.xml" />
    <meta name="theme-color" content="#FFFFFF" />

    <!-- CSS files. -->
    <link rel="stylesheet" href="css/main.css" />
    <link rel="stylesheet" href="css/animations.css" />
    <link rel="stylesheet" id="fontawesome"
          data-href="external/fontawesome/css/all.min.css" />
    <?php
    if (isset($_GET['theme']) and $_GET['theme'] == 'suckless') { ?>
      <link rel="stylesheet" href="css/suckless.css" />
    <?php } ?>

    <!-- Javascript files. -->
    <script defer src="external/chart.min.js"></script>
    <script defer src="js/main.js"></script>
    <script defer src="js/about.js"></script>
    <script defer src="js/shortcuts.js"></script>
    <script defer src="js/confetti.js"></script>

    <script type="application/ld+json">
     {
         "@context": "https://schema.org/",
         "@type": "Person",
         "honorificPrefix": "Mr",
         "name": "David Álvarez Rosa",
         "image": "img/icons/icon.png",
         "jobTitle": "Mathematics and Industrial Engineering graduate",
         "disambiguatingDescription": "Mathematics and Industrial Engineering graduate",
         "alumniOf": "Polytechnic University of Catalonia",
         "affiliation": "Polytechnic University of Catalonia",
         "birthPlace": "Navarre, Spain",
         "birthDate": "1998-10-10",
         "gender": "male",
         "memberOf": "Driverless UPC - School of Engineering Motorsport",
         "nationality": "Spanish",
         "url": "https://david.alvarezrosa.com/",
         "award": "",
         "knowsAbout": "",
         "knowsLanguage": ["es", "en", "cat"]
     }
    </script>
  </head>


  <body class="preload">
    <?php
    $currentSite = 'about';
    $sections = [
        ['name' => 'About me', 'shortName' => 'About', 'icon' => 'fas fa-address-card'],
        ['name' => 'Experience', 'icon' => 'fas fa-briefcase'],
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
      <section class="hidden">
        <?php sectionHeader(); ?>

        <a href="img/portrait.png"
           title="David Álvarez Rosa headshot portrait.">
          <img id="portrait" src="img/portrait_low.png"
               alt="David Álvarez Rosa headshot portrait." />
        </a>
        <p>
          Passionate about Maths, Artificial Intelligence and Engineering. I am
          an outgoing person, with a highly analytical mindset, who likes to
          debate and learn from others. I have the ability to motivate those who
          surround me in order to provide meaningful solutions for real
          problems, with a goal-oriented approach.
        </p>
        <p>
          Conducted research on Artificial Intelligence for my senior
          <a href="https://recomprehension.com"
             title="Exploring and Visualizing Referring Expression Comprehension">
            Bachelor's Thesis <i class="fas fa-external-link-alt"></i></a>
          at the Computer Science department of the University of Toronto under
            the supervision of Prof.
          <a href="https://www.cs.utoronto.ca/~fidler/"
             title="Sanja Fidler personal website.">
            Sanja Fidler <i class="fas fa-external-link-alt"></i></a>
          (UofT & NVIDIA) and Prof.
          <a href="https://imatge.upc.edu/web/people/xavier-giro"
             title="Xavier Giró-i-Nieto personal website.">
            Xavier Giró-i-Nieto <i class="fas fa-external-link-alt"></i></a>
           (UPC & BSC), world
          leading experts in the field of Computer Vision.
        </p>
        <p>
          Graduate in Mathematics and Industrial Technology Engineering from
          UPC (BarcelonaTech), currently studying the M.Sc. in Advanced
          Mathematics from UNED, and actively seeking for job opportunities at
          a leading company in order to get real-world experience and grow as a
          professional. For any further information feel free to check out my
          <a href="https://david.alvarezrosa.com"
             title="David Álvarez Rosa's personal website.">
            personal website</a>
          or contact me via
          <a href="https://www.linkedin.com/in/david-alvarez-rosa"
             title="Personal LinkedIn page.">
            LinkedIn <i class="fas fa-external-link-alt"></i></a> or
          <a href="javascript:showInfo('infoEmail');"
             title="Show my personal email.">
            email</a>. Below is some basic information about me.
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
      </section>


      <!-- Section experience. -->
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          Below, you will find details of my experience summarized and listed in
          reverse chronological order.
        </p>
        <div class="card">
          <h3>Visiting Researcher<i class="titleIcon fas fa-eye"></i></h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https:vectorinstitute.ai"
                 title="Vector Institute official website.">
                Vector Institute
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; Full Time &nbsp;&ndash;&nbsp; 10 months
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2020 &nbsp;&ndash;&nbsp; June 2021
            </h4>
            <h4>
              <i class="fas fa-laptop"></i>
              &nbsp; Toronto, Canada
            </h4>
          </div>
          <p class="marginTop">
            During my research thesis at the University of Toronto, I was
            admitted in Vector Institute, which allowed me to experience the
            world of research ﬁrst-hand using their exceptional
            high-performance computing resources with the technical assistance
            of the development team.
          </p>
        </div>
        <div class="card">
          <h3>
            <a href="https://gitlab.com/david-alvarez-rosa/lidar-compensation"
               title="LiDAR Compensation - Gitlab">
              Perception Engineer (LiDAR)
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-car"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://driverless.upc.edu/"
                 title="Driverless UPC website.">
                Driverless UPC
                <i class="hide fas fa-external-link-alt"></i>
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
            engineers in charge of the designing, manufacturing and testing of a
            <strong>fully autonomous</strong> car that will participate in
            national and international competitions between universities. LiDAR
            responsible and computer vision collaborator.
          </p>
        </div>
      </section>


      <!-- Section education. -->
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          Below, you will find the details of my education summarized and listed
          in reverse chronological order.
        </p>

        <div class="card">
          <h3>
            <a href="http://portal.uned.es/portal/page?_pageid=93,71396234&_dad=portal&_schema=PORTAL&idTitulacion=215201"
               title="MSc in Advanced Mathematics website.">
              MSc in Advanced Mathematics
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-shapes"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://www.uned.es/universidad/inicio.html"
                 title="National University of Distance Education website.">
                National University of Distance Education
                <i class="hide fas fa-external-link-alt"></i></a>
              &nbsp;&ndash;&nbsp;
              <a href="https://www.uned.es/universidad/facultades/ciencias.html"
                 title="Faculty of Sciences website.">
                Faculty of Sciences
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 60
              <a href="https://en.wikipedia.org/wiki/European_Credit_Transfer_and_Accumulation_System"
                 title="European Credit Transfer and Accumulation System - Wikipedia.">
                ECTS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2021 &nbsp;&ndash;&nbsp; June 2023
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Madrid, Madrid
            </h4>
          </div>
          <p class="marginTop">
            Math-lover part-time student. Official study program focused on
            research and enabling PhD.
          </p>
          <ul>
            <li>
              Real and Complex Analysis, Measure Theory and Integration,
              Functional Analysis, Operators in Banach Spaces, Riemann
              Surfaces.
            </li>
          </ul>
        </div>

        <div class="card">
          <h3>
            <a href="https://recomprehension.com"
               title="Exploring and Visualizing Referring Expression Comprehension.">
              Bachelor's Thesis
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-brain"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://utoronto.ca"
                 title="University of Toronto website.">
                University of Toronto
                <i class="hide fas fa-external-link-alt"></i></a>
              &nbsp;&ndash;&nbsp;
              <a href="https://web.cs.toronto.edu/"
                 title="Dept. of Computer Science website.">
                Dept. of Computer Science
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 10 months
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2020 &nbsp;&ndash;&nbsp; June 2021
            </h4>
            <h4>
              <i class="fas fa-laptop"></i>
              &nbsp; Toronto, Ontario
            </h4>
          </div>
          <p class="marginTop">
            Conducted research on Artificial Intelligence for my senior
            <a href="https://recomprehension.com"
               title="Exploring and Visualizing Referring Expression Comprehension">
              Bachelor's Thesis <i class="fas fa-external-link-alt"></i></a>
            at the Computer Science department of the
            <a href="https://www.utoronto.ca/"
               title="University of Toronto official website.">
              University of Toronto <i class="fas fa-external-link-alt"></i></a>
            under the supervision of Prof.
            <a href="https://www.cs.utoronto.ca/~fidler/"
               title="Sanja Fidler personal website.">
              Sanja Fidler <i class="fas fa-external-link-alt"></i></a>
            (UofT & NVIDIA) and Prof.
            <a href="https://imatge.upc.edu/web/people/xavier-giro"
               title="Xavier Giró-i-Nieto personal website.">
              Xavier Giró-i-Nieto <i class="fas fa-external-link-alt"></i></a>
            (UPC & BSC), world leading experts in the field of Computer Vision.
          </p>
          <ul>
            <li>
              Artificial Intelligence &bullet; Machine Learning &bullet; Deep
              Learning &bullet; Computer Vision &bullet; Natural Language
              Processing &bullet; Multimodal Learning.
            </li>
            <li>
              GPA 10/10 (A+).
            </li>
          </ul>
        </div>

        <div class="card">
          <h3>
            <a href="https://www.upc.edu/en/bachelors/mathematics-barcelona-fme"
               title="Degree in Mathematics website.">
              BSc in Mathematics
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-infinity"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i></a>
              &nbsp;&ndash;&nbsp;
              <a href="https://fme.upc.edu/en"
                 title="Faculty of Mathematics and Statistics website.">
                Faculty of Mathematics
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 240
              <a href="https://en.wikipedia.org/wiki/European_Credit_Transfer_and_Accumulation_System"
                 title="European Credit Transfer and Accumulation System - Wikipedia.">
                ECTS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2016 &nbsp;&ndash;&nbsp; June 2021
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
            <li>
              Relevant coursework: Linear Algebra, Calculus, Mathematical
              Programming, Algorithmics, Abstract Algebra, Geometry, Analysis,
              Differential Equations, Probability and Statistics.
            </li>
            <li>
              GPA 8.12/10.
            </li>
            <li>
              <strong>Honors</strong> or excellence in 9 subjects.
            </li>
          </ul>
        </div>

        <div class="card">
          <h3>
            <a href="https://www.upc.edu/en/bachelors/industrial-technology-engineering-barcelona-etseib"
               title="Degree in Industrial Technology Engineering website.">
              BEng in Industrial Technologies
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-cogs"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 title="Barcelona School of Industrial Engineering website.">
                School of Engineering <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 240
              <a href="https://en.wikipedia.org/wiki/European_Credit_Transfer_and_Accumulation_System"
                 title="European Credit Transfer and Accumulation System - Wikipedia.">
                ECTS <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2016 &nbsp;&ndash;&nbsp; June 2021
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
            <li>
              Relevant coursework: Mechanics, Thermodynamics, Electromagnetism,
              Electrotechnics, Fluid Mechanics, Materials and Electronics.
            </li>
            <li>
              GPA 8.03/10.
            </li>
            <li>
              <strong>Honors</strong> or excellence in 14 subjects.
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
          <p class="marginTop">
            Finished with honors obtaining 12.76/14 on University Access Exam
            (<a href="https://en.wikipedia.org/wiki/Selectividad"
                title="Search Selectividad in Wikipedia."><em>Selectividad <i class="fas fa-external-link-alt"></i></em></a>).
          </p>
        </div>
      </section>


      <!-- Section courses. -->
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          I have attended the following extracurricular courses.
        </p>
        <div class="card">
          <h3>
            <a href="https://www.edx.org/course/blockchain-and-fintech-basics-applications-and-lim"
               title="Blockchain & Financial Technology website.">
              Blockchain & Financial Technology
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-wallet"></i></a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://hku.hk/"
                 title="The University of Hong Kong website.">
                The University of Hong Kong
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>&nbsp;
              24 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; June 2021 &nbsp;&ndash;&nbsp; Present
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Pamplona, Navarre
            </h4>
          </div>
          <p class="marginTop">
            Currently enrolled in this online course.
          </p>
        </div>

        <div class="card">
          <h3>
            <a href="https://www.deeplearning.ai/deep-learning-specialization/"
               title="Deep Learning Specialization website.">
              Deep Learning Specialization
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-project-diagram"></i></a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://deeplearning.ai"
                 title="deeplearning.ai website.">
                deeplearning.ai
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>&nbsp;
              80 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; June 2021 &nbsp;&ndash;&nbsp; Present
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Pamplona, Navarre
            </h4>
          </div>
          <p class="marginTop">
            Currently enrolled in this online course.
          </p>
        </div>

        <div class="card">
          <h3>
            <a href="http://cs231n.stanford.edu/"
               title="CNNs for Visual Recognition website.">
              CNNs for Visual Recognition
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-lg fa-image"></i></a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://www.stanford.edu/"
                 title="Stanford University website.">
                Stanford University
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>&nbsp;
              10-week course
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; October 2020
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Pamplona, Navarre
            </h4>
          </div>
          <p class="marginTop">
            Completed all coursework.
          </p>
        </div>

        <div class="card">
          <h3>
            <a href="https://www.coursera.org/account/accomplishments/certificate/CV6ND58V5Q4A"
               title="Machine Learning course certificate.">
              Machine Learning
              <i class="hide fas fa-external-link-alt"></i>
              <i class="titleIcon fas fa-lg fa-chart-line"></i></a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://www.stanford.edu/"
                 title="Stanford University website.">
                Stanford University
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://coursera.org"
                 title="Coursera website.">
                Coursera
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>&nbsp;
              60 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; August 2020 &nbsp;&ndash;&nbsp; October 2020
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Pamplona, Navarre
            </h4>
          </div>
          <p class="marginTop">
            Here it is the
            <a href="https://www.coursera.org/account/accomplishments/certificate/CV6ND58V5Q4A"
               title="Machine Learning course certificate.">
              certificate
              <i class="fas fa-external-link-alt"></i></a>.
          </p>
        </div>

        <div class="card">
          <h3>Mathematical Game Theory<i class="titleIcon fas fa-dice"></i></h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://cfis.upc.edu"
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
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://cfis.upc.edu"
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
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          Here are a few projects I've worked on recently. Do you have any
          questions about them?
          <a href="#sec:touch" title="Scroll to get in touch section.">
            Get in touch</a>. These projects have helped me to improve my skills
          in software development and programming, which are a great complement
          to my technical knowledge acquired at university.
        </p>

        <div class="card">
          <h3>
            <a href="https://recomprehension.com"
               title="Go to the project website.">
              Exploring and Visualizing Referring Expression Comprehension
              <i class="titleIcon fas fa-eye"></i>
            </a>
          </h3>
          <div class="clear"></div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://utoronto.ca"
                 title="University of Toronto website.">
                University of Toronto
                <i class="hide fas fa-external-link-alt"></i></a>
              &nbsp;&ndash;&nbsp;
              <a href="https://web.cs.toronto.edu/"
                 title="Dept. of Computer Science website.">
                Dept. of Computer Science
                <i class="hide fas fa-external-link-alt"></i>
              </a>
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 10 months
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2020 &nbsp;&ndash;&nbsp; June 2021
            </h4>
            <h4>
              <i class="fas fa-laptop"></i>
              &nbsp; Toronto, Ontario
            </h4>
          </div>
          <p class="marginTop">
            Human-machine interaction is one of the main objectives currently
            in the field of Artificial Intelligence. This work will contribute
            to enhance this interaction by exploring the new task of Referring
            Expression Comprehension (REC), consisting of: given a referring
            expression&mdash;which can be a linguistic phrase or human
            speech&mdash;and an image, detect the object to which the
            expression refers (i.e., achieve a binary segmentation of the
            referred object).
          </p>
        </div>

        <div class="card">
          <h3>
            <a href="https://gitlab.com/david-alvarez-rosa/neural-network"
               title="Go to the project website (with source code).">
              Neural Network Implementation <i class="titleIcon fas fa-project-diagram"></i>
            </a>
          </h3>
          <div class="clear"></div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              Personal project
            </h4>
            <h4>
              <i class="fas fa-layer-group"></i>
              &nbsp; 25 hours
            </h4>
          </div>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-calendar-alt"></i>
              &nbsp; September 2019 &nbsp;&ndash;&nbsp; October 2020
            </h4>
            <h4>
              <i class="fas fa-map-marker-alt"></i>
              &nbsp; Pamplona, Navarre
            </h4>
          </div>
          <p class="marginTop">
            Implementation of a feedforward (fully connected) Neural Network
            from scratch in C++, based on the object-oriented programming
            paradigm. Accordingly, classes have been created in increasing
            order of abstraction to define the topology of this neural model:
            Neuron, Layer and NeuralNetwork. Keeping in mind the ease of use
            for the user and allowing the greatest possible flexibility,
            classes are also designed for reading data (Data) and for
            configuring the network (Custom). The source code of this project
            can be found in my
            <a href="https://gitlab.com/david-alvarez-rosa/neural-network"
               title="Go to the project website (with source code).">
              personal Gitlab page</a>.
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
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 title="Barcelona School of Industrial Engineering website.">
                School of Engineering <i class="hide fas fa-external-link-alt"></i>
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
               title="Personal Gitlab page.">
              personal Gitlab page <i class="fas fa-external-link-alt"></i></a>.
            You may want to check out the
            <a href="/tres-en-raya/"
               title="Robotic arm - Tic Tac Toe website.">
              project website</a>.
          </p>
        </div>
        <div class="card">
          <h3>Private Email Server<i class="titleIcon fas fa-server"></i></h3>
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
            great technology. That's the reason why I decided to run my
            <strong>own email server</strong>. After all, that is how email is
            <em>designed to work</em>.
          </p>
          <ul>
            <li>
              Configuration of a modern, secure personal email server based on
              free software
              (<a href="http://www.postfix.org/"
                  title="The Postfix Home Page.">Postfix <i class="fas fa-external-link-alt"></i>
              </a>and
              <a href="https://www.dovecot.org/"
                 title="Dovecot, The Secure IMAP server.">
                Dovecot <i class="fas fa-external-link-alt"></i></a>).
            </li>
            <li>
              Supports opportunistic
              <a href="https://en.wikipedia.org/wiki/Transport_Layer_Security"
                 title="Transport Layer Security - Wikipedia.">
                TLS <i class="fas fa-external-link-alt"></i></a>
              and
              <a href="https://en.wikipedia.org/wiki/Internet_Message_Access_Protocol"
                 title="Internet Message Access Protocol - Wikipedia.">
                IMAP <i class="fas fa-external-link-alt"></i></a>
              access.
            </li>
            <li>
              <a href="https://en.wikipedia.org/wiki/DomainKeys_Identified_Mail"
                 title="DomainKeys Identified Mail - Wikipedia.">
                DKIM <i class="fas fa-external-link-alt"></i></a>,
              <a href="https://en.wikipedia.org/wiki/Sender_Policy_Framework"
                 title="Sender Policy Framework - Wikipedia.">
                SPF <i class="fas fa-external-link-alt"></i></a>, and
              <a href="https://en.wikipedia.org/wiki/DMARC"
                 title="Domain-based Message Authentication, Reporting and Conformance - Wikipedia.">
                DMARC <i class="fas fa-external-link-alt"></i></a>
              records to ensure outgoing mail is not spam-filtered.
            </li>
          </ul>
        </div>

        <div class="card">
          <h3>
            <a href="http://david.alvarezrosa.com:5000" title="Go to the project website.">
              Study Schedule Generator <i class="titleIcon fas fa-calendar-alt"></i>
            </a>
          </h3>
          <div class="rightLeftFlex">
            <h4>
              <i class="fas fa-university"></i> &nbsp;
              <a href="https://upc.edu/en"
                 title="Polytechnic University of Catalonia website.">
                Polytechnic University of Catalonia
                <i class="hide fas fa-external-link-alt"></i>
              </a>
              &nbsp;&ndash;&nbsp;
              <a href="https://etseib.upc.edu/en"
                 title="Barcelona School of Industrial Engineering website.">
                School of Engineering <i class="hide fas fa-external-link-alt"></i>
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
            to create the best study schedule possible to
            <strong>maximize</strong> user <strong>performance</strong>.
          </p>
          <ul>
            <li>
              This is the project
              <a href="http://david.alvarezrosa.com:5000"
                 title="Go to the project website.">
                website</a>.
            </li>
            <li>
              Here is the
              <a href="https://gitlab.com/david-alvarez-rosa/horario"
                 title="Source code of the project - Gitlab.">
                source code <i class="fas fa-external-link-alt"></i></a>.
            </li>
          </ul>
        </div>
      </section>

      <!-- Section inspiration. -->
      <section class="hidden">
        <?php sectionHeader(); ?>
        <blockquote>
          <p>
            <i class="fas fa-pencil-alt fa-lg"></i>
            <em>The science of today is the technology of tomorrow.</em>
          </p>
          <p class="quoteAuthor">
            <a href="https://en.wikipedia.org/wiki/Edward_Teller"
               title="Edward Teller - Wikipedia.">
              ~Edward Teller <i class="fas fa-external-link-alt"></i></a>
          </p>
          <div class="clear"></div>
        </blockquote>
      </section>

      <!-- Section skills. -->
      <section class="hidden">
        <?php sectionHeader(); ?>
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
              <li>Worked with:
                <ul class="skills">
                  <li>Bash <i class="fas fa-terminal"></i></li>
                  <li>R <i class="fas fa-chart-bar"></i></li>
                  <li>PyTorch <i class="fas fa-network-wired"></i></li>
                  <li>
                    <a href="https://en.wikipedia.org/wiki/AMPL"
                       title="A Mathematical Programming Language - Wikipedia.">
                      AMPL <i class="fas fa-code"></i>
                      <i class="hide fas fa-external-link-alt"></i>
                    </a>
                  </li>
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
      <section class="hidden">
        <?php sectionHeader(); ?>
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
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          I participated in some of the Spanish knowledge Olympiads, more
          specifically in Physics and Mathematics. These Olympiads are
          competitions among young students, whose primary objective is to
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
      <section class="hidden">
        <?php sectionHeader(); ?>
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
            <h3>Mathematics Tutor <i class="titleIcon fas fa-chalkboard-teacher"></i></h3>
            <p class="awardText">
              Academic classes of support and preparation for the Mathematical
              Olympiad.
          </div>
        </div>
        <div class="card cardAward">
          <div class="awardTrophyIcon">
            <i class="fas fa-shopping-basket fa-lg"></i>
          </div>
          <div class="award">
            <h3>Food Gatherer <i class="titleIcon fas fa-utensils"></i></h3>
            <div class="rightLeftFlex">
              <h4>
                <i class="fas fa-calendar-alt"></i>
                &nbsp; 2014 &nbsp;&ndash;&nbsp; 2016
              </h4>
              <h4>
                <i class="fas fa-map-marker-alt"></i>
                &nbsp; Pamplona, Navarre
              </h4>
            </div>
            <p class="awardText">
              Food collection for the needy (volunteer).
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
                  title="Arch Linux official website.">Arch <i class="fas fa-external-link-alt"></i></a>,
              <a href="https://www.debian.org/"
                 title="Debian official website.">
                Debian <i class="fas fa-external-link-alt"></i></a>
              and
              <a href="https://ubuntu.com/"
                 title="Ubuntu official website.">
                Ubuntu <i class="fas fa-external-link-alt"></i></a>).
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
                 title="Virtual private server - Wikipedia.">
                Virtual Private Server
                <i class="fas fa-external-link-alt"></i></a>:
              data synchronization between devices,
              <a href="https://david.alvarezrosa.com/"
                 title="David Álvarez Rosa's personal website.">
                website</a>, email, personal
              <a href="https://en.wikipedia.org/wiki/Git"
                 title="Git - Wikipedia.">
                Git <i class="fas fa-external-link-alt"></i></a>
              server.
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
      <section class="hidden">
        <?php sectionHeader(); ?>
        <p>
          Interested in areas of knowledge that involve my two ﬁelds of studies in
          conjunction, such as Artiﬁcial Intelligence, Robotics and Data Analysis.
        </p>
	      <div id="interestsChart">
		      <canvas id="interestsChartArea"></canvas>
	      </div>
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
