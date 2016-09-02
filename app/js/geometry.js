function Point(x, y) {
    this.x = x;
    this.y = y;
}

function Rectangle(p1, p2) {
    var l = Math.min(p1.x, p2.x);
    var r = Math.max(p1.x, p2.x);
    var u = Math.min(p1.y, p2.y);
    var d = Math.max(p1.y, p2.y);
    
    this.lu = new Point(l, u);
    this.ru = new Point(r, u);
    this.ld = new Point(l, d);
    this.rd = new Point(r, d);
    
    this.width = r-l;
    this.height = d-u;
    
    this.area = this.width * this.height;
    
    this.intersection = function(other) {
        function sort(l) {
            for (var i = 0; i < l.length - 1; i++) {
                for (var j = i+1; j < l.length; j++) {
                    if (l[i].val > l[j].val) {
                        var tmp = l[i];
                        l[i] = l[j];
                        l[j] = tmp;
                    }
                }
            }
        }
        var horizontal = [{val: this.lu.x, arg: 1}, {val: this.ru.x, arg: -1}, {val: other.lu.x, arg: 1}, {val: other.ru.x, arg: -1}];
        sort(horizontal);
        
        var vertical = [{val: this.lu.y, arg: 1}, {val: this.ld.y, arg: -1}, {val: other.lu.y, arg: 1}, {val: other.ld.y, arg: -1}];
        sort(vertical);
        
        
        var l, r, u, d;
        var stat = 0;
        
        for (var i in horizontal) {
            
            if (stat == 1) {
                stat = stat + horizontal[i].arg;
                if (stat == 2) {
                    l = horizontal[i].val;
                }
                continue;
            }
            if (stat == 2) {
                stat = stat + horizontal[i].arg;
                if (stat == 1) {
                    r = horizontal[i].val;
                }
                continue;
            }
            stat = stat + horizontal[i].arg;
        }
        
        
        
        if (!l) {
            return new Rectangle(new Point(0,0), new Point(0,0));
        }
        
        
        stat = 0;
        for (var i in vertical) {
            if (stat == 1) {
                stat += vertical[i].arg;
                if (stat == 2) {
                    u = vertical[i].val;
                }
                continue;
            }
            if (stat == 2) {
                stat += vertical[i].arg;
                if (stat == 1) {
                    d = vertical[i].val;
                }
                continue;
            }
            stat += vertical[i].arg;
        }
        
        if (!u) {
            return new Rectangle(new Point(0,0), new Point(0,0));
        }
        return new Rectangle(new Point(l, u), new Point(r, d));
    };
    
    this.bestCompletion = function(big) {
        if (this.intersection(big).area == 0) {
            return big;
        }
        var tmp = [];
        tmp.push(new Rectangle(big.lu, new Point(big.ru.x, this.ru.y)));
        tmp.push(new Rectangle(big.ld, new Point(big.ru.x, this.rd.y)));
        tmp.push(new Rectangle(big.lu, new Point(this.lu.x, big.ld.y)));
        tmp.push(new Rectangle(big.ru, new Point(this.ru.x, big.ld.y)));
        
        var best = tmp[0];
        for (var i = 1; i < 4; i++) {
            if (best.area < tmp[i].area) {
                best = tmp[i];
            }
        }
        return best;
    };
    
    this.bestCrop = function(l) {
        var best = this;
        for (var i in l) {
            var tmp = this.intersection(l[i]);
            best = tmp.bestCompletion(best);
        }
        return best;
    };
    this.isIn = function(p) {
        if (p.x < this.lu.x || p.x > this.ru.x) {
            return false;
        }
        if (p.y < this.lu.y || p.y > this.ld.y) {
            return false;
        }
        return true;
    };
}
















