<?php

// Set the timezone
define('TIMEZONE', 'Europe/Moscow');
date_default_timezone_set(TIMEZONE);

function last_seen($date_time) {
    $timestamp = strtotime($date_time);
    $currentTime = time();
    $diff = $currentTime - $timestamp;

    // Less than a minute ago - the user is online
    if ($diff < 60) {
        return 'Online';
    }

    // Time intervals in seconds, each with a singular and a plural label
    $intervals = array(
        31536000 => array('year', 'years'),
        2592000  => array('month', 'months'),
        604800   => array('week', 'weeks'),
        86400    => array('day', 'days'),
        3600     => array('hour', 'hours'),
        60       => array('minute', 'minutes')
    );

    // Pick the closest interval
    foreach ($intervals as $seconds => $labels) {
        $div = $diff / $seconds;

        if ($div >= 1) {
            $rounded = floor($div);

            // English only needs singular / plural
            $label = ($rounded == 1) ? $labels[0] : $labels[1];

            return $rounded . ' ' . $label . ' ago';
        }
    }

    return 'Just now';
}
