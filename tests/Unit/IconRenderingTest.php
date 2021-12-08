<?php
namespace AntonioPrimera\HeroIcons\Tests\Unit;

use AntonioPrimera\HeroIcons\HeroIcon;
use AntonioPrimera\HeroIcons\Tests\CustomAssertions;
use AntonioPrimera\HeroIcons\Tests\TestCase;
use SVG\Nodes\SVGNode;
use SVG\SVG;

class IconRenderingTest extends TestCase
{
	use CustomAssertions;
	
	/** @test */
	public function make_sure_that_the_helpers_function_properly()
	{
		$this->assertIconsStringsAreTheSame(
			'<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 3C2.89543 3 2 3.89543 2 5C2 6.10457 2.89543 7 4 7H16C17.1046 7 18 6.10457 18 5C18 3.89543 17.1046 3 16 3H4Z" fill="#4A5568"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3 8H17V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V8ZM8 11C8 10.4477 8.44772 10 9 10H11C11.5523 10 12 10.4477 12 11C12 11.5523 11.5523 12 11 12H9C8.44772 12 8 11.5523 8 11Z" fill="#4A5568"/></svg>',
			'<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M4 3C2.89543 3 2 3.89543 2 5C2 6.10457 2.89543 7 4 7H16C17.1046 7 18 6.10457 18 5C18 3.89543 17.1046 3 16 3H4Z" fill="#4A5568"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3 8H17V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V8ZM8 11C8 10.4477 8.44772 10 9 10H11C11.5523 10 12 10.4477 12 11C12 11.5523 11.5523 12 11 12H9C8.44772 12 8 11.5523 8 11Z" fill="#4A5568"/></svg>'
		);
		
		$this->expectAssertionToFail();
		$this->assertIconsStringsAreTheSame(
			'<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 3C1.89543 3 2 3.89543 2 5C2 6.10457 2.89543 7 4 7H16C17.1046 7 18 6.10457 18 5C18 3.89543 17.1046 3 16 3H4Z" fill="#4A5568"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3 8H17V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V8ZM8 11C8 10.4477 8.44772 10 9 10H11C11.5523 10 12 10.4477 12 11C12 11.5523 11.5523 12 11 12H9C8.44772 12 8 11.5523 8 11Z" fill="#4A5568"/></svg>',
			'<svg width="20" height="20" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M4 3C2.89543 3 2 3.89543 2 5C2 6.10457 2.89543 7 4 7H16C17.1046 7 18 6.10457 18 5C18 3.89543 17.1046 3 16 3H4Z" fill="#4A5568"/><path fill-rule="evenodd" clip-rule="evenodd" d="M3 8H17V15C17 16.1046 16.1046 17 15 17H5C3.89543 17 3 16.1046 3 15V8ZM8 11C8 10.4477 8.44772 10 9 10H11C11.5523 10 12 10.4477 12 11C12 11.5523 11.5523 12 11 12H9C8.44772 12 8 11.5523 8 11Z" fill="#4A5568"/></svg>'
		);
	}
	
	/** @test */
	public function a_hero_icon_renders_correctly()
	{
		$archiveIcon = new HeroIcon('archive');
		$this->assertIconsStringsAreTheSame($archiveIcon->render(), SVG::fromFile(__DIR__ . '/../../resources/heroicons/outline/archive.svg')->toXMLString());
		
		$this->expectAssertionToFail();
		$this->assertIconsStringsAreTheSame($archiveIcon->render(), SVG::fromFile(__DIR__ . '/../../resources/heroicons/outline/home.svg')->toXMLString());
	}
	
	/** @test */
	public function a_hero_icon_must_have_by_default_the_stroke_set_to_current_color()
	{
		$archiveIcon = new HeroIcon('archive');
		$this->assertEquals('currentColor', $archiveIcon->getSvgInstance()->getDocument()->getAttribute('stroke'));
	}
	
	/** @test */
	public function a_hero_icon_must_not_have_the_width_and_height_attributes_by_default()
	{
		$archiveIcon = new HeroIcon('archive');
		$this->assertNull($archiveIcon->getSvgInstance()->getDocument()->getAttribute('width'));
		$this->assertNull($archiveIcon->getSvgInstance()->getDocument()->getAttribute('height'));
		
		$rawArchiveIcon = new HeroIcon('archive', 'outline', 0);
		$this->assertEquals(24, $rawArchiveIcon->getSvgInstance()->getDocument()->getAttribute('width'));
		$this->assertEquals(24, $rawArchiveIcon->getSvgInstance()->getDocument()->getAttribute('height'));
		
		//just a double check
		$this->expectAssertionToFail();
		$this->assertNull($rawArchiveIcon->getSvgInstance()->getDocument()->getAttribute('width'));
	}
	
	/** @test */
	public function it_can_set_a_class_attribute_on_the_outer_node()
	{
		$archiveIcon = new HeroIcon('archive');
		$this->assertNull($archiveIcon->getSvgInstance()->getDocument()->getAttribute('class'));
		
		$archiveIcon->setClass('w-full mt-4');
		$this->assertEquals('w-full mt-4', $archiveIcon->getSvgInstance()->getDocument()->getAttribute('class'));
		
		$archiveIcon->setClass(['mt-3', 'w-50']);
		$this->assertEquals('mt-3 w-50', $archiveIcon->getSvgInstance()->getDocument()->getAttribute('class'));
	}
	
	/** @test */
	public function it_renders_a_default_icon_if_the_given_icon_name_does_not_exist()
	{
		$unknownIcon = new HeroIcon('some-unknown-icon-name');
		$this->assertIconsStringsAreTheSame(
			$unknownIcon->render(),
			SVG::fromFile(__DIR__ . '/../../resources/heroicons/outline/question-mark-circle.svg')->toXMLString()
		);
	}
	
	/** @test */
	public function the_helper_renders_a_hero_icon()
	{
		$this->assertIconsStringsAreTheSame(
			(new HeroIcon('archive'))->render(),
			heroIcon('archive')
		);
		
		$this->expectAssertionToFail();
		$this->assertIconsStringsAreTheSame(
			(new HeroIcon('home'))->render(),
			heroIcon('archive')
		);
	}
	
	/** @test */
	public function the_blade_directive_renders_a_hero_icon()
	{
		$blade = resolve('blade.compiler');
		$icon = $blade->compileString('@heroIcon("archive")');
		$this->assertEquals((new HeroIcon('archive'))->render(), $icon);
		
		$icon = $blade->compileString('@heroIcon("archive", "solid")');
		$this->assertEquals((new HeroIcon('archive', HeroIcon::FORMAT_SOLID))->render(), $icon);
	}
	
	//--- Helpers -----------------------------------------------------------------------------------------------------
	
	protected function referenceIcon()
	{
		//reference icon is "archive" from file "resources/heroicons/outline/archive.svg"
		return '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
			. '<path d="M5 8H19M5 8C3.89543 8 3 7.10457 3 6C3 4.89543 3.89543 4 5 4H19C20.1046 4 21 4.89543 21 6C21 7.10457 20.1046 8 19 8M5 8L5 18C5 19.1046 5.89543 20 7 20H17C18.1046 20 19 19.1046 19 18V8M10 12H14" stroke="#4A5568" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>'
			. '</svg>';
	}
	
	protected function assertIconsStringsAreTheSame(string $icon1, string $icon2)
	{
		$svg1 = SVG::fromString($icon1);
		$svg2 = SVG::fromString($icon2);
		
		$this->assertNodesAreTheSame($svg1->getDocument(), $svg2->getDocument());
		
		return $this;
	}
	
	protected function assertNodesAreTheSame(SVGNode $node1, SVGNode $node2)
	{
		$this->assertEquals($node1->getName(), $node2->getName());
		
		$attributes1 = $node1->getSerializableAttributes();
		$attributes2 = $node2->getSerializableAttributes();
		
		if ($node1->getName() === 'svg') {
			unset($attributes1['width']);
			unset($attributes2['width']);
			unset($attributes1['height']);
			unset($attributes2['height']);
		}
		
		$this->assertArraysAreSame($attributes1, $attributes2);
		
		$this->assertEquals($node1->countChildren(), $node2->countChildren());
		
		for ($i = 0; $i < $node1->countChildren(); $i++) {
			$this->assertNodesAreTheSame($node1->getChild($i), $node2->getChild($i));
		}
		
		return $this;
	}
}