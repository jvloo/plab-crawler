<?php
defined('BASEPATH') OR exit('No direct script access allowed.');

class Address extends MY_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->helper('file');

    set_time_limit(43200);
    ini_set('max_execution_time', 43200);
  }

  public function index()
  {
    echo '<h3>'.anchor('lab/crawler/address/crawl', 'Start Crawling').'</h3><br>';
  }

  /**
   * Crawler Main Functions
   */
  protected $proceed = 30; // Sleep time (s) before proceed to next process
  protected $retry = 60; // Sleep time (s) before retrying process

  public function crawl()
  {
    log_message('error', 'Fetching states...');

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
        log_message('error', 'Fail: States not found.');
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
      $this->save_json(ROOTPATH.'tmp/data/states/states.json', $states);
    }

    $states = json_decode(read_file(ROOTPATH.'tmp/data/states/states.json'), true);

    log_message('error', 'Success: States found.');

    foreach ($states as $index => $state) {
      log_message('error', 'Preparing to fetch postcodes of state: '.$state['name']);

      $url = site_url('lab/crawler/address/explode-postcodes-by-state/?state='.$state['name'].'&link='.$state['link']);
      $status = file_get_contents($url);

      while ($status === false) {
        log_message('error', 'Retrying...');

        sleep(20);
        $status = file_get_contents($url);
      }

      log_message('error', 'Success: Postcodes found for state: '.$state['name']);

      //echo anchor('lab/crawler/address/explode-postcodes-by-state/?state='.$state['name'].'&link='.$state['link'], $state['name'], 'target="_blank"').'<br>';
    }
  }

  // http://localhost/plab-base/public/lab/crawler/address/explode-postcodes-by-state/?state=johor&link=/browse/johor/
  public function explode_postcodes_by_state()
  {
    $this->load->helper('file');

    $state = strtolower(str_replace(' ', '-', $this->input->get('state')));
    $link = $this->input->get('link');

    if ( ! file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes.json')) {
      log_message('error', 'Fetching postcodes of state: '.$state);

      $url = 'https://postcode.my'.$link;
      $raw_html = file_get_contents($url);

      $str = str_replace("\n", '', $raw_html);
      $re = '/<div class="media">(.*?)<\/h4><\/div><\/div>/';
      preg_match_all($re, $str, $matches);

      $postcodes = [];

      if (empty($matches[1])) {
        log_message('error', 'Fail: No postcode found for state: '.$state);
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
      $this->save_json(ROOTPATH.'tmp/data/states/'.$state.'/postcodes.json', $postcodes);
    }

    $postcodes = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes.json'), true);

    log_message('error', 'Success: postcodes of state: '.$state. ' found.');

    foreach ($postcodes as $index => $postcode) {
      log_message('error', 'Preparing to fetch postcodes information of state: '.$state);

      $url = site_url('lab/crawler/address/explode-postcode/?state='.$state.'&postcode='.$postcode['name'].'&link='.$postcode['link']);
      $status = file_get_contents($url);

      while ($status === false) {
        log_message('error', 'Retrying...');

        sleep($this->retry);
        $status = file_get_contents($url);
      }

      log_message('error', 'Success: Postcodes information found for state: '.$state);

      //echo ($index + 1).'. '.anchor('lab/explode-postcode/?state='.$state.'&postcode='.$postcode['name'].'&link='.$postcode['link'], $postcode['name'], 'target="_blank"').'&nbsp;&nbsp;';
    }
  }

  public function explode_postcode()
  {
    $this->load->helper('file');

    $state = strtolower(str_replace(' ', '-', $this->input->get('state')));
    $postcode = $this->input->get('postcode');
    $link = $this->input->get('link');

    $page = $this->input->get('page');

    if (empty($page)) {
      // Fetch pages information
      if ( ! file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json')) {
        log_message('error', 'Processing: Fetching pages of '.$state.' - '.$postcode);

        //sleep($this->proceed);

        $url = 'https://postcode.my'.$link;
        $status = file_get_contents($url);
        while ($status === false) {
          log_message('error', 'Retrying...');

          sleep($this->retry);
          $status = file_get_contents($url);
        }

        $raw_html = str_replace("\n", '', $status);
        $re = '/<ul class="pagination">(.*?)<\/ul>/';
        preg_match($re, $raw_html, $pagination);

        while (empty($pagination[1])) {
          log_message('error', 'Retrying: Searching for pagination wrappers of '.$state.' - '.$postcode);

          sleep($this->retry);

          $status = file_get_contents($url);
          while ($status === false) {
            log_message('error', 'Retrying...');

            sleep($this->retry);
            $status = file_get_contents($url);
          }

          $raw_html = str_replace("\n", '', $status);
          $re = '/<ul class="pagination">(.*?)<\/ul>/';
          preg_match($re, $raw_html, $pagination);
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

        log_message('error', 'Success: Totally '.$last_page.' pages found for '.$state.' - '.$postcode);

        $result = [];
        $page = 1;
        while ($page <= $last_page) {
          $result[] = $page;
          $page++;
        }

        $this->save_json(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json', $result);

        $pages = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json'), true);

        foreach ($pages as $page) {
          log_message('error', 'Preparing: Fetch information of '.$state.' - '.$postcode.' (Page '.$page.')');

          if (file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json')) {
            log_message('error', 'Error (Checkpoint 1): File exists for '.$state.' - '.$postcode.' (Page '.$page.')');
            continue;
          }

          $url = site_url('lab/crawler/address/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page);
          $status = file_get_contents($url);
          //echo anchor('lab/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page, 'page '.$page.' of '.$last_page, 'target="_blank"').'<br>';

          while ($status === false) {
            log_message('error', 'Retrying...');
            sleep($this->retry);

            $status = file_get_contents($url);
          }
        }

        exit;
      } else {
        $pages = json_decode(read_file(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'.json'), true);

        log_message('error', 'Success: Pages found for '.$state.' - '.$postcode);

        foreach ($pages as $page) {
          log_message('error', 'Preparing: Fetch information of '.$state.' - '.$postcode.' (Page '.$page.')');

          if (file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json')) {
            log_message('error', 'Error (Checkpoint 2): File exists for '.$state.' - '.$postcode.' (Page '.$page.')');
            continue;
          }

          $url = site_url('lab/crawler/address/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page);
          $status = file_get_contents($url);
          //echo anchor('lab/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page, 'page '.$page.' of '.$last_page, 'target="_blank"').'<br>';

          while ($status === false) {
            log_message('error', 'Retrying...');
            sleep($this->retry);

            $status = file_get_contents($url);
          }
        }

        exit;
      }
    } else {

      if ( ! file_exists(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json')) {
        sleep($this->proceed);
        log_message('error', 'Processing: Fetching information of '.$state.' - '.$postcode.' (Page '.$page.')');

        $url = 'https://postcode.my'.$link.'?page='.$page;
        $status = file_get_contents($url);

        while ($status === false) {
          log_message('error', 'Retrying...');
          sleep($this->retry);

          $status = file_get_contents($url);
        }

        $raw_html = str_replace("\n", '', $status);

        $re = '/<td><strong>(.*?)<\/strong><\/td><\/tr>/';
        preg_match_all($re, $raw_html, $information);

        while (empty($information[1])) {
          log_message('error', 'Fail: No information found for '.$state.' - '.$postcode.' (Page '.$page.')');
          log_message('error', 'Link: '.site_url('lab/crawler/address/explode-postcode/?state='.$state.'&postcode='.$postcode.'&link='.$link.'&page='.$page));
          log_message('error', 'Retrying...');

          sleep($this->retry);

          $status = file_get_contents($url);

          while ($status === false) {
            log_message('error', 'Retrying...');
            sleep($this->retry);

            $status = file_get_contents($url);
          }

          $raw_html = str_replace("\n", '', $status);
          $re = '/<td><strong>(.*?)<\/strong><\/td><\/tr>/';
          preg_match_all($re, $raw_html, $information);
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

        $state = strtolower(str_replace(' ', '-', $state));

        if ( ! empty($result)) {
          log_message('error', 'Success: Information found for '.$state.' - '.$postcode.' (Page '.$page.')');

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
            $this->save_json(ROOTPATH.'tmp/data/states/'.$state.'/postcodes/'.$postcode.'--'.$page.'.json', $result);
          } else {
            log_message('error', 'Error: Result Duplicated. File is not saved for '.$state.' - '.$postcode.' (Page '.$page.')');
          }
        }
      } else {
        log_message('error', 'Error (Checkpoint 3): File exists for '.$state.' - '.$postcode.' (Page '.$page.')');
        exit;
      }
    }
  }

  /**
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

  private function save_json($path, $data, $mode = 'wb')
  {
    $data = json_encode($data);

    if ( ! $fp = @fopen($path, $mode))
    {
      return FALSE;
    }

    flock($fp, LOCK_EX);

    for ($result = $written = 0, $length = strlen($data); $written < $length; $written += $result)
    {
      if (($result = fwrite($fp, substr($data, $written))) === FALSE)
      {
        break;
      }
    }

    flock($fp, LOCK_UN);
    fclose($fp);

    if (file_exists($path)) {
      log_message('error', 'Success: Saved JSON.');
    }

    return is_int($result);
  }
}
