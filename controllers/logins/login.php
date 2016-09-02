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
    require('../html/navbar_unsigned.php');
    ?>
    
    <div class="container">
    <h3>Log in here:</h3><br/>
    <form action="/login" method="POST">
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-10">
          <input type="text" class="form-control" id="inputEmail3" name="username" placeholder="Username">
        </div>
      </div>
      <div class="form-group row">
        <label for="inputPassword3" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-10">
          <input type="password" class="form-control" id="inputPassword3" name="password" placeholder="Password">
        </div>
      </div>
      <div class="form-group row">
        <div class="offset-sm-2 col-sm-10">
          <button type="submit" class="btn btn-primary">Log in</button>
        </div>
      </div>
    </form>
    </div>
</body>
</html>
