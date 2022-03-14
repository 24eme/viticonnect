<?php echo "<?xml version='1.0' standalone='yes'?>"; ?>
<cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
<?php 

function recursive_print($xml, $i = 0) {
    if ($i > 100) {
        return;
    }
    foreach((array)$xml as $k => $v) {
        $k = str_replace('cas_', 'cas:', $k);
        echo "<$k>";
        if (in_array(gettype($v), array('array', 'object'))) {
            recursive_print($v, $i++);
        }else{
            echo $v;
        }
        echo "</$k>\n";
    }
}

recursive_print($xml);

?>
</cas:serviceResponse>