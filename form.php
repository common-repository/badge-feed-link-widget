<div class="badge-feed-link-widget">

  <p>
    <?php Echo $this->t('Title:')?>
    <input type="text" name="<?php Echo $this->get_field_name('title')?>" value="<?php Echo $this->get_option('title') ?>" />
  </p>
  
  <h3><?php Echo $this->t('RSS Icon.')?></h3>
  <p>
    <?php Echo $this->t('Please select the icons which should be displayed by the widget.') ?>
    <?php Echo $this->t('A random one will be choosen on each page load.') ?>
  </p>

  <p>
    <input type="checkbox" class="checkall" />
    <?php Echo $this->t('Choose all icons.') ?>
  </p>

  <table class="rss-icons">
  <?php ForEach ($this->arr_icon AS $icon) : ?>
  <tr>
    <td><input type="checkbox" name="<?php Echo $this->get_field_name('icon')?>[]" value="<?php Echo $icon ?>" <?php Checked(In_Array($icon, (Array) $this->get_option('icon'))) ?> /></td>
    <td><img src="<?php Echo $this->File_to_URL($icon) ?>" alt="RSS Icon" class="rss-icon" /></td>
  </tr>
  <?php EndForEach; ?>
  </table>

  <p>
    <input type="checkbox" class="checkall" />
    <?php Echo $this->t('Choose all icons.') ?>
  </p>
  
  <script type="text/javascript">
  jQuery(document).ready(function(){
    init_badge_feed_link_widget(jQuery);
  });
  </script>

</div>