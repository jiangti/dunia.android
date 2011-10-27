<h2 class="venue"><?php echo $this->venue->name; ?></h2>
<div id="venueInfo">
	<?php 
		if($this->venue->address) { 
			echo $this->partial('/partials/map.php',
				array('options' => array(
					'address' => $this->venue->address,
					'width'	  => 400,
					'height'  => 300,
					)
				)
			); 
		}?>
						
	<div id="address">
		<?php 
			if($this->venue->address) :
				echo $this->venue->address->format();
			?>
				<a href="<?php echo $this->baseUrl('venue/add-address/idVenue/' . $this->venue->id);?>">Edit</a>
				
			<?php 
			else : 
			?>
				<a href="<?php echo $this->baseUrl('venue/add-address/idVenue/' . $this->venue->id);?>">Add Address</a>
			<?php endif; ?>
	</div>
	
	<div><?php echo $this->venue->description; ?></div>
	
	<div id="favoriteVenue">
		<?php 
		if ($this->user) { 
			if(!$this->user->favoriteVenues->contains($this->venue)) { ?>
				<a class="ajax" href="/venue/favorite-venue/idVenue/<?= $this->venue->id ?>" id="lnkFavoriteVenue">Add to favorites</a>
			<?php 
			}
			else { ?>
				<a class="ajax" href="/venue/unfavorite-venue/idVenue/<?= $this->venue->id ?>" id="lnkUnfavoriteVenue">Remove from favorites</a>
			<?php 
			}
		} ?>
	</div>
</div>

<div id="venueDeals">	
	<h3>Deals</h3>
	<?php 
		if($this->venue->deals && $this->venue->deals->count()) { ?>
			<ul>
				<?php echo $this->partialLoop('/partials/deal.php', $this->venue->deals); ?>
			</ul>
		<?php }
		else {?>
		        No deals yet...
		<?php } ?>
	<a href="<?php echo $this->baseUrl('venue/new-deal/idVenue/' . $this->venue->id);?>">Add New</a>
	
</div>

<div id="venueReviews">
	<h3>Reviews</h3>
	<?php 
		if($this->venue->reviews && $this->venue->reviews->count()) { ?>
			<ul>
				<?php echo $this->partialLoop('/partials/review.php', $this->venue->reviews); ?>
			</ul>
		<?php }
		else {?>
		        No reviews yet...
		<?php } ?>
	<a href="<?php echo $this->baseUrl('review/venue/id/' . $this->venue->id);?>">Add Review</a>
</div>


