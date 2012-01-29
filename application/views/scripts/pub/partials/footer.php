<?php 
    $selectedText   = 'data-icon="custom" data-transition="none" class="ui-btn-active ui-state-persist"';
    $unselectedText = 'data-icon="custom" data-transition="none"'
 ?>

<div data-role="footer" class="ui-footer-fixed nav-bar" data-grid="d">		
	<div data-role="navbar">
		<ul>
			<li><a id="beer-tab" href="#deals"   <?php echo ($this->selected == 'beer' ? $selectedText : $unselectedText);?>>Deals</a></li>
			<li><a id="map-tab"  href="#map"     <?php echo ($this->selected == 'map' ? $selectedText : $unselectedText);?>>Map</a></li>
			<li><a id="mail-tab" href="#contact" <?php echo ($this->selected == 'contact' ? $selectedText : $unselectedText);?>>Contact</a></li>
		</ul>
	</div>
</div>