<?php echo "<?xml version='1.0' standalone='yes'?>"; ?>
<cas:serviceResponse xmlns:cas='http://www.yale.edu/tp/cas'>
<?php 

function recursive_print($xml, $i = 0) {
    if ($i > 100) {
        return;
    }
    foreach((array)$xml as $k => $v) {
        $k = str_replace('cas_', 'cas:', $k);
        $k = preg_replace('/_[0-9]+$/', '', $k);
        if (in_array(gettype($v), array('array', 'object'))) {
            if (is_array($v) && (array_keys($v)[0] == 0)) {
                    foreach($v as $a) {
                        echo "<$k>\n";
                        recursive_print($a, $i++);
                        echo "</$k>\n";
                    }
            }else {
                echo "<$k>\n";
                recursive_print($v, $i++);
                echo "</$k>\n";
            }
        }else{
            echo "<$k>";
            echo htmlspecialchars($v, ENT_XML1, 'UTF-8');
            echo "</$k>\n";
        }
    }
}

recursive_print($xml);

?>
</cas:serviceResponse>