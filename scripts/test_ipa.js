<div id='p'></div>
<script>
var url = decodeURIComponent(location.href);
var div = document.getElementById('q');
div.innerHTML = '<a href = http://localhost/myapp/XSS_demo_mine/index.php/hoge?url='
                + url
                +'"target="_blank">konokiji</a>';
</script>