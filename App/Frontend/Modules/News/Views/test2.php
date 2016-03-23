<?php
if ($msg == true) {
    $mesg=1;
    $a='';
    foreach ($comments as $Comment) :
        $code=file_get_contents(__DIR__.'/show/comment.php');
        ob_start();
        print eval('?>'. $code);
        $output = ob_get_contents();
        ob_end_clean();

$a.=$output;
    endforeach;

    $result=array("msg" => $mesg,"valeur"=>$a);
    print json_encode($result);
}
else
{
    $mesg= array ("msg" =>0, "raison" => $raison);
    print json_encode($mesg);
}