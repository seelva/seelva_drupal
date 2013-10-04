(function($){
  Drupal.behaviors.seed = {
    attach:function(context, settings) {
      $('.messages .btn-close').click(function(e) {
        $(this).closest('.messages').fadeOut();
      });
    }
  }
}(jQuery));
