<?php
/**
 *
 * David \'Alvarez Rosa's personal website expanded navigation bar PHP file.
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
    <?php foreach ($sections as $sectionNumber => $section) { ?>
      <li>
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
          <i class="<?php echo $section['icon']; ?>"
             <?php
             if (isset($section['iconStyleNav']))
                 echo ' style="' . $section['iconStyleNav'] . '"';
             ?>
          ></i>
        </a>
      </li>
    <?php } ?>

    <li>
      <p>
        @David &copy; <?php echo date("Y"); ?>
      </p>
    </li>
  </ul>
</nav>
