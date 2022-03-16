<?php
 
    $title = (isset($title) && $title) ? $title : "Viticonnect" 
 
?><!doctype html>
<html lang="fr">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap CSS -->
<link href="/css/bootstrap.min.css?v=5.1.3" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous"/>
<link href="/css/viticonnect.css" rel="stylesheet"/>

<!-- title -->
<title><?php echo $title; ?></title>
</head>
<body class="text-center">

<main class="container-sm">
  <header class="mb-5">
    <img class="" src="https://24eme.fr/css/24emefonts/svg/raisins.svg" alt="" width="52" height="52">
    <h3><?php echo $title; ?></h3>
  </header>
    
<?php include($template); ?>

    <footer class="mt-10">
        <p class="text-muted">Viticonnect : Une initiative d'InterRhône, du Syndicat des Côtes du Rhône et du 24eme</p>
    </footer>
</main>

<script src="/js/bootstrap.bundle.min.js?v=5.1.3" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
