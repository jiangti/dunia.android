<script>
	$(document).ready(function() {
		$('.rounded').corner();

		$("#summaryPopup").dialog({
			autoOpen  : false,
			width     : 550,
			show	  : 'fade',
			hide 	  : 'fade'
		});

		$(".addDeal, .addVenueReview, .addDealReview").click(function() {
			$.ajax({
				url     : $(this).attr('href'),
				success : function(data) {
					$("#summaryPopup").html(data);

					$("#summaryPopup").dialog('option', 'title', $('.popup-title').text());
					$('.popup-title').remove();
					
					$("#summaryPopup").dialog('open');
				}
			});
			return false;
		});

		$(".reviewsCarousel").jCarouselLite({
			btnNext: ".next",
            visible: 1,
            speed: 500
		});

		$(".next").click(function() {
			return false;
		});
	});
</script>
<div id="venueSummary">
	<div id="summaryHeader">
		<h2><a href="<?php echo $this->baseUrl('venue/index/idVenue/' . $this->venue->id);?>"><?php echo $this->venue->name; ?></a></h2>
		<img class="venuePicture" src="/img/no-venue.gif" width="204" height="123"/>
		<div id="venueAddress">
			<?php 
				if($this->venue->address) :
					echo $this->venue->address->format();
				?>
				<?php endif; ?>
		</div>
		<div class="socialControls">
			<a class="right icon-small twitter" href="#" title="Share venue on Twitter">Share venue on Twitter</a>
    		<a class="right icon-small facebook" href="#" title="Share venue on Facebook">Share venue on Facebook</a>
			<?php 
			if ($this->user) { 
				if(!$this->user->favoriteVenues->contains($this->venue)) { ?>
					<div id="favoriteVenue">
						<a class="ajax right" href="/venue/favorite-venue/idVenue/<?= $this->venue->id ?>" id="lnkFavoriteVenue">
							<img alt="Add to favorites" src="/img/icons/social/not-favorite.png" width="16" height="16" />
						</a>
					</div>
				<?php 
				}
				else { ?>
					<div id="favoriteVenue">
						<a class="ajax right" href="/venue/unfavorite-venue/idVenue/<?= $this->venue->id ?>" id="lnkUnfavoriteVenue">
							<img alt="Remove from favorites" src="/img/icons/social/favorite.png" width="16" height="16" />
						</a>
					</div>
				<?php 
				}
			} ?>
    		<a class="right reviews addVenueReview" href="<?php echo $this->baseUrl('review/venue/id/' . $this->venue->id);?>">
    			<?php echo count($this->venue->reviews); ?>
    		</a>
		</div>
	</div>

	<div class="clear"></div>
	
	<div id="summaryDeals" class="rounded">
		<div class="header">	
			<h3>Deals</h3>
			<a class="addIcon addDeal" href="<?php echo $this->baseUrl('venue/new-deal/idVenue/' . $this->venue->id);?>">
				<img alt="Add New Deal" src="<?php echo $this->baseUrl('img/add.png'); ?>" width="37" height="75" />
			</a>
		</div>
		
		<?php 
			if($this->venue->deals && $this->venue->deals->count()) { ?>
				<ul>
					<?php echo $this->partialLoop('/partials/deal-small.php', $this->venue->deals); ?>
				</ul>
			<?php }
			else {?>
			        <div class="noContent">There are no deals entered for this venue. 
			        Why don't you <a class="addDeal" href="<?php echo $this->baseUrl('venue/new-deal/idVenue/' . $this->venue->id);?>">add one</a>?
			        </div>
			<?php } ?>
		
		
	</div>
	
	<?php /*<div id="summaryReviews" class="rounded">
		<div class="header">
			<h3>Reviews</h3>
			<a class="addIcon addVenueReview" href="<?php echo $this->baseUrl('review/venue/id/' . $this->venue->id);?>">
				<img alt="Add Review" src="<?php echo $this->baseUrl('img/add.png'); ?>" width="37" height="75" />
			</a>
		</div>
		<?php 
			if($this->venue->reviews && $this->venue->reviews->count()) { ?>
				<div class="reviewsCarousel">
					<ul>
						<?php echo $this->partialLoop('/partials/review.php', $this->venue->reviews); ?>
					</ul>
				</div>
				<a class="next" href="#">&gt;&gt;</a>
			<?php }
			else {?>
			        <div class="noContent">Nobody has reviewed this venue yet. 
			        <a class="addVenueReview" href="<?php echo $this->baseUrl('review/venue/id/' . $this->venue->id);?>">Be the first</a>!
			        </div>
			<?php } ?>
		
	</div> */ ?>
	<div id="summaryPopup"></div>
</div>
