<?php
$mypass = 'Hello Pass';
$hashed = password_hash($mypass, PASSWORD_DEFAULT);
echo $hashed . "\n";
if (password_verify($mypass, $hashed)) {
    echo "Success";
} else {
    echo "Failed";
}
?>