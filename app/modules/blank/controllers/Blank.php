<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Blank extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();
  }

  public function index()
  {
    $this->template->title->prepend('blank | ');
    $this->template->content->view('blank', $this->data);

    $this->template->publish();
  }
}
