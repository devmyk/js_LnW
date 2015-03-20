<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div data-role="panel" id="leftpanel" data-position="left" data-display="overlay" class="navmenu-panel">
	<!-- left panel menu -->
	<ul data-role="listview" style="margin-bottom:-0.5em;">
		<li><a href="/c_dictation" rel="external" class="ui-btn ui-btn-icon-left ui-icon-home">Home</a></li>
		<li><a href="#" rel="external" class="ui-btn ui-btn-icon-left ui-icon-gear">Setting</a></li>
		<li><a href="/c_dictation/logout" rel="external" class="ui-btn ui-btn-icon-left ui-icon-power">Logout</a></li>

        <? // admin ####################### ?>
        <?
//        if ($is_admin) {
//            <li><a href="#" rel="external" class="ui-btn ui-btn-icon-left ui-icon-edit">Edit</a></li>
//        } 
        ?>
        
        <? // list ####################### ?>
        <?
        foreach ($category as $c) {
            if (sizeof($c['list']) > 0) {
        ?>
            <li data-role="list-divider"><?= $c['name'] ?></li>
            <? foreach ($c['list'] as $cl) { ?>
            <li>
                <div id="collapsivleset_<?=$cl['code']?>" data-role="collapsibleset" data-inset="false">
                    <div data-role="collapsible">
                        <h2><?=$cl['name']?></h2>
                        <ul data-role="listview">
                            <? foreach($cl['list'] as $cll) {?>
                            <li><a href="/c_dictation/stat/<?=$cll['code']?>" rel="external" class="ui-btn ui-btn-icon-right ui-icon-carat-r ui-mini ui-nodisc-icon"><?=$cll['name']?></a></li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
            </li>
        <?
                } // foreach list
            } // if
        } // foreach category
        ?>
 	</ul>
</div><!-- left panel menu -->
