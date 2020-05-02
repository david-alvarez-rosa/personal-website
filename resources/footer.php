<?php
/**
 *
 * David \'Alvarez Rosa's personal website footer PHP file.
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
?>


<footer>
   <?php include "backgroundImage.html" ?>
   <ul>
      <li>
         <a class="fab fa-gitlab fa-2x"
            href="https://gitlab.com/david-alvarez-rosa"
            rel="noopener"
            target="_blank"
            title="Personal Gitlab page.">
         </a>
         <br />
         Gitlab
      </li>
      <li>
         <a class="fab fa-linkedin fa-2x"
            style="padding-left: 5px;"
            href="https://www.linkedin.com/in/david-alvarez-rosa"
            rel="noopener"
            target="_blank"
            title="Personal LinkedIn page.">
         </a>
         <br />
         LinkedIn
      </li>
      <li>
         <a class="fas fa-at fa-2x"
            href="javascript:showInfo('infoEmail');"
            title="Show my personal email.">
         </a>
         <br />
         Email
      </li>
      <li>
         <a class="fas fa-phone fa-2x"
            href="javascript:showInfo('infoPhone');"
            title="Show my personal phone number.">
         </a>
         <br />
         Phone
      </li>
      <li>
         <a class="fas fa-envelope fa-2x"
            href="javascript:showInfo('infoAddress');"
            title="Show my personal address.">
         </a>
         <br />
         Address
      </li>
   </ul>
   <p id="author" onmouseover="beatHeart();" onmouseout="hideHeart();">
      Designed with <i id="smallHeart" class="fas fa-heart"></i> by
      <a href="https://david.alvarezrosa.com"
         rel="author"
         title="David Álvarez Rosa's personal website.">
         @David.
      </a>
      <i class="shine shineFooter"></i>
   </p>
</footer>
