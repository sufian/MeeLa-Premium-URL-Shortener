<?php
/**
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.6
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2013 Fuel Development Team
 * @link       http://fuelphp.com
 */

namespace Fuel\Tasks;

/**
 * Robot example task
 *
 * Ruthlessly stolen from the beareded Canadian sexy symbol:
 *
 *		Derek Allard: http://derekallard.com/
 *
 * @package		Fuel
 * @version		1.0
 * @author		Phil Sturgeon
 */

class Robots
{

	/**
	 * This method gets ran when a valid method name is not used in the command.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots
	 *
	 * or
	 *
	 * php oil r robots "Kill all Mice"
	 *
	 * @return string
	 */
	public static function run($speech = null)
	{
		if ( ! isset($speech))
		{
			$speech = 'KILL ALL HUMANS!';
		}

		$eye = \Cli::color("*", 'red');

		return \Cli::color("
					\"{$speech}\"
			          _____     /
			         /_____\\", 'blue')."\n"
.\Cli::color("			    ____[\\", 'blue').$eye.\Cli::color('---', 'blue').$eye.\Cli::color('/]____', 'blue')."\n"
.\Cli::color("			   /\\ #\\ \\_____/ /# /\\
			  /  \\# \\_.---._/ #/  \\
			 /   /|\\  |   |  /|\\   \\
			/___/ | | |   | | | \\___\\
			|  |  | | |---| | |  |  |
			|__|  \\_| |_#_| |_/  |__|
			//\\\\  <\\ _//^\\\\_ />  //\\\\
			\\||/  |\\//// \\\\\\\\/|  \\||/
			      |   |   |   |
			      |---|   |---|
			      |---|   |---|
			      |   |   |   |
			      |___|   |___|
			      /   \\   /   \\
			     |_____| |_____|
			     |HHHHH| |HHHHH|", 'blue');
	}

	/**
	 * An example method that is here just to show the various uses of tasks.
	 *
	 * Usage (from command line):
	 *
	 * php oil r robots:protect
	 *
	 * @return string
	 */
	public static function protect()
	{
		$eye = \Cli::color("*", 'green');

		return \Cli::color("
					\"PROTECT ALL HUMANS\"
			          _____     /
			         /_____\\", 'blue')."\n"
.\Cli::color("			    ____[\\", 'blue').$eye.\Cli::color('---', 'blue').$eye.\Cli::color('/]____', 'blue')."\n"
.\Cli::color("			   /\\ #\\ \\_____/ /# /\\
			  /  \\# \\_.---._/ #/  \\
			 /   /|\\  |   |  /|\\   \\
			/___/ | | |   | | | \\___\\
			|  |  | | |---| | |  |  |
			|__|  \\_| |_#_| |_/  |__|
			//\\\\  <\\ _//^\\\\_ />  //\\\\
			\\||/  |\\//// \\\\\\\\/|  \\||/
			      |   |   |   |
			      |---|   |---|
			      |---|   |---|
			      |   |   |   |
			      |___|   |___|
			      /   \\   /   \\
			     |_____| |_____|
			     |HHHHH| |HHHHH|", 'blue');

	}
	
	public	static function insert_countries()
	{
            $count = \Model_Url_Stats_Country::query()
                ->count();
            
            if($count == 0)
            {
                ini_set('memory_limit', '-1');
                $file = fopen(DOCROOT.'public/assets/url_stats_countries.csv','r');
                while(feof($file) === false)
                {
                    $line = fgets($file);
                    $data_array = explode(',',trim($line));
                    // start long ip
                    // end long ip
                    // country
                    $country = \Model_Url_Stats_Country::forge(array(
                        'start_ip' => $data_array[0],
                        'end_ip' => $data_array[1],
                        'country' => $data_array[2],
                    ));
                    
                    $country->save();
                }
                fclose($f);
            }
	}
	
}



/* End of file tasks/robots.php */
