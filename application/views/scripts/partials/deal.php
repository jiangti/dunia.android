<li>
    <h4><?php echo stripslashes($this->name); ?>:</h4> 
    <p><?php echo stripslashes($this->description); ?></p>
    
    <?php if($this->reviews && $this->reviews->count()) : ?>
    <ul>
        <?php echo $this->partialLoop('/partials/review.php', $this->reviews); ?>
    </ul>
    <?php endif;?>
    <a href="<?php echo $this->baseUrl('venue/new-deal/id/' . $this->id . '/idVenue/' . $this->venue->id);?>">Edit</a> 
    <a href="<?php echo $this->baseUrl('review/deal/id/' . $this->id);?>">Add Review</a>
</li>

