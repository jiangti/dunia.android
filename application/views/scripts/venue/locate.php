<ul>
<?php foreach ($this->venues as $venue):
    $entity = $venue[0]; ?>
    <li><?php echo $entity->name; ?> (<?php echo $venue['distance']; ?> m)</li>
<?php endforeach;?>
</ul>

