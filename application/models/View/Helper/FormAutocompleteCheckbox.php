<?php

class Dol_Model_View_Helper_FormAutocompleteCheckbox extends Zend_View_Helper_FormElement
{
     /**
     * Generates 'select' list of options.
     *
     * @access public
     *
     * @param string|array $name If a string, the element name.  If an
     * array, all other parameters are ignored, and the array elements
     * are extracted in place of added parameters.
     *
     * @param mixed $value The option value to mark as 'selected'; if an
     * array, will mark all values in the array as 'selected' (used for
     * multiple-select elements).
     *
     * @param array|string $attribs Attributes added to the 'select' tag.
     *
     * @param array $options An array of key-value pairs where the array
     * key is the radio value, and the array value is the radio text.
     *
     * @param string $listsep When disabled, use this list separator string
     * between list values.
     *
     * @return string The select tag and options XHTML.
     */
    public function formAutocompleteCheckbox($name, $value = null, $attribs = null, $options = null, $listsep = "<br />\n") {

       $info = $this->_getInfo($name, $value, $attribs, $options, $listsep);
       extract($info); // name, id, value, attribs, options, listsep, disable

        $uri = isset($attribs['uri']) ? $attribs['uri'] : '';
        if (empty($uri)) {
            throw new Exception("No uri supplied for autocomplete");
        }
        unset($attribs['uri']);

        $displayValue = isset($attribs['displayValue']) ? $attribs['displayValue'] : '';
        unset($attribs['displayValue']);

        // check if element may have multiple values
        $multiple = '';

        if (substr($name, -2) == '[]') {
            // multiple implied by the name
            $multiple = ' multiple="multiple"';
        }

        if (isset($attribs['multiple'])) {
            // Attribute set
            if ($attribs['multiple']) {
                // True attribute; set multiple attribute
                $multiple = ' multiple="multiple"';

                // Make sure name indicates multiple values are allowed
                if (!empty($multiple) && (substr($name, -2) != '[]')) {
                    $name .= '[]';
                }
            } else {
                // False attribute; ensure attribute not set
                $multiple = '';
            }
            unset($attribs['multiple']);
        }

        // now start building the XHTML.
        $disabled = '';
        if (true === $disable) {
            $disabled = ' disabled="disabled"';
        }

        $endTag = ' />';
        if (($this->view instanceof Zend_View_Abstract) && !$this->view->doctype()->isXhtml()) {
            $endTag= '>';
        }

        $xhtml  = "<script type=\"text/javascript\">\n"
                . "$(document).ready(function() {\n"
                . "    $('#" . $this->view->escape($id) . "Autocomplete').autocomplete({ \n"
                . "        source : '" . $this->view->escape($uri) . "',"
                . "        minChars: 2,"
                . " 	   select : function(event, ui) {\n"
                . "        $(this).val(''); \n"
                . "        var id    = ui.item.id;\n"
                . "        var label = ui.item.label;\n"
                . "        if ($('#" . $this->view->escape($name) . "-' + id).val() === undefined) {\n"
                . "            $('#" . $this->view->escape($id) . "_li_separator').css(\"display\", \"inline\");\n"
                . "            var str ='<li style=\"color: green;\"><input type=\"checkbox\" name=\"".$this->view->escape($name)."[]\" id=\"".$this->view->escape($name)."-' + id + '\" value=\"' + id + '\" checked=\"checked\" onchange=\"$(this).parent(\\'li\\').fadeOut(400, function(){ $(this).remove() });\" />' + label + '</li>';\n"
                . "            $(\"#".$this->view->escape($id)."SelectedOptions\").prepend(str);\n"
                . "        }\n"
                . "        return false;\n"
                . "    	  } \n"
                . " });"
                . " });\n"
                . "</script>\n"
                . "<input type=\"text\""
                . " id=\"" . $this->view->escape($id) . "Autocomplete\""
                . " value=\"" . $this->view->escape($displayValue) . "\""
                . $this->_htmlAttribs($attribs)
                . $endTag."\n";

        $existingStr = "<ul>\n";

        if (is_array($value)) {

            foreach ($value as $option) {
                $existingStr .= '<li>'
                              . $this->setSelectedCheckbox($this->view->escape($name) . '[]' ,
                                    $this->view->escape($name) . '-' .$option->id,
                                    $option->id,
                                    $option->name,
                                    true)
                              . '</li>';

            }
        }

        $existingStr .= "</ul>\n";

        $strWrap  = '<div>' . $xhtml .'</div>';
        $strWrap .= '<div id="'.$this->view->escape($id).'SelectedOptions">';
        $strWrap .=	$existingStr;
        $strWrap .= '</div>';

        return $strWrap;
    }

    /**
     * Builds the actual <option> tag
     *
     * @param string $value Options Value
     * @param string $label Options Label
     * @param array  $selected The option value(s) to mark as 'selected'
     * @param array|bool $disable Whether the select is disabled, or individual options are
     * @return string Option Tag XHTML
     */
    protected function _build($value, $label, $selected, $disable) {
        if (is_bool($disable)) {
            $disable = array();
        }

        $opt = '<option'
             . ' value="' . $this->view->escape($value) . '"'
             . ' label="' . $this->view->escape($label) . '"';

        // selected?
        if (in_array((string) $value, $selected)) {
            $opt .= ' selected="selected"';
        }

        // disabled?
        if (in_array($value, $disable)) {
            $opt .= ' disabled="disabled"';
        }

        $opt .= '>' . $this->view->escape($label) . "</option>";

        return $opt;
    }

    /**
     * Build checkbox for selected value
     *
     * @param string $name
     * @param string $id
     * @param string $value
     * @param bool $checked
     *
     * @return string INPUT checkbox
     */
    protected function setSelectedCheckbox($name, $id, $value, $label, $checked = true) {
        $xhtml = '<input type="checkbox" '
                        . (($checked)? 'checked="checked" ':'')
                        . 'name="' . $name . '" '
                        . 'id="' . $id . '" '
                        . 'value="' . $value . '"'
                . 'onchange="$(this).parent(\'li\').fadeOut(400, function(){ $(this).remove(); });" '
                . '>';
        $xhtml .= $label;
        return $xhtml;
    }
}
