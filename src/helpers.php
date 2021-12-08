<?php

use AntonioPrimera\HeroIcons\HeroIcon;

function heroIcon(
	string $name,
	string $format = HeroIcon::FORMAT_OUTLINE,
	$classes = '',
	array $attributes = [],
	int $options = HeroIcon::SVG_REMOVE_SIZE | HeroIcon::SVG_CURRENT_COLOR
): string
{
	$icon = new HeroIcon($name, $format, $options);
	
	if ($classes)
		$icon->setClass($classes);
	
	if ($attributes)
		$icon->setAttributes($attributes);
	
	return $icon->render();
}