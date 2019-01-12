<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Base_Controller extends MY_Controller
{
  protected $user = [];

  protected $data = [];

  public function __construct()
  {
    parent::__construct();

    $this->template->title = $this->config->item('site_title');

    $this->template->meta->add('description', $this->config->item('site_description'));
    $this->template->meta->add('keywords', $this->config->item('site_keywords'));
    $this->template->meta->add('author', $this->config->item('site_author'));

    $this->template->stylesheet->add(asset_url('vendor/bootstrap/4.1.1/css/bootstrap.css'));
    $this->template->stylesheet->add(asset_url('vendor/plab/0.1.0/css/plab.css'));
    $this->template->stylesheet->add('https://fonts.googleapis.com/css?family=Righteous:400|Merriweather+Sans:700|Roboto:400,500,700');
    $this->template->stylesheet->add('https://use.fontawesome.com/releases/v5.6.1/css/all.css', [
      'integrity'   => 'sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP',
      'crossorigin' => 'anonymous',
    ]);


    $this->template->javascript->add('https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js', [
      'integrity' => 'sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k',
      'crossorigin' => 'anonymous',
    ]);

    //$this->template->javascript->add(asset_url('vendor/bootstrap/4.1.3/js/bootstrap.bundle.min.js'));
    $this->template->javascript->add('https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.9.0/feather.min.js');
    $this->template->javascript->add('https://cdn.rawgit.com/imsky/holder/master/holder.js');
    $this->template->javascript->add(asset_url('vendor/plab/0.1.0/js/plab.js'));

    if ($this->aauth->is_loggedin()) {
      $this->user = (array) $this->aauth->get_user();
      $this->_sync_cart();
    }

    $this->data['user'] = $this->user;

    $this->data['cart_contents'] = $this->cart->contents();


    $this->data['cart_total_items'] = number_limiter($this->cart->total_items());

    $this->data['cart_total'] = $this->cart->total();

    $this->template->header->view('partials/header', $this->data);
    $this->template->footer->view('partials/footer');
  }

  private function _sync_cart()
  {
    $sess_row_ids = [];

    foreach ($this->cart->contents() as $row_id => $cart_item) {
      $sess_row_ids[] = $row_id;
    }

    $user_id = $this->user['id'];

    $cart = $this->db->where('user_id', $user_id);
    $cart = $this->db->where('is_checkout', 0);
    $cart = $this->db->get('cart');

    if ($cart->num_rows() > 0) {
      $cart = $cart->row_array();
      $cart_id = $cart['cart_id'];
    } else {
      $data = [
        'user_id' => $user_id
      ];

      $this->db->insert('cart', $data);
      $cart_id = $this->db->insert_id();
    }

    $cart_items = $this->db->where('cart_id', $cart_id);
    $cart_items = $this->db->where('is_deleted', 0);
    $cart_items = $this->db->get('cart_item');
    $cart_items = $cart_items->result_array();

    $db_row_ids = [];
    $removed_items = [];

    foreach ($cart_items as $cart_item) {
      if ( ! in_array($cart_item['row_id'], $sess_row_ids)) {
        $removed_items[] = $cart_item['row_id'];
      }

      $db_row_ids[] = $cart_item['row_id'];
    }

    $removed_items_data = [];

    foreach ($removed_items as $row_id) {
      $removed_items_data[] = [
        'row_id' => $row_id,
        'is_deleted' => 1,
      ];
    }

    if ( ! empty($removed_items_data)) {
      $this->db->where('cart_id', $cart_id);
      $this->db->update_batch('cart_item', $removed_items_data, 'row_id');
    }

    $added_items = [];

    foreach ($sess_row_ids as $row_id) {
      if ( ! in_array($row_id, $db_row_ids)) {
        $added_items[] = $row_id;
      }
    }

    if ( ! empty($added_items)) {
      $added_items_data = [];

      foreach ($added_items as $row_id) {
        $item = (array) $this->cart->get_item($row_id);
        $added_items_data[] = [
          'cart_id' => $cart_id,
          'row_id' => $row_id,
          'item_id' => $item['id'],
          'options' => json_encode($item['options']),
          'name' => $item['name'],
          'option_text' => $item['option_text'],
          'thumbnail' => $item['thumbnail'],
          'price' => $item['price'],
          'qty' => $item['qty'],
          'subtotal' => $item['subtotal'],
        ];
      }

      if ( ! empty($added_items_data)) {
        $this->db->insert_batch('cart_item', $added_items_data);
      }
    }


  }

}
