<h3 class="popup-title"><?php echo $this->venue->name; ?> - Reviews</h3>

<?php if($this->venue->reviews && $this->venue->reviews->count()) { ?>
	<div>
		<ul>
			<?php echo $this->partialLoop('/partials/review.php', $this->venue->reviews); ?>
		</ul>
	</div>
<?php } else { ?>
    <div class="noContent">Nobody has reviewed this venue yet...</div>
<?php } ?>

<?php
	if (Zend_Auth::getInstance()->hasIdentity()) { ?>
		<p>Don't be shy, submit a review!</p>
	<?php echo $this->form;
	} else { ?>
	<hr />
	<div>
		<p>You need to be logged in to submit a review. <a href="<?php echo $this->baseUrl('/index/login'); ?>">log in</a> 
		or <a href="<?php echo $this->baseUrl('/index/register'); ?>">register</a>
	</div>	
		
<?php } ?>
