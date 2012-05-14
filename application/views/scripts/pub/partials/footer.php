<?php 
    $selectedText   = 'ui-btn-active ui-state-persist';
 ?>

<div data-role="footer" data-position="fixed" class="nav-bar" data-tap-toggle="false">
	<div data-role="navbar">
		<ul>
			<li>
                <a class="beer-tab <?php if ($this->selected == 'beer') echo $selectedText; ?>" href="#deals" data-icon="custom" data-transition="none">
                    Deals
                </a>
            </li>
			<li>
                <a class="map-tab <?php if ($this->selected == 'maptab') echo $selectedText; ?>" href="#maptab" data-icon="custom" data-transition="none">
                    Map
                </a>
            </li>
		</ul>
	</div>
</div>