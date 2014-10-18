<?php
/**
 * Part of the Fuel framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * NOTICE:
 *
 * If you need to make modifications to the default configuration, copy
 * this file to your app/config folder, and make them in there.
 *
 * This will allow you to upgrade fuel without losing your custom config.
 */


return array(

	// the active pagination template
	'active'                      => 'default',

	// Twitter bootstrap 2.x template
	'default'                   => array(
		'wrapper'                 => "<ul class=\"pagination\">\n\t{pagination}\n\t\n</ul>\n",

		'first'                   => "\n\t\t<li>{link}</li>",
		'first-marker'            => "&laquo;&laquo;",
		'first-link'              => "<a href=\"{uri}\">{page}</a>",

		'first-inactive'          => "",
		'first-inactive-link'     => "",

		'previous'                => "\n\t\t<li>{link}</li>",
		'previous-marker'         => "&laquo;",
		'previous-link'           => "<a href=\"{uri}\" rel=\"prev\">{page}</a>",

		'previous-inactive'       => "\n\t\t<li class=\"disabled\">{link}</li>",
		'previous-inactive-link'  => "<a href=\"#\" rel=\"prev\">{page}</a>",

		'regular'                 => "\n\t\t<li>{link}</li>",
		'regular-link'            => "<a href=\"{uri}\">{page}</a>",

		'active'                  => "\n\t\t<li class=\"active\">{link}</li>",
		'active-link'             => "<a href=\"#\">{page}</a>",

		'next'                    => "\n\t\t<li>{link}</li>",
		'next-marker'             => "&raquo;",
		'next-link'               => "<a href=\"{uri}\" rel=\"next\">{page}</a>",

		'next-inactive'           => "\n\t\t<li class=\"disabled\">{link}</li>",
		'next-inactive-link'      => "<a href=\"#\" rel=\"next\">{page}</a>",

		'last'                    => "\n\t\t<li>{link}</li>",
		'last-marker'             => "&raquo;&raquo;",
		'last-link'               => "<a href=\"{uri}\">{page}</a>",

		'last-inactive'           => "",
		'last-inactive-link'      => "",
	),

);
