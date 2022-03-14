<html>
<head>
<title><?php echo (isset($title) && $title) ? $title : "Viticonnect" ; ?></title>
<link href="css/bootstrap.min.css?v=5.1.3" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<h1>Viticonnect</h1>

<div id="pagecontent">
<?php include($template); ?>
</div>

</body>
<script src="/js/bootstrap.bundle.min.js?v=5.1.3" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
</html>
