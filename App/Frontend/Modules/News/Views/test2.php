<?php
if ($msg == true) {
    $mesg=1;
    $comments=array_merge(array("msg" => $mesg),$comments);
    print json_encode($comments);
}
else
{
    $mesg= array ("msg" =>0, "raison" => $raison);
    print json_encode($mesg);
}