<?php

namespace AntonioPrimera\HeroIcons;

use Illuminate\Support\Str;
use SVG\SVG;

class HeroIcon
{
	const FORMAT_OUTLINE = 'outline';
	const FORMAT_SOLID   = 'solid';
	
	const SVG_REMOVE_SIZE 	= 0b0001;
	const SVG_CURRENT_COLOR = 0b0010;
	
	/**
	 * @var SVG
	 */
	protected $svgInstance;
	
	protected $name;
	protected $format;
	
	/**
	 * Create a HeroIcon instance, given the name of the icon, its format and a set of options.
	 * The name can be given as camelCase / kebab-case.
	 * The format can be either 'outline' or 'solid' (default 'outline').
	 * The options allow you to remove the hard-coded size of the hero-icon (and let its container determine the size)
	 * and to remove the hard-coded stroke color and to set the stroke to currentColor, so the icon takes the color
	 * set on its parent container.
	 *
	 * @param string $name
	 * @param string $format
	 * @param int    $options
	 */
	public function __construct(
		string $name,
		string $format = self::FORMAT_OUTLINE,
		int $options = self::SVG_REMOVE_SIZE | self::SVG_CURRENT_COLOR
	)
	{
		$this->name = $name;
		$this->format = strtolower($format);
		
		$fileName = Str::kebab($name) . '.svg';
		$filePath = __DIR__ . "/../resources/heroicons/{$format}/{$fileName}";
		
		$this->svgInstance = file_exists($filePath)
			? SVG::fromFile($filePath)
			: SVG::fromString($this->defaultIcon());
		
		if ($options & self::SVG_REMOVE_SIZE)
			$this->removeSize();
		
		if ($options & self::SVG_CURRENT_COLOR)
			$this->useCurrentColor();
	}
	
	/**
	 * Set the class attribute of the svg node.
	 * It accepts a string or an indexed
	 * array of class names.
	 *
	 * @param string | array $classes
	 *
	 * @return $this
	 */
	public function setClass($classes = [])
	{
		$classString = is_array($classes) ? implode(' ', $classes) : $classes;
		$this->svgInstance->getDocument()->setAttribute('class', $classString);
		
		return $this;
	}
	
	/**
	 * Set the 'width' and 'height' attributes
	 * on the outer svg node, in pixels.
	 *
	 * @param $width
	 * @param $height
	 *
	 * @return $this
	 */
	public function setSize(int $width, int $height)
	{
		if (is_numeric($width))
			$this->svgInstance->getDocument()->setWidth($width);
		
		if (is_numeric($height))
			$this->svgInstance->getDocument()->setHeight($height);
		
		return $this;
	}
	
	/**
	 * Removes the 'width' and the 'height'
	 * attributes from the svg node.
	 *
	 * @return $this
	 */
	public function removeSize()
	{
		$this->svgInstance
			->getDocument()
			->removeAttribute('width')
			->removeAttribute('height');
		
		return $this;
	}
	
	/**
	 * Set any attributes on the outer svg node.
	 * It accepts an array of associative
	 * name => value pairs.
	 *
	 * @param array $attributes
	 *
	 * @return $this
	 */
	public function setAttributes(array $attributes)
	{
		foreach ($attributes as $name => $value)
			$this->svgInstance
				->getDocument()
				->setAttribute($name, $value);
		
		return $this;
	}
	
	/**
	 * Remove any stroke styles and attributes from the outer and inner nodes and
	 * set a stroke="currentColor" attribute on the outer svg node. This will
	 * force the svg to take the color set on its containing node.
	 *
	 * @return $this
	 */
	public function useCurrentColor()
	{
		$document = $this->svgInstance->getDocument();
		
		//set the stroke on the parent attribute
		$document->setAttribute('stroke', 'currentColor');
		
		//and remove it from all child attributes
		for ($i = 0; $i < $document->countChildren(); $i++)
			$document->getChild($i)->removeStyle('stroke')->removeAttribute('stroke');
		
		return $this;
	}
	
	/**
	 * Render the svg to a html string
	 *
	 * @return string
	 */
	public function render(): string
	{
		return $this->svgInstance->toXMLString();
	}
	
	public function __toString()
	{
		return $this->render();
	}
	
	/**
	 * For more control, you can get access
	 * to the svg instance itself.
	 *
	 * @return SVG
	 */
	public function getSvgInstance()
	{
		return $this->svgInstance;
	}
	
	//--- Protected helpers -------------------------------------------------------------------------------------------
	
	protected function defaultIcon(): string
	{
		return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" stroke="currentColor">'
			.'<path d="M8.22766 9C8.77678 7.83481 10.2584 7 12.0001 7C14.2092 7 16.0001 8.34315 16.0001 10C16.0001 11.3994 14.7224 12.5751 12.9943 12.9066C12.4519 13.0106 12.0001 13.4477 12.0001 14M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
			. '</svg>';
	}
}