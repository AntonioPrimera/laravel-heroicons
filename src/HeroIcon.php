<?php

namespace AntonioPrimera\HeroIcons;

use Illuminate\Support\Str;
use SVG\SVG;

class HeroIcon
{
	const FORMAT_OUTLINE = 'outline';
	const FORMAT_SOLID   = 'solid';
	
	/**
	 * A buffer for icons, so we don't load them twice
	 *
	 * @var array
	 */
	protected static $icons = [];
	
	/**
	 * @var SVG
	 */
	protected $svgInstance;
	
	protected $name;
	protected $format;
	
	public function __construct(string $name, string $format = self::FORMAT_OUTLINE)
	{
		$this->name = $name;
		$this->format = $format;
		
		$fileName = Str::kebab($name) . '.svg';
		$filePath = __DIR__ . "/../resources/heroicons/{$format}/{$fileName}";
		
		$this->svgInstance = file_exists($filePath)
			? SVG::fromFile($filePath)
			: SVG::fromString($this->defaultIcon());
		
		//buffer this instance
		static::set($this);
	}
	
	public function setClass($classes = [])
	{
		$classString = is_array($classes) ? implode(' ', $classes) : $classes;
		$this->svgInstance->getDocument()->setAttribute('class', $classString);
		
		return $this;
	}
	
	public function setSize($width, $height)
	{
		if (is_numeric($width))
			$this->svgInstance->getDocument()->setWidth($width);
		
		if (is_numeric($height))
			$this->svgInstance->getDocument()->setHeight($height);
		
		return $this;
	}
	
	public function removeSize()
	{
		$this->svgInstance
			->getDocument()
			->removeAttribute('width')
			->removeAttribute('height');
		
		return $this;
	}
	
	public function setAttributes(array $attributes)
	{
		foreach ($attributes as $name => $value)
			$this->svgInstance
				->getDocument()
				->setAttribute($name, $value);
		
		return $this;
	}
	
	public function useCurrentColor()
	{
		$document = $this->svgInstance->getDocument();
		
		//set the stroke on the parent attribute
		$document->setAttribute('stroke', 'currentColor');
		
		//and remove it from all child attributes
		for ($i = 0; $i < $document->countChildren(); $i++) {
			//dump($document->getChild($i)->getStyle('stroke'));
			$document->getChild($i)->removeStyle('stroke')->removeAttribute('stroke');
		}
		
		return $this;
	}
	
	public function render(): string
	{
		return $this->svgInstance->toXMLString();
	}
	
	public function __toString()
	{
		return $this->render();
	}
	
	//--- Public static methods ---------------------------------------------------------------------------------------
	
	public static function get($name, $format = self::FORMAT_OUTLINE): HeroIcon
	{
		return static::$icons[$name] ?? new static($name, $format);
	}
	
	protected static function set(HeroIcon $heroIcon)
	{
		static::$icons["{$heroIcon->format}:{$heroIcon->name}"] = $heroIcon;
	}
	
	//--- Protected helpers -------------------------------------------------------------------------------------------
	
	protected function defaultIcon(): string
	{
		return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
			.'<path d="M8.22766 9C8.77678 7.83481 10.2584 7 12.0001 7C14.2092 7 16.0001 8.34315 16.0001 10C16.0001 11.3994 14.7224 12.5751 12.9943 12.9066C12.4519 13.0106 12.0001 13.4477 12.0001 14M12 17H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#4A5568" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
			. '</svg>';
	}
}