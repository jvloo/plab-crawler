<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <?php echo ! empty($meta) ? $meta : false; ?>

  <title><?php echo ! empty($title) ? $title : false; ?></title>

  <?php echo ! empty($stylesheet) ? $stylesheet : false; ?>

  <script src="<?php echo asset_url('vendor/jquery/3.3.1/jquery.min.js'); ?>"></script>
</head>
<body class="bg-light">
  <?php echo ! empty($header) ? $header : false; ?>
  <?php echo ! empty($content) ? $content : false; ?>
  <?php echo ! empty($footer) ? $footer : false; ?>

  <?php echo ! empty($javascript) ? $javascript : false; ?>

</body>
</html>
