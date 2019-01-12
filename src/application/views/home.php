<?php
defined('BASEPATH') or exit('No direct script access allowed.');
?>
<!--- HOME PAGE --->
<style>
  /* Content Header */
  .content-header-banner {
    overflow: hidden;
    padding: 0px;
  }
  .content-header-banner .carousel .carousel-control-prev,
  .content-header-banner .carousel .carousel-control-next {
    top: 35%;
    width: 3%;
    height: 25%;
    background: rgba(0, 0, 0, 0.2);
    opacity: 0;
    visibility: hidden;
  }
  .content-header-banner .carousel:hover .carousel-control-prev,
  .content-header-banner .carousel:hover .carousel-control-next {
    opacity: 1;
    visibility: visible;
    transition: opacity 0.5s ease;
  }

  .content-header-banner .carousel .carousel-indicators {
    bottom: 0;
  }
  .content-header-banner .carousel .carousel-indicators li {
    border-radius: 50%;
    height: 10px;
    width: 10px;
  }
  .content-header-banner .carousel .carousel-indicators li.active {
    background: #EB3833;
  }

  /* Section Category */
  .section-category {
    border-radius: 0;
    overflow: hidden;
  }
  .section-category .card-group {
    flex-flow: row wrap;
  }
  .section-category .card-group > .card {
    margin-bottom: 0;
  }
  .section-category .card-group > .card + .card {
    margin-left: 0;
    border-left: 0;
  }
  .section-category .card-group > .card,
  .section-category .card-group > .card:first-child,
  .section-category .card-group > .card:last-child,
  .section-category .card-group > .card:only-child,
  .section-category .card-group > .card .card-img,
  .section-category .card-group > .card:first-child .card-img,
  .section-category .card-group > .card:last-child .card-img,
  .section-category .card-group > .card:only-child .card-img {
    border-radius: 0;
  }
  @media (min-width: 576px) {
    .section-category {
      border-radius: .25rem;
    }
    .section-category .card-group > .card:first-child,
    .section-category .card-group > .card:first-child .card-img {
      border-top-left-radius: .25rem;
      border-bottom-left-radius: .25rem;
    }
    .section-category .card-group > .card:last-child,
    .section-category .card-group > .card:last-child .card-img {
      border-top-right-radius: .25rem;
      border-bottom-right-radius: .25rem;
    }
    .section-category .card-group > .card-divider + .card,
    .section-category .card-group > .card-divider + .card .card-img {
      border-top-left-radius: .25rem !important;
      border-bottom-left-radius: .25rem !important;
    }
  }
</style>
  <!--- Content Topbar --->
  <section class="content-topbar pt-sm-3 pt-0"></section><!--- END Content Topbar --->

  <!--- Content Header --->
  <header class="content-header pt-sm-3 pt-0">
    <div class="container">
      <div class="row">
        <!--- Content Header Left --->
        <div class="content-header-left col-sm-4 pl-sm-0 px-2">
          <nav class="card shadow-sm h-100 d-sm-block d-none">
            <ul class="list-group list-group-flush">
              <li class="list-group-heading">
                <small>TOP CATEGORIES</small>
              </li>
            </ul>
          </nav>
        </div><!--- END Content Header Left --->
        <!--- Content Header Right --->
        <div class="content-header-right col-sm">
          <div class="row">

            <!--- Content Header Banner --->
            <div class="content-header-banner col-sm bg-white border rounded">
              <div id="carouselHeroBanner" class="carousel slide" data-ride="carousel">
                <div class="carousel-inner">
                  <div class="carousel-item active">
                    <img class="d-block w-100" src="<?php echo asset_url('img/banner/hero/home/1920x600.png'); ?>" alt="First slide">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100" data-src="holder.js/1920x600?auto=yes&text=1920x600&bg=FA5656&fg=EB3833" alt="Second slide">
                  </div>
                  <div class="carousel-item">
                    <img class="d-block w-100" data-src="holder.js/1920x600?auto=yes&text=1920x600&bg=FFEFED&fg=EB3833" alt="Third slide">
                  </div>
                </div>
                <!--- Carousel Controls --->
                <a class="carousel-control-prev" href="#carouselHeroBanner" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselHeroBanner" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
                <ol class="carousel-indicators">
                  <li data-target="#carouselHeroBanner" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselHeroBanner" data-slide-to="1"></li>
                  <li data-target="#carouselHeroBanner" data-slide-to="2"></li>
                </ol>
                <!--- END Carousel Controls --->
              </div>

              <div class="carousel-bottom-panel px-4 d-sm-block d-none">
                <div class="row">
                  <div class="col-sm">
                    <figure class="media h-100 d-flex align-items-center justify-content-center">
                      <img class="w-25 align-self-center" src="<?php echo asset_url('img/brand/service-highlight/instant-quotation.svg'); ?>">
                      <figcaption class="media-body p-2">
                        <h6 class="text-center text-primary">Instant Quotation</h6>
                      </figcaption>
                    </figure>
                  </div>
                  <div class="col-sm">
                    <figure class="media h-100 d-flex align-items-center justify-content-center">
                      <img class="w-25 align-self-center" src="<?php echo asset_url('img/brand/service-highlight/quick-purchase.svg'); ?>">
                      <figcaption class="media-body p-2">
                        <h6 class="text-center text-primary">Quick Purchase</h6>
                      </figcaption>
                    </figure>
                  </div>
                  <div class="col-sm">
                    <figure class="media h-100 d-flex align-items-center justify-content-center">
                      <img class="w-25 align-self-center" src="<?php echo asset_url('img/brand/service-highlight/easy-tracking.svg'); ?>">
                      <figcaption class="media-body p-2">
                        <h6 class="text-center text-primary">Easy Tracking</h6>
                      </figcaption>
                    </figure>
                  </div>
                  <div class="col-sm">
                    <figure class="media h-100 d-flex align-items-center justify-content-center">
                      <img class="w-25 align-self-center" src="<?php echo asset_url('img/brand/service-highlight/fast-delivery.svg'); ?>">
                      <figcaption class="media-body p-2">
                        <h6 class="text-center text-primary">Fast Delivery</h6>
                      </figcaption>
                    </figure>
                  </div>
                  <div class="col-sm">
                    <figure class="media h-100 d-flex align-items-center justify-content-center">
                      <img class="w-25 align-self-center" src="<?php echo asset_url('img/brand/service-highlight/after-sales-support.svg'); ?>">
                      <figcaption class="media-body p-2">
                        <h6 class="text-center text-primary"><span class="text-nowrap">After-Sales</span> Support</h6>
                      </figcaption>
                    </figure>
                  </div>
                </div>
              </div>
            </div><!--- END Content Header Banner --->

            <!--- TODO: Content Header Widget --->
            <aside class="content-header-widget col-sm-5 pr-0 d-none"></aside><!--- END Content Header Widget --->
            <!--- TODO： Great Deals/Trending --->
            <article class="content-header-section col-sm-24 p-sm-0 p-2 d-none"></article>
          </div>
        </div><!--- END Content Header Right --->
      </div>
    </div>
  </header><!--- END Content Header --->

  <!--- Content Body --->
  <main class="content-body pt-sm-3 pt-0">
    <div class="container p-0">

      <?php if ( ! empty($categories) ) : foreach ($categories as $category) : ?>
      <article id="section-category-<?php echo $category['category_id']; ?>" class="section-category bg-white shadow-sm mt-sm-5 mt-0">
        <div class="row no-gutters">

          <div class="col-sm-6 px-0">
            <div class="bg-gradient bg-primary h-100 p-3">
              <h4 class="text-white m-0"><?php echo $category['name']; ?></h4>
            </div>
          </div>

          <div class="col-sm-18 p-sm-3 p-0">
            <div class="card-group">
              <?php if ( ! empty($category['shops']) ) : foreach ($category['shops'] as $tab_index => $tab) :?>
                <?php if ( ! empty($tab) ) : end($tab); $last_row = key($tab); foreach ($tab as $row_index => $row) : ?>
                  <?php if ( ! empty($row) ) : foreach ($row as $shop_index => $shop) : ?>

                  <a class="card col-sm col-8 p-0" href="#category-<?php echo $category['category_id']; ?>-shop-<?php echo $shop['shop_id']; ?>-items" data-toggle="modal" data-shop="<?php echo $shop['shop_id']; ?>" style="padding: 0px; max-height: 250px;<?php echo ( ! empty($shop['color']) ) ? ' background-color: ' . $shop['color'] . ';' : ''; ?>">
                    <img class="card-img" style="object-fit: contain" src="<?php echo $shop['thumbnail']; ?>">
                  </a>

                  <div class="modal fade" id="category-<?php echo $category['category_id']; ?>-shop-<?php echo $shop['shop_id']; ?>-items" tabindex="-1" role="dialog" aria-labelledby="modalCategoryLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                      <div class="modal-content p-0">
                        <div class="modal-header">
                          <h5 class="modal-title text-primary px-3" id="exampleModalLabel">Browse Items in <?php echo $shop['name']; ?></h5>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </div>
                        <div class="card-body d-flex flex-column align-items-center">
                          <?php if (empty($shop['items'])) : ?>
                            <div class="no-items p-5">
                              <h6 class="text-primary text-dark">No items found</h6>
                            </div>
                          <?php else : ?>
                            <div class="has-items row w-100 flex-wrap">
                              <?php foreach($shop['items'] as $item) : ?>
                                <div class="col-sm-6">
                                  <a href="<?php echo site_url('item/'.$item['slug'].'-i'.$item['item_id'].'.html'); ?>" class="card card-body p-0" style="background: #F1EFEA;">
                                    <img class="card-img-top w-100 h-100" src="<?php echo asset_url($item['thumbnail_image']); ?>">
                                    <h6 class="m-2"><?php echo $item['name']; ?></h6>
                                  </a>
                                </div>
                              <?php endforeach; ?>
                            </div>
                          <?php endif; ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <?php endforeach; endif; ?>
                  <?php if ($row_index !== $last_row) : ?>
                    <div class="card-divider w-100 p-2 d-sm-block d-none"></div>
                  <?php endif; ?>
                <?php endforeach; endif; ?>
              <?php endforeach; endif; ?>




              <a class="card col-sm-6 col-8 p-0 d-sm-none d-flex">
                <img class="card-img" style="object-fit: contain" src="https://via.placeholder.com/250x300/F1EFEA/EB3833/?text=+" data-src="http://localhost/printinglabmy/www/static/common/img/catalogue/display-system/roll-up-bunting.png">
              </a>
            </div>
          </div>

        </div>
      </article>
    <?php endforeach; endif; ?><!--- END Category Section --->


    </div>
  </main><!--- END Content Body --->

  <!--- Content Footer --->
  <footer class="content-footer"></footer><!--- END Content Footer --->

  <footer class="mobile-content-footer d-sm-none d-block text-center px-2 py-3">
    <button class="btn btn-sm btn-block btn-outline-primary">Load More</button>
    <small class="text-primary font-weight-bold d-none">End of Content</small>
  </footer>
<!--- END HOME PAGE --->
