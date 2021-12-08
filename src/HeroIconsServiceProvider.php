<?php

namespace AntonioPrimera\HeroIcons;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Str;

class HeroIconsServiceProvider extends \Illuminate\Support\ServiceProvider
{
	
	public function boot()
	{
		//the default (static) heroIcon will render the svg, and its result will be cached
		//no variables can / should be used as its arguments
		//for most cases, this should be enough, and it's the fastest solution
		Blade::directive('heroIcon', function ($expression) {
			$arguments = Str::of($expression)
				->explode(',')
				->map(function($arg) {
					return trim($arg, "\"\'\ \t\n\r\0\x0B");
				});
			
			//if we return the hero icon
			return (new HeroIcon(
				$arguments[0],
				$arguments[1] ?? HeroIcon::FORMAT_OUTLINE
			))->render();
		});
		
		//the dynamic heroIcon will render the recipe for creating and rendering a heroIcon, so
		//this will be re-evaluated at every rendering of the blade template
		//use this if you need to display icons, which change from one page rendering to the next
		Blade::directive('dynamicHeroIcon', function($expression) {
			return "<?php (new HeroIcon($expression))->render(); ?>";
		});
	}
}