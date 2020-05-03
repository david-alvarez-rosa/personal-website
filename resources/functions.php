<?php
$sectionCounter = 0;

function sectionHeader() {
    global $sections, $sectionCounter;
    $section = $sections[$sectionCounter];
    $shortName = $section['name'];
    if (isset($section['shortName']))
        $shortName = $section['shortName'];
    $href = '#sec:' . strtolower(str_replace(' ', '-', $shortName));
    $title = 'Go to ' . strtolower($section["name"]) . ' section.';
    echo '<h2>' . $section["name"];
    echo '<a href="' . $href . '"';
    echo 'title="' . $title . '">';
    echo '<span class="screenReader">' . $title . '</span>';
    echo '<i aria-hidden="true" class="linkIcon fas fa-link"></i>';
    echo '</a>';
    echo '<i class="rightIcon ' . $section['icon'] . '"';
    if (isset($section['iconStyle']))
        echo ' style="' . $section['iconStyle'] . '"';
    echo '></i>';
    echo '</h2>';

    ++$sectionCounter;
}
?>
