<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
/**
 * List Group
 */
 .list-group-heading {
   padding: 8px 15px;
 }
 .list-group-heading > * {
   font-weight: bold;
   color: #EB3833
 }

  a:hover {
    text-decoration: none;
  }
  .content-p > * {
    padding: 15px;
  }
  .content-m > * {
    margin: 15px;
  }

  .content-px > * {
    padding-left: 15px;
    padding-right: 15px;
  }
  .content-mx > * {
    margin-left: 15px;
    margin-right: 15px;
  }

  .content-py > * {
    padding-top: 15px;
    padding-bottom: 15px;
  }
  .content-my > * {
    margin-top: 15px;
    margin-bottom: 15px;
  }

  .content-p-between > * {
    padding-top: 15px;
    padding-left: 15px;
  }
  .content-m-between > * {
    margin-top: 15px;
    margin-left: 15px;
  }
  [class*="content-p-between"] > *:first-of-type,
  [class*="content-m-between"] > *:first-of-type {
    padding-top: 0px;
    padding-left: 0px;
    margin-top: 0px;
    margin-left: 0px;
  }

  .content-px-between > * {
    padding-left: 15px;
  }
  .content-mx-between > * {
    margin-left: 15px;
  }
  [class*="content-px-between"] > *:first-of-type,
  [class*="content-mx-between"] > *:first-of-type {
    padding-left: 0px;
    margin-left: 0px;
  }

  .content-py-between > * {
    padding-top: 15px;
  }
  .content-my-between > * {
    margin-top: 15px;
  }
  [class*="content-py-between"] > *:first-of-type,
  [class*="content-my-between"] > *:first-of-type {
    padding-top: 0px;
    margin-top: 0px;
  }

  .content-flex-row {
    display: flex;
    flex-direction: row;
  }
  .content-flex-column {
    display: flex;
    flex-direction: column;
  }

  .content-center {
    align-items: center;
    justify-content: center;
  }

  .content-flex-row.content-v-center {
    align-items: center;
  }
  .content-flex-row.content-h-center {
    justify-content: center;
  }

  .content-flex-column.content-v-center {
    justify-content: center;
  }
  .content-flex-column.content-h-center {
    align-items: center;
  }

  .content-flex-row.content-right {
    justify-content: flex-end;
  }
  .content-flex-column.content-right {
    align-items: flex-end;
  }

  .content-flex-row.content-left {
    justify-content: flex-start;
  }
  .content-flex-column.content-left {
    align-items: flex-start;
  }

  .content-flex-row.content-top {
    align-items: flex-start;
  }
  .content-flex-column.content-top {
    justify-content: flex-start;
  }
  .content-flex-row.content-bottom {
    justify-content: flex-start;
  }
  .content-flex-column.content-bottom {
    align-items: flex-start;
  }

  .text-strike {
    text-decoration: line-through;
  }

  .no-content {
    opacity: 0;
    visibility: hidden;
    padding: 15px;
  }
</style>

<!--- Section Header --->
<header class="section-header sticky-top shadow-sm">
  <nav class="navbar navbar-expand-lg navbar-dark bg-gradient bg-primary">
    <div class="container">
      <a class="navbar-brand" href="<?php echo site_url(); ?>">
        <span class="logo logo-sm">PrintingLab <small class="mr-1">Beta</small></span>
      </a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <!--- Navbar Left --->
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link<?php echo $this->uri->segment(1) == 'home' || empty($this->uri->segment(1)) ? ' active' : false; ?>" href="<?php echo site_url('home'); ?>">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link<?php echo $this->uri->segment(1) == 'catalogue' ? ' active' : false; ?>" href="<?php echo site_url('catalogue'); ?>">Catalogue</a>
          </li>
        </ul><!--- END Navbar Left --->

        <!--- Navber Right --->
        <ul class="navbar-nav ml-auto">
          <?php if ($user) : ?>
            <li class="nav-item ">
              <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">
                <?php echo ucwords($user['username']); ?>'s Account
              </a>
              <div class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item" href="<?php echo site_url('order/manage'); ?>">My Order</a>
                <a class="dropdown-item" href="<?php echo site_url('order/transaction'); ?>">My Transaction</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="<?php echo site_url('account/manage'); ?>">Manage Account</a>
              </div>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('user/logout'); ?>">Logout</a>
            </li>
          <?php else : ?>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('user/sign-up'); ?>">Sign Up</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="<?php echo site_url('user/login'); ?>">Login</a>
            </li>
          <?php endif; ?>
          <style>
            /* Header Widget */
            .header-widget.nav-item {
              position: relative;
            }
            .header-widget.nav-item .nav-link {
              color: rgba(255, 255, 255, 0.8) !important;
            }
            .header-widget.nav-item .nav-link:hover {
              cursor: pointer;
            }
            .header-widget.nav-item .badge {
              position: absolute;
              top: 10px;
              width: 20px;
              right: -10px;
              color: rgba(255, 255, 255, 0.8);
            }
            .header-widget.nav-item:hover .nav-link .badge,
            .header-widget.nav-item .nav-link.active .badge {
              color: #FFFFFF !important;
            }
            .header-widget.nav-item:hover .nav-link .feather,
            .header-widget.nav-item .nav-link.active .feather {
              color: #FFFFFF !important;
              filter: drop-shadow(0px 0px 15px rgba(249, 225, 178, 0.9));
            }
          </style>
          <li class="header-widget nav-item">
            <a class="nav-link" href="#modalSupport" data-toggle="modal" data-dismiss="modal">
              <span class="icon icon-md">
                <i class="fas fa-question-circle" data-feather="help-circle"></i>
              </span>
            </a>
          </li>
          <li class="header-widget nav-item dropdown">
            <a class="nav-link text-white" href="<?php echo site_url('cart'); ?>">
              <span class="icon icon-md">
                <i class="fas fa-shopping-cart" data-feather="shopping-cart"></i>
              </span>
              <span class="cart-total-items badge badge-pill"><?php echo ( ! empty($cart_total_items)) ? $cart_total_items : ''; ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-right has-arrow p-0" style="min-width: 350px; min-height: 150px;" data-autoclose="false">
              <?php if (empty($cart_contents)) : ?>
                <div class="no-content px-4 py-5 d-none flex-column justify-content-center align-items-center">
                  <span class="btn-close"></span>
                  <img class="w-25" src="https://image.flaticon.com/icons/svg/825/825561.svg">
                  <h6 class="text-primary mt-3"><small>No items found here</small></h6>
                </div>
              <?php else : ?>
                <div class="has-content" style="width: 400px;">
                  <h5 class="dropdown-header p-3 d-flex flex-column justify-content-center align-items-center border-bottom">
                    <b class="text-primary">Recently Added Item(s)</b>
                    <?php if (count($cart_contents) > 5) :
                      $cart_item_more = (count($cart_contents) - 5); ?>
                      <small><?php echo $cart_item_more; ?> more item(s) in the cart</small>
                    <?php endif; ?>
                    <span class="btn-close"></span>
                  </h5>
                  <div class="dropdown-body p-2" style="max-height: 250px; overflow-y: auto">
                    <ul class="list-unstyled">
                      <?php $cart_item_count = 0;
                        if ($cart_item_count <= 5) :
                          foreach($cart_contents as $index => $cart_item) :
                            $cart_item_count++;
                      ?>
                        <a href="<?php echo site_url('item/'.$cart_item['id']); ?>" class="list-group-item list-group-item-action px-0" style="border: none; border-radius: 5px">
                          <div class="row m-0 p-0">
                            <div class="col-sm-4 py-0">
                              <img class="w-100 border" <?php echo ( ! empty($cart_item['thumbnail'])) ? 'src="'.$cart_item['thumbnail'].'"' : 'data-src="holder.js/80x80?auto=yes&bg=#F9FAFB"'; ?>>
                            </div>
                            <div class="col-sm-12 text-truncate p-0" style="font-size: 14px">
                              <?php echo $cart_item['name']; ?>
                            </div>
                            <div class="col-sm-6 text-right text-primary p-0" style="font-size: 13px">
                              <b>RM <?php echo $cart_item['subtotal']; ?></b>
                              <p class="text-muted"><?php echo $cart_item['qty']; ?> set(s)</p>
                            </div>
                            <div class="col-sm-2 p-0 text-center">
                              <i data-feather="trash-2"></i>
                            </div>
                          </div>
                        </a>
                      <?php endforeach; endif; ?>
                    </ul>
                  </div>
                  <div class="dropdown-footer p-3">
                    <div class="row p-0 m-0">
                      <div class="col-sm-12 p-0 d-flex align-items-center">
                        Subtotal <span class="text-primary ml-3">RM <?php echo $cart_total; ?></span>
                      </div>
                      <div class="col-sm-12 p-0">
                        <a href="<?php echo site_url('cart'); ?>" class="btn btn-block btn-primary text-white bg-gradient bg-orange">View Shopping Cart</a>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endif; ?>
            </div>
          </li><!--- END Shopping Cart Widget --->
        </ul><!--- Navbar Right --->
      </div>
    </div>
  </nav>
</header><!--- END Section Header --->
