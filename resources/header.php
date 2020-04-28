<?php
/**
 *
 * David \'Alvarez Rosa's personal website header PHP file.
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


<header>
   <?php include "backgroundImage.html" ?>
   <a href="https://david.alvarezrosa.com"
      title="David Álvarez Rosa's personal website.">
      <h1>David Álvarez Rosa</h1>
   </a>
   <h2>
      <strong>Mathematics</strong> and <br />
      <strong>Industrial Engineering</strong> student.
   </h2>
   <div id="sites">
      <a href="."
         <?php if ($currentSite === 'home') echo 'class="currentSite"'; ?>
         title="David Álvarez Rosa's personal website.">
         Home <i class="shine shineHeader1"></i>
      </a>
      <a href="https://blog.alvarezrosa.com/"
         <?php if ($currentSite === 'blog') echo 'class="currentSite"'; ?>
         title="David Álvarez Rosa's personal blog.">
         Blog <i class="shine shineHeader2"></i>
      </a>
      <a href="pdf/cv-david-alvarez-rosa.pdf#view=Fit"
         title="My personal Curriculum Vitae.">
         CV <i class="shine shineHeader3"></i>
      </a>
   </div>
   <div id="controllers">
      <button id="backward"
              title="Show previous image."
              onclick="backwardAnimation();">
         <i class="fas fa-step-backward"></i>
      </button>
      <button id="pause"
              title="Toggle background animation state."
              onclick="toggleAnimation();">
         <i id="toggleIcon" class="fas fa-pause"></i>
      </button>
      <button id="forward"
              title="Show next image."
              onclick="forwardAnimation();">
         <i class="fas fa-step-forward"></i>
      </button>
   </div>
</header>
