<?php

if(!function_exists("endsWith")) {
    function endsWith($in, $search) {
        return substr($in, -strlen($search)) === $search;
    }

    function startsWith($in, $search) {
        return substr($in, 0, strlen($search)) === $search;
    }
}
