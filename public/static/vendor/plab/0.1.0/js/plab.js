$(document).ready(function() {
  // Initiate Popper.js
  $('[data-toggle="tooltip"]').tooltip();

  // Initiate Feather Icons
  feather.replace();
});

/**
 * Bootstrap Elements Interaction
 */
 $(document).ready(function() {
   /**
    * Collapse Event
    */
     $('[data-toggle=collapse]').on('click', function() {
       var collapseItem = $(this).attr('data-target');
       if ($(collapseItem).hasClass('show') === false) {
         $(this).addClass('active');
       } else {
         $(this).removeClass('active');
       }
     });

     // Handle collapse items
     $('.collapse').on('show.bs.collapse', function () {
       $(this).siblings('.collapsible').addClass('active');
     });
     $('.collapse').on('hide.bs.collapse', function () {
       $(this).removeClass('active');
       $(this).siblings('.collapsible').removeClass('active');
     });

     // Handle collapse items FOR USER MENU
     $('.user-menu.collapse').on('show.bs.collapse', function () {
       $(this).siblings('.user-name').addClass('active');
       $('.user-menu-toggle').addClass('active');
     });
     $('.user-menu.collapse').on('hide.bs.collapse', function () {
       $(this).siblings('.user-name').removeClass('active');
       $('.user-menu-toggle').removeClass('active');
     });

   /**
    * Dropdown Event
    */
     // Handle dropdown toggler active state
     $('[data-toggle=dropdown]').on('click', function() {
       var menu = $(this).siblings('.dropdown-menu');
       // If dropdown menu has been opened, remove active state from the toggler
       if (menu.hasClass('show') === true) {
         $(this).removeClass('active');
       } else { // If dropdown menu not opened yet, add active state to the toggler
         $(this).addClass('active');
       }

       // Remove active state of other dropdown toggler
       $(this).parents().find('[data-toggle=dropdown]').each(function() {
         if ( $(this).siblings('.dropdown-menu').hasClass('show') ) {
           $(this).removeClass('active');
         }
       });

       // If dropdown toggler has link, navigate to the link
       if ( $(this)[0].hasAttribute('href') ) {
         var url = $(this).attr('href');
         window.location.href = url;
       }
     });

     // Remove active state of all dropdown togglers,
     // except their menu is shown
     $('body').on('click', function() {
       $(this).find('[data-toggle=dropdown]').each(function() {
         if ( $(this).siblings('.dropdown-menu').hasClass('show') ) {
           $(this).removeClass('active');
         }
       });
     });

     // Handle dropdown menu close event
     $('.dropdown-menu span.btn-close').on('click', function() {
       $(this).parents('.dropdown-menu').removeClass('show');
       $(this).parents('.dropdown-menu').siblings('[data-toggle=dropdown]').removeClass('active');
     });
     // Prevent dropdown menu auto-close event
     $('.dropdown-menu[data-autoclose=false]').on('click', function(e) {
       e.stopPropagation();
     });
 });

/**
 * PrintingLab Micro-Interaction
 */
 $(document).ready(function() {
   //$('html, body').animate({ scrollTop: 0 }, 500);
   // Logo animation on hover
   $('.logo.animated').hover(function() {
     if ( $('.logo.animated').hasClass('rubberBand') !== true ) {
       $('.logo.animated').addClass('rubberBand');
     }
   }, function() {
     if ( $('.logo.animated').hasClass('rubberBand') === true ) {
       setTimeout(function(){
         $('.logo.animated').removeClass('rubberBand');
       }, 1000);
     }
   });
 });
