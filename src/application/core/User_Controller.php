<?php
defined('BASEPATH') or exit('No direct script access allowed.');

class User_Controller extends Base_Controller {

  /**
	 * Class constructor
	 * @return 	void
	 */
  public function __construct()
  {
    parent::__construct();

    if ( ! $this->aauth->is_loggedin()) {
      redirect('user/login');
    }
  }
}
