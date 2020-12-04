<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite entries variables PHP file.
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


$tagsLengthMax = 36;

$basicKeywords = [
    'David Álvarez Rosa',
    'David Álvarez',
    'David',
    'Personal Blog',
    'Blog',
    'Blogsite',
    'Blog Entry',
    'Entry',
    'Mathematics',
    'Engineering',
    'Technology'
];

$authors = [
    'me' => [
        'name' => 'David Álvarez Rosa',
        'webUrl' => 'https://david.alvarezrosa.com',
        'webTitle' => 'David Álvarez Rosa\'s personal website'
    ]
];

$entries = [
    'hello-world' => [
        'title' => 'Hello World!',
        'icon' => 'fas fa-bullhorn',
        'author' => 'me',
        'date' => 'November 7, 2019',
        'tags' => [
            'Blog',
            'Entry',
            'Greeting'
        ],
        'next' => 'neural-network-part1'
    ],
    'neural-network-part1' => [
        'title' => 'Implementing a Neural Network from scratch &ndash; Part 1',
        'icon' => 'fas fa-project-diagram',
        'author' => 'me',
        'date' => 'May 3, 2020',
        'tags' => [
            'Neural Network',
            'AI',
            'Deep Learning',
            'Machine Learning',
            'C++',
            'Implementation',
            'Scratch'
        ],
        'previous' => 'hello-world'
    ]
];
?>
