<?php

function xssClean($_content) {
    $content = htmlspecialchars(strip_tags($_content), ENT_QUOTES, 'UTF-8');
    return $content;
}
