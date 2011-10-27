<?php
class Dol_Model_View_Helper_FormLimitedTextarea extends Zend_View_Helper_FormElement {

    public $rows   = 24;
    public $cols   = 80;
    public $length = 200;
    
    public function FormLimitedTextarea($name, $value = null, $attribs = null) {
    	
        $info = $this->_getInfo($name, $value, $attribs);
        extract($info); // name, value, attribs, options, listsep, disable

        // is it disabled?
        $disabled = '';
        if ($disable) {
            // disabled.
            $disabled = ' disabled="disabled"';
        }

        // Make sure that there are 'rows' and 'cols' values
        // as required by the spec.  noted by Orjan Persson.
        if (empty($attribs['rows'])) {
            $attribs['rows'] = (int) $this->rows;
        }
        if (empty($attribs['cols'])) {
            $attribs['cols'] = (int) $this->cols;
        }
        if (empty($attribs['length'])) {
            $attribs['length'] = (int) $this->length;
        }
        
        $attribs['class'] = isset($attribs['class']) ? $attribs['class'] . ' limited' : 'limited';
        
        $id = $this->view->escape($id);

        $javascript = '<script>
        			      $(document).ready(function() {
        			          $("#' . $id . '").keyup(function() {
        			              var newLength = ' . $attribs['length'] . ' - $("#' . $id . '").val().length;
        			              $("#' . $id . '-counter").text(newLength);
        			              if (newLength < 0) {
        			                  $("#' . $id . '-counter").css("color","#d00");
        			              } else if (newLength >= 0) {
        			                  $("#' . $id . '-counter").css("color","#ddd");
        			              }
        			          }).keyup();
        			      });
        			   </script>';
        
        // build the element
        $xhtml = '<div class="limited-textarea">
        		  <textarea name="' . $this->view->escape($name) . '"'
                . ' id="' . $this->view->escape($id) . '"'
                . $disabled
                . $this->_htmlAttribs($attribs) . '>'
                . $this->view->escape($value) . '</textarea>
        		  <span class="grey" id="' . $this->view->escape($name) . '-counter">' . $attribs['length'] . '</span>
                </div>';

        return $javascript . $xhtml;
    }
}
