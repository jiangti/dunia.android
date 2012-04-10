<?php foreach ($this->promos as $promo): ?>
    <li>
        <?php echo $this->currency($promo->price); ?>
        <?php foreach ($promo->getLiquorTypes() as $liquor) {
            echo ' ' . $liquor->name;
            $size = $promo->getLiquorSizes();
            foreach ($size as $s) {
                echo ' (' . $s['name'] . ')';
            }
        } ?>
        <div>
            <?php echo $this->formatDate($promo->timeStart, 'H:i'); ?> - <?php echo $this->formatDate($promo->timeEnd, 'H:i'); ?>
        </div>
    </li>
<?php endforeach; ?>