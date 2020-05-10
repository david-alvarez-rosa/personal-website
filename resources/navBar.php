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
      <?php foreach ($sections as $sectionNumber => $section) { ?>
         <?php
         $shortName = $section['name'];
         if (isset($section['shortName']))
             $shortName = $section['shortName'];
         $class = '';
         if ($sectionNumber === 0)
             $class = 'current';
         $href = '#sec:' . strtolower(str_replace(' ', '-', $shortName));
         $title = 'Scroll to ' . strtolower($shortName) . ' section.';
         ?>

         <a class="<?php echo $class; ?>"
            href="<?php echo $href; ?>"
            title="<?php echo $title; ?>">
            <?php echo $shortName ?>
         </a>

      <?php } ?>
   </div>
   <button id="navBarButton"
           onclick="toggleNavBar();"
           title="Expand navigation bar.">
      <i class="fas fa-bars fa-2x"></i>
   </button>
</nav>
