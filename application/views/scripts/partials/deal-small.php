<li>
    <h4><?php echo stripslashes($this->name); ?></h4> 
    
    <p><?php echo stripslashes($this->description); ?></p>
    
    <div>
    	<p class="when left">
    		<?php 
    			foreach ($this->days as $day) {
    				echo $day->getDayWording() . ' ';
    			}
    			echo $this->timeStart->format("H:i") . ' - ' . $this->timeEnd->format('H:i'); ?>
    	</p>
    	
    	<a class="right reviews addDealReview" href="<?php echo $this->baseUrl('review/deal/id/' . $this->id);?>">
    		<?php echo $this->reviews->count(); ?>
    	</a>
    	<?php //if ($this->idUserSubmitted == Zend_Auth::getInstance()->getIdentity()->id): ?>
    	<a class="right" href="<?php echo $this->baseUrl('venue/new-deal/id/' . $this->id . '/idVenue/' . $this->venue->id);?>"><img class="icon" src="<?php echo $this->baseUrl('/img/icons/social/edit.png');?> " /></a> 
    	<?php //endif;?>
    	<a class="right icon-small flag"     href="#" title="Flag deal">Flag deal</a>
    	<a class="right icon-small favorite" href="#" title="Mark deal as favorite">Mark deal as favorite</a>
    	<a class="right icon-small twitter"  href="#" title="Share deal on Twitter">Share deal on Twitter</a>
    	<a class="right icon-small facebook" href="#" title="Share deal on Facebook">Share deal on Facebook</a>
    </div>
    <div class="clear separator"></div>
</li>

