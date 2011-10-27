<div>
	<h2>Venues</h2>
	<ul>
		<?php foreach ($this->venues as $venue): ?>
			<li><a href="<?php echo $this->baseUrl('venue/index/idVenue/' . $venue->id);?>"><?php echo $venue->name; ?></a></li>
		<?php endforeach;?>
	</ul>
	<a href="<?php echo $this->baseUrl('index/new-venue');?>">Add venue</a>
</div>
