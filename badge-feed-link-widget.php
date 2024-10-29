<?php Echo $args['before_title'].$this->get_option('title').$args['after_title']; ?>

<a href="<?php Echo Get_BlogInfo('rss2_url') ?>">
  <img src="<?php Echo $this->File_to_URL($this->get_option('icon')) ?>" alt="RSS 2.0" />
</a>