<?php
function anchorUndo($id, $type) {?>
  <div class="anchor anchor<?php echo $type; ?>" id="<?php echo $id ?>">
    <i class="bouncingHand fas fa-hand-point-right"></i>
    <a onclick="javascript:takeMeBack();"
       class="undoAnchor"
       title="Take me back where I was.">
      <i class="fas fa-fast-backward"></i>
    </a>
  </div>
<?php
}


$sectionCounter = 0;

function sectionHeader() {
    global $sections, $sectionCounter;
    $section = $sections[$sectionCounter];
    $shortName = $section['name'];
    if (isset($section['shortName']))
        $shortName = $section['shortName'];
    $id = 'sec:' . strtolower(str_replace(' ', '-', $shortName));
    $href = '#' . $id;
    $title = 'Go to ' . strtolower($section["name"]) . ' section.';

    anchorUndo($id, "Section");
?>

  <h2> <?php echo $section['name']; ?>
    <a href="<?php echo $href ?>" title="<?php echo $title; ?>">
      <i aria-hidden="true" class="linkIcon fas fa-link">
        <span class="screenReader"><?php echo $title; ?></span>
      </i>
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

  <?php
  $subsectionCounter = 0;

  function subsectionHeader() {
      global $subsections, $subsectionCounter;
      $subsection = $subsections[$subsectionCounter];
      $shortName = $subsection['name'];
      if (isset($subsection['shortName']))
          $shortName = $subsection['shortName'];
      $id = 'subsec:' . strtolower(str_replace(' ', '-', $shortName));
      $href = '#' . $id;
      $title = 'Go to ' . strtolower($subsection["name"]) . ' subsection.';

      anchorUndo($id, "Subsection");
  ?>
    <h3> <?php echo $subsection['name']; ?>
      <a href="<?php echo $href ?>" title="<?php echo $title; ?>">
        <i aria-hidden="true" class="linkIcon fas fa-hashtag">
          <span class="screenReader"><?php echo $title; ?></span>
        </i>
      </a>
    </h3>
    <?php
    ++$subsectionCounter;
    }
    ?>
