<?php

preg_match('/\A\/pixels(\/\d+){0,1}\/{0,1}\z/', $_SERVER['REQUEST_URI'], $zone);
$id = substr($zone[1], 1);

require_once('../authorize.php');
require_once('../db/repository.php');

$p = get_specific_pixels(authenticate(), $id);

?>

<!doctype html>

<html lang="en">
<head>
    <title><!-- Insert your title here --></title>
    <script src="/js/jquery.js" ></script>
    <link rel="stylesheet" href="/css/bootstrap.css">
    <script src="/js/bootstrap.min.js" ></script>
</head>
<body>
    
    <?php
    require('../html/navbar_signed.php');
    ?>
    
    <div class="container">
        <img src="/pictures/<?php echo $p['picture'] ?>"/>
    </div>
    
    <div class="container">
    <h3>Update picture</h3><br/>
    <form action="/pixels/<?php echo $id ?>" method="POST" enctype="multipart/form-data">
      <div class="form-group row">
        <label for="pic" class="col-sm-2 col-form-label">Choose file</label>
        <div class="col-sm-10">
          <input type="file" class="form-control" id="pic" name="picture">
        </div>
      </div>
      <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
          <input type="submit" class="btn btn-primary" value="Upload">
        </div>
      </div>
    </form>
    </div>
</body>
</html>