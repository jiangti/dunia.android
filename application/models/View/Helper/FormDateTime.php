<?php
class Dol_Model_View_Helper_FormDateTime extends Zend_View_Helper_FormElement {

    protected $_date = 'date';
    protected $_time = 'time';

    protected $template     = '<div class="full">%s&nbsp;&nbsp;&nbsp;&nbsp;%s</div>';
    protected $templateDate = '<input type="text" name="%s" id="%s" value="%s" class="%s" %s />';
    protected $templateTime = '<input type="text" name="%s" id="%s" value="%s" class="%s" %s />';

    protected $hideTime = false;

    public function formDateTime($name, $value = null, $attribs = null) {
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // build the element
        $disabled = '';
        if ($disable) $attribs['disabled'] = 'disabled';

        $classes = '';

        if (isset($attribs['class'])) {
            $classes = str_replace(array('date', 'time'), '', $attribs['class']);
            unset($attribs['class']);
        }

        if ($value) {
            try {
                $dateTime = new DateTime($value);
                $date = $dateTime->format('d/m/Y');
                $time = $dateTime->format('H:i');
            }
            catch (Exception $e) {
                list($date, $time) = explode(' ', $value, 2);
            }
        }
        else {
            $date = $time = '';
        }

        $htmlDate = sprintf($this->templateDate,
            $this->view->escape($name) . '[' . $this->_date . ']"',
            $this->view->escape($id) . '-' . $this->_date,
            $this->view->escape($date),
            trim($classes . ' date'),
            $this->_htmlAttribs($attribs)
        );

        if ($this->hideTime) {
            $attribs += array('style' => 'display: none;');
        }

        $htmlTime = sprintf($this->templateTime,
            $this->view->escape($name) . '[' . $this->_time . ']"',
            $this->view->escape($id) . '-' . $this->_time,
            $this->view->escape($time),
            trim($classes . ' time'),
            $this->_htmlAttribs($attribs)
        );

        $html = sprintf($this->template, $htmlDate, $htmlTime);

        return $html;
    }
}
