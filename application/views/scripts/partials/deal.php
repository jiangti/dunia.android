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
        <div class="right">
            <?php echo $this->formatDate($promo->timeStart, 'H:i'); ?> - <?php echo $this->formatDate($promo->timeEnd, 'H:i'); ?>
        </div>
        <div>
            <?php echo $promo->description; ?>
        </div>
        <?php if (!isset($this->today)) : ?>
            <div class="when">
                <?php echo ucwords(strtolower(str_replace(',', ', ', $promo->day))); ?>
            </div>
        <?php endif; ?>
    </li>
<?php endforeach; ?>