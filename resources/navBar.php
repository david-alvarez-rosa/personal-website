<?php
/**
 *
 * David \'Alvarez Rosa's personal website navigation bar PHP file.
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
