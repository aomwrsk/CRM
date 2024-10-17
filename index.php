<?php 
function Redirect($url, $permanent = false)
{
    header('Location: ' . $url, true, $permanent ? 301 : 302);

    exit();
}

Redirect('https://localhost:8081/CRM/pages-login.html', false);
?>