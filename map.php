<?php
$zoom = 500;
$address = $_GET['coord']; // lat/lng is also possible but without an address card
$lng = 'de';

$src = 'https://www.google.com/maps/embed?pb='.
'!1m18'.
    '!1m12'.
        '!1m3'.
            '!1d'.$zoom.
            '!2d0'.
            '!3d0'.
        '!2m3'.
            '!1f0'.
            '!2f0'.
            '!3f0'.
        '!3m2'.
            '!1i1024'.
            '!2i768'.
        '!4f13.1'.
        '!3m3'.
            '!1m2'.
            '!1s0'.
            '!2s'.rawurlencode($address).
        '!5e0'.
        '!3m2'.
            '!1s'.$lng.
            '!2s'.$lng.
        '!4v'.time().'000'.
        '!5m2'.
            '!1s'.$lng.
            '!2s'.$lng;

echo '<iframe src="'.$src.'" width="100%" height="100%" style="border:0;" allowfullscreen="true" loading="lazy" ></iframe>';
?>
