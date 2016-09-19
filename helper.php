<?php
/**
 * @copyright	Copyright (c) 2016 HJ. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

/**
 * HJ - HJ Archive Helper Class.
 *
 * @package		Joomla.Site
 * @subpakage	HJ.HJArchive
 */

require_once JPATH_SITE . '/components/com_content/helpers/route.php';
JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models', 'ContentModel');

class modHJArchiveHelper {
    
	public static function getList( $params )
    {
        $results = array();
        // Get the dbo
		$db = JFactory::getDbo();

		// Get an instance of the generic articles model
		$model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		// Set application parameters in model
		$app       = JFactory::getApplication();
		$appParams = $app->getParams();
		$model->setState('params', $appParams);
		
		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $params->get('count', 0));
		$model->setState('filter.published', 1);

		// Access filter
		$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);
		
		//Order filter
		$ordering = JArrayHelper::getValue($order_map, $params->get('ordering'), 'a.created');
		$dir      = 'DESC';
		$model->setState('list.ordering', $ordering);
		$model->setState('list.direction', $dir);
		$catid = $params->get('Category');
		
		//Categories
		$categories = JModelLegacy::getInstance('Categories', 'ContentModel', array('ignore_request' => true));
		$categories->setState('params', $appParams);
		$levels = $params->get('levels', 1) ? $params->get('levels', 1) : 9999;
		$categories->setState('filter.get_children', $levels);
		$categories->setState('filter.published', 1);
		$categories->setState('filter.access', $access);
		$additional_catids = array();

		$categories->setState('filter.parentId', $catid);
		
		$recursive = true;
		$items     = $categories->getItems($recursive);

		if ($items)
		{
			foreach ($items as $category)
			{
				$condition = (($category->level - $categories->getParent()->level) <= $levels);

				if ($condition)
				{
					$additional_catids[] = $category->id;
				}
			}
		}
		
		// Merge unique
		$catids = array_unique(array_merge((array)$catid, $additional_catids));
		
		// Category filter
		$model->setState('filter.category_id', $catids);
		
		// Get Articles
		$items = $model->getItems();
		
		foreach($items as $item){
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;
			$results[date("F", strtotime($item->created)) .' '. date("Y", strtotime($item->created))][] = $item;
		}
        return $results;
    }
}