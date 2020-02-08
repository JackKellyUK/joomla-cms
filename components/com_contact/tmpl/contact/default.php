<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_contact
 *
 * @copyright   Copyright (C) 2005 - 2019 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Layout\FileLayout;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\CMS\Router\Route;
use Joomla\Component\Contact\Site\Helper\RouteHelper;

$tparams = $this->item->params;
?>

<div class="com-contact contact" itemscope itemtype="https://schema.org/Person">
	<?php if ($tparams->get('show_page_heading')) : ?>
		<h1>
			<?php echo $this->escape($tparams->get('page_heading')); ?>
		</h1>
	<?php endif; ?>

	<?php if ($this->item->name && $tparams->get('show_name')) : ?>
		<div class="page-header">
			<h2>
				<?php if ($this->item->published == 0) : ?>
					<span class="badge badge-warning"><?php echo Text::_('JUNPUBLISHED'); ?></span>
				<?php endif; ?>
				<span class="contact-name" itemprop="name"><?php echo $this->item->name; ?></span>
			</h2>
		</div>
	<?php endif; ?>

	<?php $show_contact_category = $tparams->get('show_contact_category'); ?>

	<?php if ($show_contact_category === 'show_no_link') : ?>
		<h3>
			<span class="contact-category"><?php echo $this->item->category_title; ?></span>
		</h3>
	<?php elseif ($show_contact_category === 'show_with_link') : ?>
		<?php $contactLink = RouteHelper::getCategoryRoute($this->item->catid, $this->item->language); ?>
		<h3>
			<span class="contact-category"><a href="<?php echo $contactLink; ?>">
				<?php echo $this->escape($this->item->category_title); ?></a>
			</span>
		</h3>
	<?php endif; ?>

	<?php echo $this->item->event->afterDisplayTitle; ?>

	<?php if ($tparams->get('show_contact_list') && count($this->contacts) > 1) : ?>
		<form action="#" method="get" name="selectForm" id="selectForm">
			<label for="select_contact"><?php echo Text::_('COM_CONTACT_SELECT_CONTACT'); ?></label>
			<?php echo HTMLHelper::_('select.genericlist', $this->contacts, 'select_contact', 'class="inputbox" onchange="document.location.href = this.value"', 'link', 'name', $this->item->link); ?>
		</form>
	<?php endif; ?>

	<?php if ($tparams->get('show_tags', 1) && !empty($this->item->tags->itemTags)) : ?>
		<div class="com-contact__tags">
			<?php $this->item->tagLayout = new FileLayout('joomla.content.tags'); ?>
			<?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
		</div>
	<?php endif; ?>

	<?php echo $this->item->event->beforeDisplayContent; ?>

	<?php if ($this->params->get('show_info', 1)) : ?>
		<?php echo '<h3>' . Text::_('COM_CONTACT_DETAILS') . '</h3>'; ?>

		<?php if ($this->item->image && $tparams->get('show_image')) : ?>
			<div class="com-contact__thumbnail thumbnail float-right">
				<?php echo HTMLHelper::_('image', $this->item->image, htmlspecialchars($this->item->name,  ENT_QUOTES, 'UTF-8'), array('itemprop' => 'image')); ?>
			</div>
		<?php endif; ?>

		<?php if ($this->item->con_position && $tparams->get('show_position')) : ?>
			<dl class="com-contact__position contact-position dl-horizontal">
				<dt><?php echo Text::_('COM_CONTACT_POSITION'); ?>:</dt>
				<dd itemprop="jobTitle">
					<?php echo $this->item->con_position; ?>
				</dd>
			</dl>
		<?php endif; ?>

		<?php echo $this->loadTemplate('address'); ?>

		<?php if ($tparams->get('allow_vcard')) : ?>
			<?php echo Text::_('COM_CONTACT_DOWNLOAD_INFORMATION_AS'); ?>
			<a href="<?php echo Route::_('index.php?option=com_contact&amp;view=contact&amp;id=' . $this->item->id . '&amp;format=vcf'); ?>">
			<?php echo Text::_('COM_CONTACT_VCARD'); ?></a>
		<?php endif; ?>
	<?php endif; ?>

	<?php if ($tparams->get('show_email_form') && ($this->item->email_to || $this->item->user_id)) : ?>
		<?php echo '<h3>' . Text::_('COM_CONTACT_EMAIL_FORM') . '</h3>'; ?>

		<?php echo $this->loadTemplate('form'); ?>
	<?php endif; ?>

	<?php if ($tparams->get('show_links')) : ?>
		<?php echo $this->loadTemplate('links'); ?>
	<?php endif; ?>

	<?php if ($tparams->get('show_articles') && $this->item->user_id && $this->item->articles) : ?>
		<?php echo '<h3>' . Text::_('JGLOBAL_ARTICLES') . '</h3>'; ?>

		<?php echo $this->loadTemplate('articles'); ?>
	<?php endif; ?>

	<?php if ($tparams->get('show_profile') && $this->item->user_id && PluginHelper::isEnabled('user', 'profile')) : ?>
		<?php echo '<h3>' . Text::_('COM_CONTACT_PROFILE') . '</h3>'; ?>

		<?php echo $this->loadTemplate('profile'); ?>
	<?php endif; ?>

	<?php if ($tparams->get('show_user_custom_fields') && $this->contactUser) : ?>
		<?php echo $this->loadTemplate('user_custom_fields'); ?>
	<?php endif; ?>

	<?php if ($this->item->misc && $tparams->get('show_misc')) : ?>
		<?php echo '<h3>' . Text::_('COM_CONTACT_OTHER_INFORMATION') . '</h3>'; ?>

		<div class="com-contact__miscinfo contact-miscinfo">
			<dl class="dl-horizontal">
				<dt>
					<span class="<?php echo $tparams->get('marker_class'); ?>">
					<?php echo $tparams->get('marker_misc'); ?>
					</span>
				</dt>
				<dd>
					<span class="contact-misc">
						<?php echo $this->item->misc; ?>
					</span>
				</dd>
			</dl>
		</div>
	<?php endif; ?>
	<?php echo $this->item->event->afterDisplayContent; ?>
</div>