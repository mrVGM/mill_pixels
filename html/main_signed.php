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
        
        <script src="/js/geometry.js"></script>
        <script>
            var zones = [];
            for (var i in rectangles) {
                zones.push(new Rectangle(new Point(rectangles[i].x, rectangles[i].y), new Point(rectangles[i].x + rectangles[i].w, rectangles[i].y + rectangles[i].h)));
            }
            
            var startPoint = new Point(0,0);
            var endPoint = new Point(0, 0);
            var selecting = false;
            var selectedArea = new Rectangle(startPoint, endPoint);
            
            function buy() {
                $.ajax({
                    url: '/pixels',
                    method: 'POST',
                    data: 'x=' + selectedArea.lu.x + '&y=' + selectedArea.lu.y + '&w=' + selectedArea.width + '&h=' + selectedArea.height,
                    success: function(data) {
                        window.location.href = "/pixels/" + data;
                    },
                    error: function() {
                        alert('Error');
                    }
                });
            }
            
            
            $("#foreground").mousedown(function(event) {
                if (!selectedArea.isIn(new Point(event.originalEvent.layerX, event.originalEvent.layerY))) {
                    $("#buybutton").remove();
                    startPoint = new Point(event.originalEvent.layerX, event.originalEvent.layerY);
                    endPoint = new Point(startPoint.x, startPoint.y);
                    selecting = true;
                }
            });
            
            var context = document.getElementById("foreground").getContext("2d");
            context.fillStyle = "rgba(0,255,0,0.5)";
                    
            $("#foreground").mousemove(function(event) {
                if (selecting) {
                    endPoint = new Point(event.originalEvent.layerX, event.originalEvent.layerY);
                    selectedArea = new Rectangle(startPoint, endPoint);
                    
                    
                    context.clearRect(0,0,1000,1000);
                    context.fillRect(selectedArea.lu.x, selectedArea.lu.y, selectedArea.width, selectedArea.height);
                    
                }
            });
            $("#foreground").mouseup(function() {
                if (selecting) {
                    selecting = false;
                    selectedArea = selectedArea.bestCrop(zones);
                    
                    context.clearRect(0,0,1000,1000);
                    context.fillRect(selectedArea.lu.x, selectedArea.lu.y, selectedArea.width, selectedArea.height);
                    if (selectedArea.area > 0) {
                        console.log("defr");
                        
                        $('<li id="buybutton" onclick="buy()"><a href="#">Buy selected</a></li>').appendTo("#navlist");
                    }
                }
            });
            
            
            
        </script>
        
    </body>
</html>