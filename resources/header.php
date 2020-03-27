<header>
   <div class="background">
      <img class="backgroundImage"
           data-src="img/backgrounds/autumn"
           alt="Background image: Autumn." />
      <img class="backgroundImage"
           data-src="img/backgrounds/beach"
           alt="Background image: Beach." />
      <img class="backgroundImage"
           data-src="img/backgrounds/flower"
           alt="Background image: Flower." />
      <img class="backgroundImage"
           data-src="img/backgrounds/leaves"
           alt="Background image: Leaves." />
      <img class="backgroundImage"
           data-src="img/backgrounds/lighthouse"
           alt="Background image: Lighthouse." />
      <img class="backgroundImage"
           data-src="img/backgrounds/mountain"
           alt="Background image: Mountain." />
      <img class="backgroundImage"
           data-src="img/backgrounds/stars"
           alt="Background image: Stars." />
   </div>
   <div class="overlay"></div>
   <a href="."
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
         Home
      </a>
      <a href="https://blog.alvarezrosa.com/"
         <?php if ($currentSite === 'blog') echo 'class="currentSite"'; ?>
         title="David Álvarez Rosa's personal blog.">
         Blog
      </a>
      <a href="pdf/curriculum-vitae.pdf#view=Fit"
         title="My personal Curriculum Vitae.">
         CV
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
