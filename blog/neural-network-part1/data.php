<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite entry sections PHP file.
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


$sections = [
    ['name' => 'Introduction', 'icon' => 'fas fa-percentage'],
    ['name' => 'Topology', 'icon' => 'fas fa-project-diagram'],
    ['name' => 'Forward', 'icon' => 'fas fa-forward'],
    ['name' => 'Backward', 'icon' => 'fas fa-backward'],
];

$keywords = [
    'Neural Network',
    'C++',
    'Scratch',
    'Fully Connected',
    'Artificial Intelligence',
    'Deep Learning',
    'Implementing'
];

$image = 'fully-connected-neural-network.jpeg';

$cssExtra = [
    '../external/highlight/styles/atom-one-dark.css'
];

$jsExtra = [
    '<script id="MathJax-script" data-src="../external/mathjax/tex-svg.js" async></script>',
    '<script defer src="../external/highlight/highlight.pack.js"></script>',
    '<script defer src="../external/highlight/highlight-line-numbers.min.js"></script>',
    '<script type="text/javascript">
      // Warn that this website is under construction.
      setTimeout( function() { showInfo("welcomeUser"); }, 3500);
    </script>'
];
?>
