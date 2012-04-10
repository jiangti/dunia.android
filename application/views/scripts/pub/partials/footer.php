<?php 
    $selectedText   = 'ui-btn-active ui-state-persist';
 ?>

<div data-role="footer" class="ui-footer-fixed nav-bar" data-grid="d">		
	<div data-role="navbar">
		<ul>
			<li>
                <a class="beer-tab <?php if ($this->selected == 'beer') echo $selectedText; ?>" href="#deals" data-icon="custom" data-transition="none">
                    Deals
                </a>
            </li>
			<li>
                <a class="map-tab <?php if ($this->selected == 'map') echo $selectedText; ?>" href="#map" data-icon="custom" data-transition="none">
                    Map
                </a>
            </li>
		</ul>
	</div>
</div>