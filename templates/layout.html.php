<?php
 
    $title = (isset($title) && $title) ? $title : "Viticonnect" 
 
?><!doctype html>
<html lang="fr">
<head>
<!-- Required meta tags -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<!-- Bootstrap CSS -->
<link href="/css/bootstrap.min.css?v=5.1.3" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
<!-- title -->
<title><?php echo $title; ?></title>
</head>
<body class="text-center">

<header class="mb-4">
<h1 class="h3 mb-3 fw-normal"><?php echo $title; ?></h1>
</header>

<main class="container">
<?php include($template); ?>
</main>

<script src="/js/bootstrap.bundle.min.js?v=5.1.3" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</body>
</html>
