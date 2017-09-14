<?php
if(!defined('IN_MYBB'))
{
    exit;
}

/**
 * Register Plugin Hooks
 */
$plugins->add_hook('showthread_end', 'handleTemplate');
$plugins->add_hook('global_start', 'loadStyle');

/**
 * Plugin Info
 */
function VisitorNotification_info()
{
    return array(
        'name'          => 'IPB Tarzi Bildirim',
        'description'   => 'IPB Tarzi Giris yap bildirimi',
        'version'       => '1.0',
        'versioncode'   => '1000',
        'author'        => 'remzikocak',
        'authorsite'    => 'https://www.mybbpro.com',
        'website'       => 'https://www.mybbpro.com?ref=visitornotification',
        'compatibility' => '18*',
    );
}

/**
 * Activate Plugin
 */
function VisitorNotification_activate()
{
    global $db;

    $template = '<div class="VisitorNotification">
        <h2 class="VisitorNotification__Title">{$lang->vn_title}</h2>
        <p class="VisitorNotification__Text">{$lang->vn_description}</p>
        <div class="VisitorNotification__Boxes">
            <div class="VisitorNotification__Box">
                <h3>{$lang->welcome_register}</h3>
                <p>{$lang->vn_register_description}</p>
                <a href="{$mybb->settings[\'bburl\']}/member.php?action=register" class="VisitorNotification__Btn">{$lang->welcome_register}</a>
            </div>
            <div class="VisitorNotification__Box">
                <h3>{$lang->welcome_login}</h3>
                <p>{$lang->vn_login_description}</p>
                <a href="{$mybb->settings[\'bburl\']}/member.php?action=login" class="VisitorNotification__Btn">{$lang->welcome_login}</a>
            </div>
        </div>
    </div>';

    $insertData = array(
        'title'     => 'visitornotification',
        'template'  => $db->escape_string($template),
        'sid'       => '-1',
        'version'   => '1000',
        'dateline'  => time()
    );

    $db->insert_query('templates', $insertData);

    $template = '<style>
    .VisitorNotification{border: 1px solid #ddd;padding: 10px;text-align:center;box-sizing: border-box;border-radius: 3px;-moz-border-radius: 3px;-webkit-border-radius: 3px;box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.05);-moz-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.05);-webkit-box-shadow: 0px 2px 2px 0px rgba(0, 0, 0, 0.05);}
    .VisitorNotification__Title{color: #717171;margin: 0;padding: 0;font-weight:normal;}
    .VisitorNotification__Text{color:#AEAEAE;}
    .VisitorNotification__Boxes{display:flex;}
    .VisitorNotification__Box{flex: 1;margin:10px;background:#F8F8F8;padding: 10px;}
    .VisitorNotification__Box > p, .VisitorNotification__Box > h3 {font-weight:normal;}
    .VisitorNotification__Box > p {color: #AEAEAE;}
    .VisitorNotification__Box > h3 {color: #717171;}
    .VisitorNotification__Btn:link, .VisitorNotification__Btn:visited {display:inline-block;background:#595959;line-height:34px;padding:0px 18px;border:none;color:#fff;border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;transition: all 200ms ease;}
    .VisitorNotification__Btn:hover, .VisitorNotification__Btn:active {text-decoration:none;background:#696969;}
</style>';

    $insertData = array(
        'title'     => 'visitornotification_styles',
        'template'  => $db->escape_string($template),
        'sid'       => '-1',
        'version'   => '1000',
        'dateline'  => time()
    );

    $db->insert_query('templates', $insertData);

    require MYBB_ROOT . 'inc/adminfunctions_templates.php';
    find_replace_templatesets("headerinclude", "#".preg_quote("{\$stylesheets}")."#i", "{\$stylesheets}{\$visitornotificationStyles}");
}

/**
 * Deactivate Plugin
 */
function VisitorNotification_deactivate()
{
    global $db;

    $db->delete_query("templates", "title = 'visitornotification'");
    $db->delete_query("templates", "title = 'visitornotification_styles'");

    require MYBB_ROOT . 'inc/adminfunctions_templates.php';
    find_replace_templatesets("headerinclude", "#".preg_quote("{\$stylesheets}{\$visitornotificationStyles}")."#i", "{\$stylesheets}");
}

/**
 * Handle Template
 *
 * @return void
 */
function handleTemplate()
{
    global $mybb, $db, $templates, $lang, $quickreply;

    if(!$mybb->user['uid'])
    {
        $lang->load('visitornotification');
        eval('$quickreply  = "' . $templates->get('visitornotification') . '";');
    }
}

/**
 * Load Style before headerinclude Template is parsed.
 *
 * @return void
 */
function loadStyle()
{
    global $templates, $visitornotificationStyles;

    if(THIS_SCRIPT == 'showthread.php')
    {
        eval('$visitornotificationStyles  = "' . $templates->get('visitornotification_styles') . '";');
    }
}
