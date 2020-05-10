<?php
$sectionCounter = 0;

function sectionHeader() {
    global $sections, $sectionCounter;
    $section = $sections[$sectionCounter];
    $shortName = $section['name'];
    if (isset($section['shortName']))
        $shortName = $section['shortName'];
    $href = '#sec:' . strtolower(str_replace(' ', '-', $shortName));
    $title = 'Go to ' . strtolower($section["name"]) . ' section.'; ?>

   <h2> <?php echo $section['name']; ?>
      <a href="<?php echo $href ?>" title="<?php echo $title; ?>"
         <span class="screenReader"><?php echo $title; ?></span>
         <i aria-hidden="true" class="linkIcon fas fa-link"></i>
      </a>
      <i class="rightIcon <?php echo $section['icon'] ?>"
         <?php
         if (isset($section['iconStyle']))
             echo 'style="' . $section['iconStyle'] .'"';
         ?>
      ></i>
   </h2>
   <?php
   ++$sectionCounter;
   }
   ?>
