<nav id="navBar">
   <div id="navBarDiv">
      <?php
      for ($i = 0, $size = count($sections); $i < $size; ++$i) {
          $section = $sections[$i];
          $class = '';
          if ($i === 0)
              $class = 'class = "current" ';
          $href = '#sec:' . strtolower(str_replace(' ', '-', $section));
          $title = 'Scroll to ' . strtolower($section) . ' section.';
          echo '<a ' . $class . 'href="' . $href . '" title="' . $title . '">';
          echo $section;
          echo '</a>';
      }
      ?>
   </div>
   <button id="navBarButton"
           onclick="toggleNavBar();"
           title="Expand navigation bar.">
      <i class="fas fa-bars fa-2x"></i>
   </button>
</nav>
