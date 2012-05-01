<?php
class Aw_Decorator_JQMobileGroup extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $content = '
        <div data-role="fieldcontain">
			<fieldset data-role="controlgroup">
				' . $content . '
			</fieldset>
		</div>'; 
        
        return $content;
    }
}