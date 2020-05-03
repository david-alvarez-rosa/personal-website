<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite entry PHP file.
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


$currentSite = 'blog';
include '../resources/blog/data.php';
$entryId = basename(getcwd());
$entry = $entries[$entryId];
include 'data.php';
$abstract = file_get_contents('abstract.html');
include '../resources/functions.php';
ob_start();
include 'content.php';
$content = ob_get_clean();
?>


<!DOCTYPE html>

<html lang="en">
   <head>
      <?php include '../resources/blog/head.php'; ?>
   </head>


   <!-- Welcome comment. -->
   <?php include '../resources/comment.html'; ?>


   <body class="preload">
      <?php include '../resources/bodyPreMain.php'; ?>


      <!-- Main. -->
      <main>
         <?php
         include '../resources/blog/abstract.php';
         echo $content;
         include '../resources/blog/controllers.php';
         ?>
      </main>


      <!-- License (Creative Commons). -->
      <?php include '../resources/license.html'; ?>


      <!-- Footer. -->
      <?php include '../resources/footer.php'; ?>


      <!-- Javascript files. -->
      <script src="/js/main.js"></script>
      <script src="/js/shortcuts.js"></script>
      <script src="/js/blog.js"></script>
      <script defer src="/js/confetti.js"></script>
      <?php
      for ($i = 0; $i < sizeof($jsExtra); ++$i)
          echo $jsExtra[$i];
      ?>
   </body>
</html>
