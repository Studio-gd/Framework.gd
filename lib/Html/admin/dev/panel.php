
<div class="adminPanel">

<ul class="nav nav-tabs nav-stacked">
    
    <li><a href="/admin/dev/translate"><i class="icon-flag"></i> Translate</a></li>
    
    <li><a onclick="admin.cleanCache()"><i class="icon-trash"></i> Clean all cache</a></li>
    <li><a href="/admin/dev/logs"><i class="icon-list"></i> check Log</a></li>
    <!--<li><a href="/admin/dev/cacheStats">Cache Stats</a></li>-->
    
    <li><a href="/admin/dev/stats"><i class="icon-signal"></i> <?php echo __('Statistics'); ?></a></li>

    <li><a onclick="admin.pack()"><i class="icon-gift"></i> Pack js & css</a></li>
    <li><a onclick="admin.togglePack()" class="tt" title="click to toggle"><i class="icon-retweet"></i> Javascript Packed version: <b id="packed"></b></a></li>
    
    <li><a href="/admin/dev/scaffold"><i class="icon-th-list"></i> Scaffold</a></li>
    <li><a href="/admin/dev/simplepage"><i class="icon-file"></i> Create a simple page</a></li>
    
    <li><input type="text" /><a onclick="admin.createWidget($(this))">Create a view</a></li>
    <li><input type="text" /><a onclick="admin.createController($(this))">Create a controller</a></li>

</ul>

</div>