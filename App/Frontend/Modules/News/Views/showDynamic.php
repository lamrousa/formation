<?php
if ($nb >0) {
    $comments = array_merge(array("nb" => $nb), $comments);
    print json_encode($comments);
}
else {
    $comments = array("nb" => $nb);
    print json_encode($comments);
}