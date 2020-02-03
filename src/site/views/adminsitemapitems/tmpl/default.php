<?php
/**
 * @package   OSMap
 * @copyright 2007-2014 XMap - Joomla! Vargas - Guillermo Vargas. All rights reserved.
 * @copyright 2016-2020 Joomlashack.com. All rights reserved.
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @license   http://www.gnu.org/licenses/gpl.html GNU/GPL
 */

use Alledia\OSMap;

defined('_JEXEC') or die();

OSMap\Factory::getApplication()->input->set('tmpl', 'component');

JHtml::_('stylesheet', 'com_osmap/admin.min.css', array('relative' => true));

if (!empty($this->message)) : ?>
    <div class="alert alert-warning">
        <?php echo $this->message; ?>
    </div>
<?php endif;

if (empty($this->message)) {
    echo $this->loadTemplate('items');
}

jexit();
