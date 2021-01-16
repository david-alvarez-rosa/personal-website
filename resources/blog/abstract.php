<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite abstract generator PHP file.
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


<p class="fadeIn" style="margin-top: 2.5em;">
   This website does not (and won't ever) use cookies. I value your
   privacy.
</p>

<h1 class="fadeIn"><?php echo $entry['title']; ?></h1>
<div class="subTitle fadeIn">
   <div class="rightLeftFlex">
      <h4>
         <i class="fas fa-clock"></i> &nbsp;
         <?php echo $entry['time']; ?>
      </h4>
      <h4>
         <i class="fas fa-user-edit"></i> &nbsp;
         <a href="<?php echo $authors[$entry['author']]['webUrl']; ?>"
            title="<?php echo $authors[$entry['author']]['webTitle']; ?>">
            <?php echo $authors[$entry['author']]['name']; ?>
         </a>
      </h4>
   </div>
   <div class="rightLeftFlex">
      <h4>
         <i class="fas fa-tags"></i> &nbsp;
         <?php
         $tags = $entry['tags'];
         $tagsLength = -3;
         $last = 0;
         for ($i = 0; $i < sizeof($tags); ++$i) {
             $tagsLength += strlen($tags[$i]) + 3;
             if ($tagsLength >= $tagsLengthMax) {
                 $last = $i;
                 break;
             }
             if ($i != 0)
                 echo ' - ';
             echo $tags[$i];
         }
         if ($tagsLength >= $tagsLengthMax) {
             echo '<span id="callOutTags1" class="callOut">
             <a href="javascript:enlargeCallOut(\'callOutTags1\');"
             title="Click to see this information bigger.">
             <i class="moreTags fas fa-plus"></i>
             </a>
             <span><p>';
             for ($i = $last; $i < sizeof($tags); ++$i) {
                 if ($i != $last)
                     echo ', ';
                 echo $tags[$i];
             }
             echo '</p></span></span>';
         }
         ?>
      </h4>
      <h4>
         <i class="fas fa-calendar-alt"></i> &nbsp;
         <?php echo $entry['date']; ?>
      </h4>
   </div>
   <p class="marginTopAux">
      <?php include '../' . $entryId . '/abstract.html'; ?>
   </p>
</div>
