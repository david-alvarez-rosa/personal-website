<?php
/**
 *
 * David \'Alvarez Rosa's personal website background images PHP file.
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


// For resizing images the command is:
// mogrify -scale '2%' -scale '5000%' -resize 10% -path low/ *.jpg
// mogrify -resize 480 -path med/ *.jpg
// mogrify -resize 1250 -path high/ *.jpg

$backgrounds = [
    ['name' => 'Flower', 'color' => '#C98E03'],
    ['name' => 'Leaves', 'color' => '#3A6F1F'],
    ['name' => 'Lighthouse', 'color' => '#DF7E3A'],
    ['name' => 'Mountain', 'color' => '#56737B'],
    ['name' => 'Boat', 'color' => '#BB6D1F'],
    ['name' => 'Flame', 'color' => '#E27822'],
    ['name' => 'Road', 'color' => '#687274'],
];

$isMobile = is_numeric(stripos($_SERVER['HTTP_USER_AGENT'], 'mobile'));
?>


<div class="background">
  <?php
  foreach ($backgrounds as $background) {
      $name = $background['name'];
      $path = strtolower($name);
      $color = $background['color'];
      $root = '/img/backgrounds/high/';
      if ($isMobile)
          $root = '/img/backgrounds/med/'
  ?>
    <img class="backgroundImage"
         src="/img/backgrounds/low/<?php echo $path . '.jpg'; ?>"
         data-src="<?php echo $root . $path . '.jpg'; ?>"
         data-color="<?php echo $color; ?>"
         alt="Background image: <?php echo $name; ?>." />
  <?php
  }
  ?>
</div>
<div class="overlay"></div>
