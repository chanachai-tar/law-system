<?php
$content = file_get_contents("resources/views/auth/login.blade.php");
// Hide the green button container by adding "hidden" class or commenting it out.
$content = str_replace(
    "<button type=\"button\" onclick=\"toggleAuthForm(true)\"",
    "<button type=\"button\" onclick=\"toggleAuthForm(true)\" style=\"display:none;\"",
    $content
);
file_put_contents("resources/views/auth/login.blade.php", $content);
echo "Fixed";

