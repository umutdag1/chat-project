<?php
$level = 'production';


if ($level == 'production') {
    error_reporting(E_ERROR | E_PARSE);
} else if ($level == 'development') {
    error_reporting(E_ALL & ~E_USER_WARNING);
}