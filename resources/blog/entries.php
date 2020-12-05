<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite entries list generator PHP file.
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


include 'data.php';

$entry = end($entries);
do {
    ob_start();
    include key($entries) . '/content.php';
    $content = ob_get_clean();
    $entry['time'] = estimateReadingTime($content);
?>
  <a href="<?php echo key($entries); ?>"
     title="Read more: <?php echo $entry['title']; ?>">
    <div class="card">
      <h3>
        <?php echo $entry['title']; ?>
        <i class="titleIcon <?php echo $entry['icon']; ?>"></i>
      </h3>
      <div class="rightLeftFlex">
        <h4>
          <i class="fas fa-clock"></i> &nbsp; <?php echo $entry['time']; ?>
        </h4>
        <h4>
          <i class="fas fa-user-edit"></i> &nbsp;
          <?php echo $authors[$entry['author']]['name']; ?>
        </h4>
      </div>
      <div class="rightLeftFlex">
        <h4>
          <i class="fas fa-tags"></i> &nbsp;
          <?php
          $tags = $entry['tags'];
          $tagsLength = -3;
          for ($i = 0; $i < sizeof($tags); ++$i) {
              $tagsLength += strlen($tags[$i]) + 3;
              if ($tagsLength >= $tagsLengthMax)
                  break;
              if ($i != 0)
                  echo ' - ';
              echo $tags[$i];
          }
          if ($tagsLength >= $tagsLengthMax)
              echo ' <i class="moreTags fas fa-plus"></i>';
          ?>
        </h4>
        <h4>
          <i class="fas fa-calendar-alt"></i> &nbsp; <?php echo $entry['date']; ?>
        </h4>
      </div>
      <p class="marginTopAuxAux">
        <?php
        ob_start();
        include key($entries) . '/abstract.html';
        $abstract = ob_get_clean();
        $abstract = preg_replace('/<\\/?a(.|\\s)*?>/', '', $abstract);
        echo $abstract;
        ?>
      </p>
      <p class="readMore">
        <i class="fas fa-book-reader fa-lg"></i>
      </p>
    </div>
  </a>
<?php } while ($entry = prev($entries)) ?>
