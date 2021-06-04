{if !$ajaxlist}
<!DOCTYPE html>
{assign var="lang" value=$true|getlanguage}
{assign var="select_lang" value=$smarty.session.lang}
{assign var="default_lang" value=$true|selectlang:"config"}
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>

  <meta charset="UTF-8" />
  <meta http-equiv="pragma" content="no-cache" /> 
  <meta http-equiv="cache-control" content="no-cache" />
  
  <link rel="stylesheet" href="templates/{$design}/css/style.css" />
  <link rel="stylesheet" href="templates/{$design}/css/facebox.css" />
  <link rel="stylesheet" href="templates/{$design}/css/paginator.css" />

  <script src="templates/{$design}/js/jquery.js"></script>
  <script src="templates/{$design}/js/amxbans.js.php"></script>
  <script src="templates/{$design}/js/tooltip.js"></script>

  <script src="templates/{$design}/js/facebox.js"></script>
  <script src="templates/{$design}/js/facebox_ext.js"></script>
  <script src="include/layer.js"></script>
  <script src="include/steamprofile/steamprofile.js"></script>
  <script src="templates/{$design}/js/paginator3000.js"></script>

  <title>AMXBans: Gm {$version_web} - {$title|lang}{if $title2} - {$title2|lang}{/if}</title>  
</head>

<body id="top" {if $smarty.session.loginfailed}onLoad="javascript: countdown({$smarty.session.loginfailed});"{/if}>
<div id="network">
  <div class="center-wrapper">
    <div class="left">
      <ul class="tabbed" id="menu-tabs">
        <li>
          <form method="post" action="" style="padding:5px 8px 0 0;margin:0;">
            <select name="newlang" style="padding:0;margin:0;" onchange="this.form.submit()">
            {foreach from=$lang item="langs"}
              <option value="{$langs|escape}" {if empty($select_lang) && $default_lang == $langs}selected="selected"{/if} {if $select_lang == $langs}selected="selected"{/if}>{$langs|escape}</option>
            {/foreach}
        {$select_lang|@var_dump}
            </select>
          </form>
          
        </li>
        {foreach from=$menu item=menus}
          {if $smarty.session.loggedin == "true"}
            {if $menus.lang_key2}<li><a href="{$menus.url2}">{$menus.lang_key2|lang}</a></li>{/if}
          {else}
            {if $menus.lang_key}<li><a href="{$menus.url}">{$menus.lang_key|lang}</a></li>{/if}
          {/if}
        {/foreach}
          </ul>
    </div>
    <div class="right">
      
      <ul class="tabbed" id="admin-tabs">
        {if $smarty.session.loggedin == "true"}
          <li><a href="admin.php">{"_ADMINAREA"|lang}</a></li>
          <li><a href="logout.php">{"_LOGOUT"|lang} {$smarty.session.uname}</a></li>
        {else}
          <li>
            <form name="loginform" action="login.php" method="post">
              <script type="text/javascript">
                var languser = '{"_USERNAME"|lang}';
                var langpass = '{"_PASSWORD"|lang}';
              </script>
              <input
                type="text" 
                size="17"
                name="user" 
                value="{"_USERNAME"|lang}" 
                onfocus="javascript:if(this.value=languser)this.value='';" 
                
                />
              <input 
                type="password"
                size="12" 
                name="pass" 
                value="{"_PASSWORD"|lang}" 
                onfocus="javascript:if(this.value=langpass)this.value='';" 
                
                />
              <input type='checkbox' checked="checked" name='remember' value="yes" title='{"_REMEMBERME"|lang}' />
              <button type="submit" name="action" id="action" value="Login">{"_LOGIN"|lang}</button>
            </form>
          </li>
        {/if}
      </ul>
      
    </div>
    <div class="clearer">&nbsp;</div>
  </div>
</div>
<br />
<div id="site">
  <div class="center-wrapper">
    {if $banner <> ""}
      <div id="header">
        <div id="site-title" style="text-align: center;">
          <a href="{$banner_url}" target="_blank"><img src="images/banner/{$banner}" alt="{$banner_url}" title="{$banner_url}" /></a>
        </div>
      </div>
    {else}
      <div class="spacer">&nbsp;</div>
    {/if}
    {/if}