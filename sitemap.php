<?php
/**
 *
 * David \'Alvarez Rosa's personal website sitemap PHP file.
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


header('Content-type: application/xml; charset=utf-8');

include 'resources/cacheStart.php';

echo '<?xml version="1.0" encoding="UTF-8"?>';
?>


<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url>
    <loc>https://david.alvarezrosa.com/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/about/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pdf/cv-david-alvarez-rosa.pdf</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/es/</loc>
  </url>
  <url>
    <loc>https://blog.alvarezrosa.com/</loc>
  </url>

  <!-- Include blog entries. -->
  <?php
  include 'resources/blog/data.php';
  $entry = end($entries);
  do { ?>
    <url>
      <loc>https://blog.alvarezrosa.com/<?php echo key($entries); ?></loc>
    </url>
  <?php } while ($entry = prev($entries)) ?>

  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/proyecto/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/proyecto/documentacion</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/proyecto/documentacion/documentacion.pdf</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/jugar/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/jugar/per/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/jugar/ai/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/p1/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/p1/p1.pdf</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/p2/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/p2/p2.pdf</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/p3/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/tres-en-raya/presentaciones/p3/p3.pdf</loc>
  </url>
  <url>
    <loc>http://david.alvarezrosa.com:5000/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/grafo/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/sudoku/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/deducción/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/ajedrez/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/errores/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/sopa/</loc>
  </url>
  <url>
    <loc>https://david.alvarezrosa.com/pasatiempos-dn/código/</loc>
  </url>
</urlset>


<?php include 'resources/cacheEnd.php'; ?>
