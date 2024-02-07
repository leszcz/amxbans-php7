<div id="navigation">
	<div id="main-nav">
		<ul class="tabbed">
			<li><a class="main-nav_a" id="#menu_1"><h1>{"_ADMINAREA"|lang}</h1></a></li>
			<li><a class="main-nav_a" id="#menu_2"><h1>{"_SERVER"|lang}</h1></a></li>
			<li><a class="main-nav_a" id="#menu_3"><h1>{"_WEB"|lang}</h1></a></li>
			<li><a class="main-nav_a" id="#menu_4"><h1>{"_MODULES"|lang}</h1></a></li>
		</ul>
		<div class="clearer">&nbsp;</div>
	</div>

	<div id="sub-nav">
		<div id="menu_1" style="{if $menu_pos == "so_up" || $menu_pos == "so_in" || $menu_pos == "ban_add" || $menu_pos == "ban_add_online"}display: block;{else}display: none;{/if}">
			<ul class="tabbed">
				<li><a href="admin.php">{"_MENUINFO"|lang}</a></li>
				<li><a href="admin.php?site=ban_add">{"_ADDBAN"|lang}</a></li>
				<li><a href="admin.php?site=ban_add_online">{"_ADDBANONLINE"|lang}</a></li>
			</ul>
		</div>
		<div id="menu_2" style="{if $menu_pos == "sm_sv" || $menu_pos == "sm_bg" || $menu_pos == "sm_av" || $menu_pos == "sm_sa"}display: block;{else}display: none;{/if}">
			<ul class="tabbed">
				<li><a href="admin.php?site=sm_sv">{"_SETTINGS"|lang}</a></li>
				<li><a href="admin.php?site=sm_bg">{"_MENUREASONS"|lang}</a></li>
				<li><a href="admin.php?site=sm_av">{"_ADMINS"|lang}</a></li>
				<li><a href="admin.php?site=sm_sa">{"_TITLEADMIN"|lang}</a></li>
			</ul>
		</div>
		<div id="menu_3" style="{if $menu_pos == "wm_wa" || $menu_pos == "wm_ul" || $menu_pos == "wm_um" || $menu_pos == "wm_ms" || $menu_pos == "so_lg"}display: block;{else}display: none;{/if}">
			<ul class="tabbed">
				<li><a href="admin.php?site=wm_wa">{"_ADMINS"|lang}</a></li>
				<li><a href="admin.php?site=wm_ul">{"_PERM"|lang}</a></li>
				<li><a href="admin.php?site=wm_um">{"_MENUUSERMENU"|lang}</a></li>
				<li><a href="admin.php?site=wm_ms">{"_SETTINGS"|lang}</a></li>
				<li><a href="admin.php?site=so_lg">{"_MENULOGS"|lang}</a></li>
			</ul>
		</div>
		<div id="menu_4" style="{if $menu_pos == "so_mo" || $menu_pos == "iexport" || $menu_pos == "usersi" }display: block;{else}display: none;{/if}">
			<ul class="tabbed">
				<li><a href="admin.php?site=so_mo">{"_MODULES"|lang}</a></li>
				<li><a href="admin.php?modul=iexport">{"_MENUIMPORTEXPORT"|lang}</a></li>
				<li><a href="admin.php?modul=usersi">{"_MENUIMPORTADMINS"|lang}</a></li>
			</ul>
		</div>
		<div class="clearer">&nbsp;</div>
	</div>
</div>