<?php
/**
 *
 * David \'Alvarez Rosa's personal website body pre-main PHP file.
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


<!-- Loading spinner. -->
<div id="loadingSpinner"></div>


<!-- Information divs. -->
<?php include 'information.html'; ?>


<!-- Heart. -->
<div id="heart"><i class="fas fa-heart"></i></div>


<?php if ($currentSite === 'blog') { ?>
   <!-- Zoomed images.  -->
   <div id="zoomImgDiv">
      <a href="javascript:closeZoomImg();" title="Close zoomed figure.">
         <i class="fas fa-times fa-2x"></i>
      </a>
      <figure>
        <a id="zoomImgLink" title="Download image.">
          <img id="zoomImg" /><figcaption id="zoomImgCaption"></figcaption>
        </a>
      </figure>
   </div>


   <!-- Enlarged call out. -->
   <div id="enlargedCallOutDiv">
      <div id="enlargedCallOutContainer">
         <div id="enlargedCallOut">
            <a href="javascript:closeCallOut();" title="Close enlarged information.">
               <i class="fas fa-times-circle"></i>
            </a>
            <div id="enlargedCallOutInfo"></div>
         </div>
      </div>
   </div>
<?php } ?>



<!-- Header. -->
<?php include 'header.php'; ?>


<!-- Navigation bar. -->
<?php include 'navBar.php'; ?>


<!-- Navigation bar expanded. -->
<?php include 'navBarExpanded.php'; ?>


<!-- Scroll back to top button. -->
<div id="scrollTop"
     onmouseover="document.getElementById('scrollTopSpan').style.display = 'inline-block';"
     onmouseout="document.getElementById('scrollTopSpan').style.display = 'none';">
   <a href="#" title="Scroll back to top.">
      <span id="scrollTopSpan">Go to top</span>
      <i class="fas fa-arrow-circle-up fa-2x"></i>
   </a>
</div>
