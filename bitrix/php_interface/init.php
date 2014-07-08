<?php
require __DIR__ . '/include/events.php';

/**
 * Вывод перенменной
 * @global CUser $USER
 *
 * @param        $mas
 * @param bool   $prent
 * @param bool   $show
 */
function prent($mas, $prent = true, $show = false)
    {
    global $USER;
    if ($USER->IsAdmin() || $show)
        {
        echo "<pre style=\"text-align:left; background-color:#CCC;color:#000; font-size:10px; padding-bottom: 10px; border-bottom:1px solid #000;\">";
        if ($prent)
            {
            print_r($mas);
            }
        else
            {
            var_dump($mas);
            }
        echo "</pre>";
        }
    }