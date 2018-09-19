<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  System.Etdeucookie
 *
 * @version     1.1.2
 * @copyright   Copyright (C) 2015 - 2018 ETD Solutions. Tous droits réservés.
 * @license     Apache Version 2 (https://raw.githubusercontent.com/jbanety/etdeucookie/master/LICENSE)
 * @author      ETD Solutions http://etd-solutions.com
 **/

// No direct access.
defined('_JEXEC') or die('Restricted access');

jimport('joomla.plugin.plugin');

class  plgSystemEtdeucookie extends JPlugin {

    function onBeforeRender() {

        $app = JFactory::getApplication();
        $doc = JFactory::getDocument();

        // Only on the site.
        if ($app->isSite()) {

            // Retrieve the cookie.
            $cookie = $app->input->cookie->get('etdeucookie');

            // If the cookie has not been accepted yet.
            if ($cookie !== "ok") {

                // Retrieve the parameters.
                $bgcolor   = $this->params->get('bgcolor', '#000000');
                $textcolor = $this->params->get('textcolor', '#FFFFFF');
                $message   = $this->params->get('message', '');
                $position  = $this->params->get('position', 'top');

                $html = array();

                $doc->addStyleDeclaration('
#etd-cookie{position:fixed;z-index:99999;' . $position . ':0;right:0; margin:0 auto;padding: 5px; color:' . $textcolor . ';background:' . $bgcolor. '; }
#etd-cookie-t{float:left;padding:5px; padding-top : 15px; width:85%}
#etd-cookie-b{float:left;padding:5px;width:15%}
@media(max-width:767px){#etd-cookie-t,#etd-cookie-b{float:none;width:100%;text-align:center}}
#etd-cookie{left:Opx; width: 100%}
');

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
