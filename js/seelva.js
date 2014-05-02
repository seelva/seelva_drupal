(function($){
  Drupal.behaviors.seelva = {
    attach:function(context, settings) {
      $('.messages .btn-close').click(function(e) {
      	e.preventDefault();
        $(this).closest('.messages').fadeOut();
      });
    }
  }
}(jQuery));
