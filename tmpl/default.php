<?php
/**
 * @copyright	Copyright (c) 2016 HJ. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;
?>

<div id="dwarchive">
<ul>
<?php foreach ($list as $key => $value){ ?>
        <li><span><?php echo $key ?></span>
        <ul>
            <?php foreach ($value as $item){ ?>
				<?php 
					$item->slug    = $item->id . ':' . $item->alias;
					$item->catslug = $item->catid . ':' . $item->category_alias;
					$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catid, $item->language));
				?>
				<li><a href="<?php echo $item->link; ?>"><?php echo $item->title;?></a></li>
            <?php } ?>
        </ul>
        </li>
<?php } ?>
</ul>
</div>
