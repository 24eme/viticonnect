<div class="mb-3">
    <h1 class="mt-5 ">Je m'identifie sur <em><?php echo $servicename; ?></em></h1>
    <h2>grace à mes identifiants :</h2>
</div>

<form class="form-viticonnect" method="POST">
  <input type="hidden" name="service" value="<?php echo $service; ?>">
  <div class="mb-1">
  <?php foreach ($cases as $key => $service) : if (($limits && !isset($limits[$key])) || (!isset($service['service_humanname'])) || (!$limits && isset($service['hidden']) && $service['hidden']) ) continue ; ?>
      <button type="submit" class="btn btn-lg btn-light btn-outline-dark mb-2" name="cas_choice" value="<?php echo $key; ?>"><?php echo $service['service_humanname']; ?></button>
  <?php endforeach; ?>
  </div>
  <div class="checkbox mb-5">
    <label>
      <input type="checkbox" value="remember-me" disabled=disabled> Se souvenir de mon choix
    </label>
  </div>
</form>