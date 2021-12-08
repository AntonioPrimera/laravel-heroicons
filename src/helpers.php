<?php

function heroicon(
	string $name,
	string $format = \AntonioPrimera\HeroIcons\HeroIcon::FORMAT_OUTLINE,
	$classes = '',
	bool $removeSize = true,
	array $attributes = []
)
{
	$icon = \AntonioPrimera\HeroIcons\HeroIcon::get($name, $format);
	
	if ($classes)
		$icon->setClass($classes);
	
	if ($removeSize)
		$icon->removeSize();
	
	if ($attributes)
		$icon->setAttributes($attributes);
	
	return $icon->render();
}