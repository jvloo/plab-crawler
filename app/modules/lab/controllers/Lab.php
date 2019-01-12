<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Lab extends Base_Controller
{
  public function __construct()
  {
    parent::__construct();

    $this->load->config('address');
  }

  /**
   * States Crawler (Manual)
   *
   * @see    /crawler/address
   */
  public function index()
  {
    $this->load->helper('file');

    if ( ! file_exists(ROOTPATH.'tmp/data/states/states.json')) {
      /**
       * Explode States
       */
      $url = 'https://postcode.my/browse/';
      $raw_html = file_get_contents($url);

      $str = str_replace("\n", '', $raw_html);
      $re = '/<div class="media">(.*?)<p class="hidden-xs"><small><\/small><\/p><\/div>/';
      preg_match_all($re, $str, $matches);

      if (empty($matches[1])) {
        echo '<h3>No states found.</h3>';
        exit;
      }

      $states = [];
      foreach ($matches[1] as $state) {
        $re = '/href="(.*?)" title/';
        preg_match($re, $state, $link);

        $re = '/title="(.*?)"><img/';
        preg_match($re, $state, $name);

        if ( ! empty($link[1]) && ! empty($name[1])) {
          $states[] = [
            'name' => $name[1],
            'link' => $link[1],
          ];
        }
      }

      is_dir(ROOTPATH.'tmp/data/states') OR mkdir(ROOTPATH.'tmp/data/states');
      write_file(ROOTPATH.'tmp/data/states/states.json', json_encode($states));
    }

    echo '<h3>Explode Postcodes of State:</h3>';

    $states = json_decode(read_file(ROOTPATH.'tmp/data/states/states.json'), true);
    foreach ($states as $index => $state) {
      echo anchor('lab/explode-postcodes-by-state/?state='.$state['name'].'&link='.$state['link'], $state['name'], 'target="_blank"').'<br>';
    }
  }

  /**
   * Postcodes Crawler (Manual)
   *
   * @see    /crawler/address
   */
  public function explode_postcodes_by_state() {
    $this->load->helper('file');

    $state = mb_strtolower(str_replace(' ', '-', $this->input->get('state')));
    $link = $this->input->get('link');

    if ( ! file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes.json')) {
      $url = 'https://postcode.my'.$link;
      $raw_html = file_get_contents($url);

      $str = str_replace("\n", '', $raw_html);
      $re = '/<div class="media">(.*?)<\/h4><\/div><\/div>/';
      preg_match_all($re, $str, $matches);

      $postcodes = [];

      if (empty($matches[1])) {
        echo '<h3>No postcode found for state: '.$state['name'].'</h3>';
        exit;
      } else {
        foreach ($matches[1] as $postcode) {
          $re = '/href="(.*?)" title/';
          preg_match($re, $postcode, $link);

          $re = '/title="(.*?)"><img/';
          preg_match($re, $postcode, $name);

          if ( ! empty($link[1]) && ! empty($name[1])) {
            $postcodes[] = [
              'name' => $name[1],
              'link' => $link[1],
            ];
          }
        }
      }

      is_dir(ROOTPATH.'tmp/data/states/'.$state) OR mkdir(ROOTPATH.'tmp/data/states/'.$state);
      write_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes.json', json_encode($postcodes));
    }

    echo '<h3>Explode Information of Postcode:</h3>';

    $postcodes = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes.json'), true);
    foreach ($postcodes as $index => $postcode) {
      echo ($index + 1).'. '.anchor('lab/explode-postcode/?state='.$state.'&postcode='.$postcode['name'].'&link='.$postcode['link'], $postcode['name'], 'target="_blank"').'&nbsp;&nbsp;';
    }
  }

  /**
   * Postcode Information Crawler (Manual)
   *
   * @see    /crawler/address
   */
  public function explode_postcode()
  {
    $this->load->helper('file');

    $state = mb_strtolower(str_replace(' ', '-', $this->input->get('state')));
    $postcode = $this->input->get('postcode');
    $link = $this->input->get('link');

    $page = $this->input->get('page');

    if (empty($page)) {
      if ( ! file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json')) {
        $url = 'https://postcode.my'.$link;
        $status = file_get_contents($url);

        $raw_html = str_replace("\n", '', $status);
        $re = '/<ul class="pagination">(.*?)<\/ul>/';
        preg_match($re, $raw_html, $pagination);

        if (empty($pagination[1])) {
          echo 'Pages not found for postcode: '.$state.' - '.$postcode;
          exit;
        }

        $re = "/\?page=(\d+)'>/";
        preg_match_all($re, $pagination[1], $pages);

        if ( ! empty($pages[1])) {
          end($pages[1]);

          $last = key($pages[1]);
          $last_page = $pages[1][$last];
        } else {
          $last_page = 1;
        }

        $result = [];
        $page = 1;
        while ($page <= $last_page) {
          $result[] = $page;
          $page++;
        }

        write_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json', json_encode($result));

        $pages = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json'), true);

        echo '<h3>Explode Information of Postcode:</h3>';

        foreach ($pages as $page) {
          echo anchor('lab/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page, 'page '.$page.' of '.$last_page, 'target="_blank"').'<br>';
        }
      } else {
        $pages = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json'), true);

        echo '<h3>Explode Information of Postcode:</h3>';

        foreach ($pages as $page) {
          echo anchor('lab/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page, 'page '.$page, 'target="_blank"').'<br>';
        }
      }
    } else {
      if ( ! file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json')) {
        $url = 'https://postcode.my'.$link.'?page='.$page;
        $status = file_get_contents($url);

        $raw_html = str_replace("\n", '', $status);
        $re = '/<td><strong>(.*?)<\/strong><\/td><\/tr>/';
        preg_match_all($re, $raw_html, $information);

        if (empty($information[1])) {
          echo '<h3>No information found for postcode: '.$postcode.'</h3>';
          exit;
        }

        $result = [];

        foreach ($information[1] as $info) {
          $re = '/title="(.*?)">/';
          preg_match_all($re, $info, $raw_result);

          if ( ! empty($raw_result[1])) {
            list($location, $post_office, $state, $postcode) = $raw_result[1];

            $result[] = [
              'location' => $location,
              'post_office' => $post_office,
              'state' => $state,
              'postcode' => $postcode,
            ];
          }
        }

        $state = mb_strtolower(str_replace(' ', '-', $state));

        if ( ! empty($result)) {
          echo 'Success: Information found for postcode: '.$state.' - '.$postcode.' (Page '.$page.')<br>';

          $save_file = false;

          if (file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json')) {
            $old_result = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json'), true);
            $new_result = $this->array_diff_multi($old_result, $result);

            if (empty($new_result)) {
              $save_file = false;
            } else {
              $result = array_merge($old_result, $new_result);
            }
          } else {
            $save_file = true;
          }

          if ($save_file) {
            write_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json', json_encode($result));

            $info = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json'), true);
            print_r($info);
          } else {
            echo 'Result Duplicated. File is not saved for postcode: '.$state.' - '.$postcode.' (Page '.$page.')<br><br>';
            $info = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json'), true);
            print_r($info);
            exit;
          }
        }
      } else {
        echo 'Error: File exists for '.$state.' - '.$postcode.' (Page '.$page.')<br><br>';
        $info = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json'), true);
        print_r($info);
        exit;
      }
    }
  }

  /**
   * Differentiate Two Multidimensional Arrays
   * https://github.com/rogervila/array-diff-multidimensional/blob/master/src/ArrayDiffMultidimensional.php
   * @method array_diff_multi
   * @param  [type]           $array1 [description]
   * @param  [type]           $array2 [description]
   * @return [type]           [description]
   */
  private function array_diff_multi($array1, $array2)
  {
    $result = array();
    foreach ($array1 as $key => $value) {
      if (!is_array($array2) || !array_key_exists($key, $array2)) {
        $result[$key] = $value;
        continue;
      }
      if (is_array($value)) {
        $recursiveArrayDiff = $this->array_diff_multi($value, $array2[$key]);
        if (count($recursiveArrayDiff)) {
          $result[$key] = $recursiveArrayDiff;
        }
        continue;
      }
      if ($value != $array2[$key]) {
        $result[$key] = $value;
      }
    }
    return $result;
  }

}
