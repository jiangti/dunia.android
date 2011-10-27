<li>
	<h4><a href="<?php echo $this->baseUrl('/user/id/' . $this->author->id); ?>"><?php echo $this->author->formatName(); ?></a> said:</h4>
    <blockquote><span class="bqstart">&#8220;</span><?php echo stripslashes($this->description); ?><span class="bqend">&#8221;</span></blockquote>

    <?php 
    $type = isset($this->deal) ? 'deal' : 'venue';
    if(Zend_Auth::getInstance()->hasIdentity() && $this->author->id == Zend_Auth::getInstance()->getIdentity()->id): ?>
    	<a href="<?php echo sprintf('/review/delete-' . $type . '/id/%s/idReview/%s', $this->$type->id, $this->id);?>">delete</a>
    <?php endif;?>
    <hr />
</li>
