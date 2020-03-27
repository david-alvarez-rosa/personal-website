<!-- Loading spinner. -->
<div id="loadingSpinner"></div>


<!-- Information divs. -->
<?php include 'resources/information.html'; ?>


<!-- Heart. -->
<div id="heart"><i class="fas fa-heart"></i></div>


<?php
if ($currentSite === 'blog') {
    echo '
    <!-- Zoomed images.  -->
    <div id="zoomImgDiv">
    <a href="javascript:closeZoomImg();" title="Close zoomed figure.">
    <i class="fas fa-times fa-2x"></i>
    </a>
    <figure>
    <img id="zoomImg" /><figcaption id="zoomImgCaption"></figcaption>
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
    </div>';
    }
?>


<!-- Header. -->
<?php include 'resources/header.php'; ?>


<!-- Navigation bar. -->
<?php include 'resources/navBar.php'; ?>


<!-- Navigation bar expanded. -->
<?php include 'resources/navBarExpanded.php'; ?>


<!-- Scroll back to top button. -->
<div id="scrollTop"
     onmouseover="document.getElementById('scrollTopSpan').style.display = 'inline-block';"
     onmouseout="document.getElementById('scrollTopSpan').style.display = 'none';">
   <a href="#" title="Scroll back to top.">
      <span id="scrollTopSpan">Go to top</span>
      <i class="fas fa-arrow-circle-up fa-2x"></i>
   </a>
</div>
