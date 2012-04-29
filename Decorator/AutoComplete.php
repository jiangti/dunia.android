<?php
class Aw_Decorator_AutoComplete extends Zend_Form_Decorator_Abstract {
    public function render($content) {
        $uri = $this->getOption('uri');
        $js = <<<'EOD'
        <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#%s").tokenInput("%s", {theme:"facebook", jsonContainer:"data"});
        });
        </script>
EOD;
        $js = sprintf($js, $this->getElement()->getId(), $uri);
        return $content . $js;
    }
}