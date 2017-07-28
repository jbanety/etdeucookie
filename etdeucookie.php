<?php
/**
 * @package      ETD EU Cookie
 *
 * @version      1.0
 * @copyright    Copyright (C) 2015 ETD Solutions. Tous droits réservés.
 * @license      Apache Version 2 (https://raw.githubusercontent.com/jbanety/etdeucookie/master/LICENSE.md)
 * @author       ETD Solutions http://etd-solutions.com
 **/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class  plgSystemEtdeucookie extends JPlugin {

    function onBeforeRender() {

        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();

        // Seulement sur le site.
        if ($app->isSite()) {

            // On récupère le cookie.
            $cookie = $app->input->cookie->get('etdeucookie');

            // On ne travaille que si le cookie n'est pas accepté.
            if ($cookie !== "ok") {

                // On récupère les paramètres.
                $bg          = $this->params->get('bg', '#000000');
                $color       = $this->params->get('color', '#FFFFFF');
                $message     = $this->params->get('message', '');
                //$article_id  = $this->params->get('article_id', '');

                $html = array();

                $doc->addStyleDeclaration('
#etd-cookie{font:normal 12px/16px Arial,Verdana,sans-serif;position:fixed;z-index:99999;top:0;right:0;margin:0 auto;color:' . $color . ';background:' . $bg. ';padding:5px}
#etd-cookie-t{float:left;padding:5px;width:85%}
#etd-cookie-b{float:left;padding:5px;width:15%}
@media(max-width:767px){#etd-cookie-t,#etd-cookie-b{float:none;width:100%;text-align:center}}');

                $html[] = '<div id="etd-cookie">';
                $html[] = '  <div id="etd-cookie-t">' . addslashes($message) . '</div>';
                $html[] = '  <div id="etd-cookie-b"><button type="button" class="btn btn-default btn-sm">Poursuivre</button>';
                $html[] = '</div>';

                JHtml::_('jquery.framework');
                $doc->addScriptDeclaration("
jQuery(document).on('ready', (function($) {
    $('body').append('" . implode($html) . "');
    $('#etd-cookie-b button').on('click', function(e) {
        e.preventDefault();
        var t = new Date();
        t.setMilliseconds(t.getMilliseconds() + 30 * 864e+5)
        document.cookie = 'etdeucookie=ok; expires=' + t.toUTCString();
        $('#etd-cookie').remove();
    });
})(jQuery));");

            }

        }
    }
}
