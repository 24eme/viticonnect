<ul>
<?php foreach ($cases as $key => $service) : ?>
    <li><a href="<?php echo str_replace('%service%', str_replace('%servicename%', $key, $callback), $service['cas_service']); ?>"><?php echo $service['service_humanname']; ?></a></li>
<?php endforeach; ?>
</ul>