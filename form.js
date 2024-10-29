function init_badge_feed_link_widget($){
  var $widget = $('.widget .badge-feed-link-widget');
  
  $widget.find('input.checkall').change(function(){
    var $this = $(this);
    $widget.find('table.rss-icons input:checkbox').attr('checked', $this.attr('checked'));
  });
  
  $widget.find('table.rss-icons input:checkbox').change(function(){
    var $this = $(this);
    if (!$(this).is(':checked'))
      $widget.find('input.checkall').attr('checked', false);
  });

}