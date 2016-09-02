<!DOCTYPE html>
<html>
    <head>
        <script src="/js/jquery.js" ></script>
        <link rel="stylesheet" href="/css/bootstrap.css">
        <script src="/js/bootstrap.min.js" ></script>
    </head>
    <body>
        <?php require('../html/navbar_unsigned.php'); ?>
        
        <div class="container" style="position: relative;">
            <canvas width="1000" height="1000" id="background" style="position: absolute; margin-left: 0px; margin-right: 0px;  border-style: solid;"></canvas>
            <canvas width="1000" height="1000" id="foreground" style="position: absolute; margin-left: 0px; margin-right: 0px;"></canvas>
        </div>
        
        <script>
            var rectangles = <?php require('../controllers/pixels/pixels_array.php'); ?>;
            var rectangles_duplicate = <?php require('../controllers/pixels/pixels_array.php'); ?>;
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
        
    </body>
</html>