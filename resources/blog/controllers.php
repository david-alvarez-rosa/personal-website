<?php
/**
 *
 * David \'Alvarez Rosa's personal blogsite controllers PHP file.
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


<div id="blogControllers" class="fadeIn">
   <button class="blogButton
                  <?php
                  if (!isset($entry['previous']))
                      echo 'blogButtonInactive';
                  ?>
                  blogPrevious"
           <?php
           if (isset($entry['previous'])) {
               echo 'onclick="window.location.href = \'../';
               echo $entry['previous'];
               echo '\';"';
           }
           ?>
   >
      <i class="fas fa-arrow-left"></i> Previous blog entry
   </button>
   <button class="blogButton
                  <?php
                  if (!isset($entry['next']))
                      echo 'blogButtonInactive';
                  ?>
                  blogNext"
           <?php
           if (isset($entry['next'])) {
               echo 'onclick="window.location.href = \'../';
               echo $entry['next'];
               echo '\';"';
           }
           ?>
   >
      Next blog entry <i class="fas fa-arrow-right"></i>
   </button>
</div>
