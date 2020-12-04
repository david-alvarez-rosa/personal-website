<?php
/**
 *
 * David \'Alvarez Rosa's personal website background images PHP file.
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


$backgrounds = [
    ['name' => 'Autumn', 'color' => '#D45B12'],
    ['name' => 'Flower', 'color' => '#C98E03'],
    ['name' => 'Leaves', 'color' => '#3A6F1F'],
    ['name' => 'Lighthouse', 'color' => '#DF7E3A'],
    ['name' => 'Mountain', 'color' => '#56737B']
];
?>

<div class="background">
  <?php
  foreach ($backgrounds as $background) {
  $name = $background['name'];
  $path = strtolower($name);
  $color = $background['color'];

  ?>
    <img class="backgroundImage"
         data-src="/img/backgrounds/<?php echo $path; ?>"
         data-color="<?php echo $color; ?>"
         alt="Background image: <?php echo $name; ?>." />
  <?php
  }
  ?>
</div>
<div class="overlay"></div>
