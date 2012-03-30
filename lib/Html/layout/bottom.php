</div></div><?php

if(DEBUG) echo view('admin/dev/debug');

echo view('layout/footer');

if(!DEV && STATS)
{
    echo "<script>var _gaq=[['_setAccount','".STATS."'],['_trackPageview']];(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];g.async=1;g.src='//www.google-analytics.com/ga.js';s.parentNode.insertBefore(g,s)}(document,'script'))</script>";
}
