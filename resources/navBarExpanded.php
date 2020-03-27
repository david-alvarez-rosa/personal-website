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
      <?php
      for ($i = 0, $size = count($sections); $i < $size; ++$i) {
          $section = $sections[$i]; $icon = $icons[$i];
          $class = '';
          if ($i === 0)
              $class = 'class = "current" ';
          $href = '#sec:' . strtolower(str_replace(' ', '-', $section));
          $title = 'Scroll to ' . strtolower($section) . ' section.';
          echo '<li>';
          echo '<a ' . $class . 'href="' . $href . '" title="' . $title . '">';
          echo $section . ' <i class="' . $icon . '"></i>';
          echo '</a>';
          echo '</li>';
      }
      ?>
      <li>
         <p>
            @David &copy; 2020
         </p>
      </li>
   </ul>
</nav>
