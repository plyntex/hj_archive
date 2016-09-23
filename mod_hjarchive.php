<?php
/**
 * @copyright	@copyright	Copyright (c) 2016 HJ. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// no direct access
defined('_JEXEC') or die;

// include the syndicate functions only once
require_once __DIR__ . '/helper.php';

$class_sfx = htmlspecialchars($params->get('class_sfx'));
$list = modHJArchiveHelper::getList( $params );

$doc = JFactory::getDocument();

//enable CSS
if ($params->get('enable_css', 1))
$doc->addStylesheet(JURI::base(true) . '/modules/mod_hjarchive/assets/css/archive.css');

// enable JS
if ($params->get('enable_js', 1))
$doc->addScript(JURI::base(true) . '/modules/mod_hjarchive/assets/js/archive.js');


require(JModuleHelper::getLayoutPath('mod_hjarchive', $params->get('layout', 'default')));