<?php

function hashPass($plain_pass, $hash_algo = PASSWORD_DEFAULT) {
    return password_hash($plain_pass, $hash_algo);
}