<?php
/**
 *
 * David \'Alvarez Rosa's personal website expanded navigation bar PHP file.
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
