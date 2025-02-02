<?php
/**
 * @package   OSMap
 * @contact   www.joomlashack.com, help@joomlashack.com
 * @copyright 2007-2014 XMap - Joomla! Vargas - Guillermo Vargas. All rights reserved.
 * @copyright 2016-2022 Joomlashack.com. All rights reserved.
 * @license   https://www.gnu.org/licenses/gpl.html GNU/GPL
 *
 * This file is part of OSMap.
 *
 * OSMap is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * OSMap is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with OSMap.  If not, see <https://www.gnu.org/licenses/>.
 */

use Alledia\OSMap;
use Joomla\CMS\Toolbar\ToolbarHelper;

defined('_JEXEC') or die();


class OSMapViewSitemapItems extends OSMap\View\Admin\AbstractList
{
    /**
     * @param string $tpl
     *
     * @return void
     * @throws Exception
     */
    public function display($tpl = null)
    {
        $app             = OSMap\Factory::getApplication();
        $this->sitemapId = $app->input->getInt('id', 0);
        $this->language  = $app->input->get('lang', '');

        $this->setToolBar();

        parent::display($tpl);
    }

    protected function setToolBar($addDivider = true)
    {
        $isNew = true;

        $this->setTitle('COM_OSMAP_PAGE_VIEW_SITEMAP_ITEMS');

        ToolbarHelper::apply('sitemapitems.apply');
        ToolbarHelper::save('sitemapitems.save');

        $alt = $isNew ? 'JTOOLBAR_CANCEL' : 'JTOOLBAR_CLOSE';
        ToolbarHelper::cancel('sitemapitems.cancel', $alt);

        parent::setToolBar($addDivider);
    }
}
