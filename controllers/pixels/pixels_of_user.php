<!DOCTYPE html>
<html>
    <head>
        <script src="/js/jquery.js" ></script>
        <link rel="stylesheet" href="/css/bootstrap.css">
        <script src="/js/bootstrap.min.js" ></script>
    </head>
    <body>
        <?php require('../html/navbar_signed.php'); ?>
        
        <div class="container" style="position: relative;">
            <canvas width="1000" height="1000" id="background" style="position: absolute; margin-left: 0px; margin-right: 0px;  border-style: solid;"></canvas>
            <canvas width="1000" height="1000" id="foreground" style="position: absolute; margin-left: 0px; margin-right: 0px;"></canvas>
        </div>
        
        <script>
            var rectangles = [<?php
            
            require_once('../authorize.php');
            require_once('../db/repository.php');
            $username = authenticate();
            $pixels = get_pixels_of_user($username);

            $resp = "";
            foreach ($pixels as $zone) {
                $resp = $resp . '{x:' . $zone['x'] . ', y:' . $zone['y'] . ', w:' . $zone['w'] . ', h:' . $zone['h'] . ', id:' . $zone['id'];
                if ($zone['picture']) {
                    $resp = $resp . ',picture:' . $zone['picture'];
                }
                $resp = $resp . '},';
            }
            
            $resp = substr($resp, 0, -1);
            
            echo $resp;
            
            ?>];
            var rectangles_duplicate = [<?php
            
            require_once('../authorize.php');
            require_once('../db/repository.php');
            $username = authenticate();
            $pixels = get_pixels_of_user($username);

            $resp = "";
            foreach ($pixels as $zone) {
                $resp = $resp . '{x:' . $zone['x'] . ', y:' . $zone['y'] . ', w:' . $zone['w'] . ', h:' . $zone['h'] . ', id:' . $zone['id'];
                if ($zone['picture']) {
                    $resp = $resp . ',picture:' . $zone['picture'];
                }
                $resp = $resp . '},';
            }
            
            $resp = substr($resp, 0, -1);
            
            echo $resp;
            
            ?>];
        </script>
        
        <script>
            
            var ctx = document.getElementById("background").getContext("2d");

            ctx.fillStyle = "#FF0000";

            function f() {
                if (rectangles_duplicate.length > 0) {
                    var cur = rectangles_duplicate.shift();
                    
                    if (cur.picture) {
                        var pic = new Image();
                        $(pic).attr('src', "/pictures/" + cur.picture);
                        $(pic).on('load', function() {
                            if (!this.complete || typeof this.naturalWidth == "undefined" || this.naturalWidth == 0) {
                                //alert('broken image!');
                            } else {
                                console.log("here");
                                ctx.drawImage(pic, cur.x, cur.y, cur.w, cur.h);
                            }
                        });                     
                    }
                    else {
                        ctx.fillRect(cur.x,cur.y,cur.w,cur.h);
                    }
                    
                    setTimeout(f, 0);
                }
            }
            setTimeout(f, 0);
        </script>
        
        <script src="/js/geometry.js"></script>
        
        <script>
            $("#foreground").click(function(event) {
                var p = new Point(event.originalEvent.layerX, event.originalEvent.layerY);
                for (var i in rectangles) {
                    var tmp = new Rectangle(new Point(rectangles[i].x, rectangles[i].y), new Point(rectangles[i].x + rectangles[i].w, rectangles[i].y + rectangles[i].h));
                    if (tmp.isIn(p)) {
                        window.location.href = '/pixels/' + rectangles[i].id;
                    }
                }
            });
        </script>
        
    </body>
</html>