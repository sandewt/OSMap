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

use Joomla\CMS\Language\Text;

defined('_JEXEC') or die();

$count             = 0;
$showItemUID       = $this->osmapParams->get('show_item_uid', 0);
$showExternalLinks = (int)$this->osmapParams->get('show_external_links', 0);

/**
 * This method is called while traversing the sitemap items tree, and is
 * used to append the found item to the sitemapItems attribute, which will
 * be used in the view. Will not add ignored items. Duplicate items will
 * be included.
 *
 * @param object $item
 *
 * @return bool
 */
$printNodeCallback = function ($item) use (&$count, &$showItemUID, &$showExternalLinks) {
    if (!$item->isInternal) :
        if ($showExternalLinks === 0) :
            // Don't show external links
            $item->set('ignore', true);
            $item->addAdminNote('COM_OSMAP_ADMIN_NOTE_IGNORED_EXTERNAL');

        elseif ($showExternalLinks === 2) :
            // Display only in the HTML sitemap
            $item->addAdminNote('COM_OSMAP_ADMIN_NOTE_IGNORED_EXTERNAL_HTML');
        endif;
    endif;

    // Add notes about sitemap visibility
    if (!$item->visibleForXML) :
        $item->addAdminNote('COM_OSMAP_ADMIN_NOTE_VISIBLE_HTML_ONLY');
    endif;

    if (!$item->visibleForHTML) :
        $item->addAdminNote('COM_OSMAP_ADMIN_NOTE_VISIBLE_XML_ONLY');
    endif;

    if (!$item->visibleForRobots) :
        $item->addAdminNote('COM_OSMAP_ADMIN_NOTE_INVISIBLE_FOR_ROBOTS');
    endif;

    if (!$item->parentIsVisibleForRobots) :
        $item->addAdminNote('COM_OSMAP_ADMIN_NOTE_PARENT_INVISIBLE_FOR_ROBOTS');
    endif;

    if (!$item->hasCompatibleLanguage() || $item->ignore) :
        return false;
    endif;

    ?>
    <tr class="sitemapitem row<?php echo $count; ?> <?php echo ($showItemUID) ? 'with-uid' : ''; ?>"
        data-uid="<?php echo $item->uid; ?>"
        data-settings-hash="<?php echo $item->settingsHash; ?>">

        <td class="center">
            <div class="sitemapitem-published"
                 data-original="<?php echo $item->published ? '1' : '0'; ?>"
                 data-value="<?php echo $item->published ? '1' : '0'; ?>">

                <?php
                $class = $item->published ? 'publish' : 'unpublish';
                $title = $item->published
                    ? 'COM_OSMAP_TOOLTIP_CLICK_TO_UNPUBLISH'
                    : 'COM_OSMAP_TOOLTIP_CLICK_TO_PUBLISH';
                ?>

                <span title="<?php echo Text::_($title); ?>"
                      class="hasTooltip icon-<?php echo $class; ?>">
                    </span>
            </div>
            <?php
            $notes = $item->getAdminNotesString();

            if (!empty($notes)) :
                ?>
                <span class="icon-warning hasTooltip osmap-info" title="<?php echo $notes; ?>"></span>
            <?php endif; ?>
        </td>

        <td class="sitemapitem-link">
            <?php
            if ($item->level > 0) :
                ?>
                <span class="level-mark">
                    <?php echo str_repeat('—', $item->level); ?>
                </span>
            <?php endif;

            if (!empty($item->rawLink) && $item->rawLink !== '#' && $item->link !== '#') :
                ?>
                <a href="<?php echo $item->rawLink; ?>"
                   target="_blank"
                   class="hasTooltip"
                   title="<?php echo $item->link; ?>">
                    <?php echo $item->rawLink; ?>
                </a>
                <span class="icon-new-tab"></span>

            <?php else : ?>
                <span>
                    <?php echo $item->name ?? ''; ?>
                </span>
            <?php endif; ?>

            <?php
            if ($showItemUID) :
                ?>
                <br>
                <div class="small osmap-item-uid">
                    <?php echo Text::_('COM_OSMAP_UID'); ?>: <?php echo $item->uid; ?>
                </div>
            <?php endif; ?>
        </td>

        <td class="sitemapitem-name">
            <?php echo $item->name ?? ''; ?>
        </td>

        <td class="center">
            <div class="sitemapitem-priority"
                 data-original="<?php echo $item->priority; ?>"
                 data-value="<?php echo sprintf('%03.1f', $item->priority); ?>">

                <?php echo sprintf('%03.1f', $item->priority); ?>
            </div>
        </td>

        <td class="center">
            <div class="sitemapitem-changefreq"
                 data-original="<?php echo $item->changefreq; ?>"
                 data-value="<?php echo $item->changefreq; ?>">

                <?php echo Text::_('COM_OSMAP_' . strtoupper($item->changefreq)); ?>
            </div>
        </td>
    </tr>
    <?php
    $count++;

    return true;
};
?>
    <table class="adminlist table table-striped" id="itemList">
        <thead>
        <tr>
            <th style="width: 1%;min-width:55px" class="nowrap center">
                <?php echo Text::_('COM_OSMAP_HEADING_STATUS'); ?>
            </th>

            <th class="title">
                <?php echo Text::_('COM_OSMAP_HEADING_URL'); ?>
            </th>

            <th class="title">
                <?php echo Text::_('COM_OSMAP_HEADING_TITLE'); ?>
            </th>

            <th class="center">
                <?php echo Text::_('COM_OSMAP_HEADING_PRIORITY'); ?>
            </th>

            <th class="nowrap center">
                <?php echo Text::_('COM_OSMAP_HEADING_CHANGE_FREQ'); ?>
            </th>
        </tr>
        </thead>

        <tbody>
        <?php
        if (is_object($this->sitemap)) :
            $this->sitemap->traverse($printNodeCallback, false, true);
        endif;
        ?>
        </tbody>
    </table>
    <div><?php echo Text::sprintf('COM_OSMAP_NUMBER_OF_ITEMS_FOUND', $count); ?></div>

<?php
if (empty($count)) :
    ?>
    <div class="alert alert-warning">
        <?php echo Text::_('COM_OSMAP_NO_ITEMS'); ?>
    </div>
<?php
endif;
