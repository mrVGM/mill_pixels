<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">Hello, <?php
      require_once('../authorize.php');
      echo authenticate();
      ?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav" id="navlist">
        <li><a href="/pixels">My pixels</a></li>     
      </ul>
      
      <ul class="nav navbar-nav navbar-right">
        <li id="logout"><a href="#">Log out</a></li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

<script>
    $("#logout").on("click", function() {
        $.ajax({
            url: "/login",
            method: "DELETE",
            success: function() {
                window.location.href = '/';
            }
        });
    });
</script>