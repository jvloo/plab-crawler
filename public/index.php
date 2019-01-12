<?php
/*
 *---------------------------------------------------------------
 * APPLICATION VERSION
 *---------------------------------------------------------------
 */
 define('APP_VERSION', '0.1.0-beta.1');
 
/*
 *---------------------------------------------------------------
 * APPLICATION OFFLINE MODE
 *---------------------------------------------------------------
 */
 define('APP_OFFLINE', false);

/*
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     testing
 *     production
 *
 * NOTE: If you change these, also change the error_reporting() code below
 */
	define('ENVIRONMENT', isset($_SERVER['CI_ENV']) ? $_SERVER['CI_ENV'] : 'development');

/*
 *---------------------------------------------------------------
 * ERROR REPORTING
 *---------------------------------------------------------------
 *
 * Different environments will require different levels of error reporting.
 * By default development will show errors but testing and live will hide them.
 */
	switch (ENVIRONMENT) {
		case 'development':
			error_reporting(-1);
			ini_set('display_errors', 1);
		break;

		case 'testing':
		case 'production':
			ini_set('display_errors', 0);
			if (version_compare(PHP_VERSION, '5.3', '>=')) {
				error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED & ~E_STRICT & ~E_USER_NOTICE & ~E_USER_DEPRECATED);
			} else {
				error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_USER_NOTICE);
			}
		break;

		default:
			header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
			echo 'The application environment is not set correctly.';
			exit(1); // EXIT_ERROR
	}

/*
 *---------------------------------------------------------------
 * ROOT DIRECTORY
 *---------------------------------------------------------------
 *
 * This folder contains all source files that are hidden from
 * public access.
 *
 * Set the path to the source files from the path of same directory
 * that houses index.php (FCPATH).
 *
 * NO TRAILING SLASH!
 */
	switch (ENVIRONMENT) {
		case 'development':
			$root_path = '../';
			break;

		case 'testing':
		case 'production':
			$root_path = '';
			break;

		default:
			header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
			echo 'The application environment is not set correctly.';
			exit(1); // EXIT_ERROR
	}

/*
 *---------------------------------------------------------------
 * SYSTEM DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This variable must contain the name of your "system" directory.
 * Set the path if it is not in the same directory as this file.
 */
	$system_path = 'src/system';

/*
 *---------------------------------------------------------------
 * APPLICATION DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want this front controller to use a different "application"
 * directory than the default one you can set its name here. The directory
 * can also be renamed or relocated anywhere on your server. If you do,
 * use an absolute (full) server path.
 * For more info please see the user guide:
 *
 * https://codeigniter.com/user_guide/general/managing_apps.html
 *
 * NO TRAILING SLASH!
 */
	$application_folder = 'src/application';

/*
 *---------------------------------------------------------------
 * MODULES DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * This folder contains all pluggable modules for the application
 *
 * NO TRAILING SLASH!
 */
	$modules_folder = 'app/modules';

/*
 *---------------------------------------------------------------
 * VIEW DIRECTORY NAME
 *---------------------------------------------------------------
 *
 * If you want to move the view directory out of the application
 * directory, set the path to it here. The directory can be renamed
 * and relocated anywhere on your server. If blank, it will default
 * to the standard location inside your application directory.
 * If you do move this, use an absolute (full) server path.
 *
 * NO TRAILING SLASH!
 */
	$view_folder = '';


// --------------------------------------------------------------------
// END OF USER CONFIGURABLE SETTINGS.  DO NOT EDIT BELOW THIS LINE
// --------------------------------------------------------------------

/**
 * Set the current directory correctly for CLI requests
 */
	if (defined('STDIN')) {
		chdir(dirname(__FILE__));
	}

/**
 * Resolve Path Function
 *
 * @param  string       $path      [description]
 * @param  string       $path_name [description]
 * @return string       [description]
 */
function resolve_path(string $path, string $path_name) {
	// Check if the path exists
	if ( ! is_dir($path)) {
		header('HTTP/1.1 503 Service Unavailable.', TRUE, 503);
		echo 'Your '.$path_name.' path does not appear to be set correctly.';

		exit(3);
	}

	// Resolve the path
	if (($_temp = realpath($path)) !== false) {
		$path = $_temp.DS;
	} else {
		// Ensure there's a trailing slash
		$path = strtr(rtrim($path, '/\\'), '/\\', DS.DS).DS;
	}

	return $path;
}

//------------------------------------------------------------------------------
// DEFINE SOME USEFUL CONSTANTS
//------------------------------------------------------------------------------

/**
 * Directory Separator Abbreviation
 */
define('DS', DIRECTORY_SEPARATOR);

/**
 * The Name of THIS File
 */
define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));

/**
 * Path to the front controller (this file) directory
 */
define('FCPATH', dirname(__FILE__).DS);

/**
 * Path to the ROOTPATH folder
 */
define('ROOTPATH', resolve_path($root_path, 'ROOTPATH folder'));

/**
 * Path to the system directory
 */
define('BASEPATH', resolve_path(ROOTPATH.$system_path, 'system folder'));

/**
 * Name of the "system" directory
 */
define('SYSDIR', basename(BASEPATH));

/**
 * Path to the "application" directory
 */
define('APPPATH', resolve_path(ROOTPATH.$application_folder, 'application folder'));

/**
 * Path to the modules folder
 */
defined('MODPATH') OR define('MODPATH', resolve_path(ROOTPATH.$modules_folder, 'modules folder'));


/**
 * Path to the "views" folder
 */
 if ( ! isset($view_folder[0]) && is_dir(APPPATH.'views'.DS)) {
	 define('VIEWPATH', resolve_path(APPPATH.'views', 'views folder'));
 } else {
	 define('VIEWPATH', resolve_path(ROOTPATH.$view_folder, 'views folder'));
 }


//------------------------------------------------------------------------------
//	LOAD THE BOOTSTRAP FILE
//------------------------------------------------------------------------------
require_once BASEPATH.'core/CodeIgniter.php';
