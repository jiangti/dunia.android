<?php
/**
 * A ZendFramework-ish filter for PHP_Beautifier
 *
 * This file have to go in:
 *      /path/to/pear/php/Beautifier/Filter/
 *
 * Use it in conjunction with Pear() and NewLines() filters:
 *      php_beautifier --input "/path/to/your-file.php" --filter="Pear() zfish() NewLines(after=T_DOC_COMMENT)"
 *
 * of course you need PEAR and PHP_Beautifier installed
 *
 *
 */
class PHP_Beautifier_Filter_Aw extends PHP_Beautifier_Filter {


    /**
     * Keep blank lines
     */
    function t_whitespace($sTag) {
        $match = array();
        // how many new lines can we match?
        preg_match_all("/(\r\n|\r|\n)/s", $sTag, $match);
        if (!empty($match[1])) {
            $newLines = count($match[1]);
            if ($newLines == 2) {
                $this->oBeaut->addNewLineIndent();
            } else if ($newLines > 2) {
                $this->oBeaut->addNewLineIndent();
                $this->oBeaut->addNewLineIndent();
            }
        }
    }
    /**
     * Keep blankline in array definitiion and align
     */
    function t_comma($sTag) {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add($sTag . ' ');

        if ($this->oBeaut->getControlParenthesis() == T_ARRAY) {
            $matches = array();
            $aToken = $this->oBeaut->getToken($this->oBeaut->iCount + 1);
            if (preg_match_all("/(\r\n|\n|\r)/s", @$aToken[1], $matches)) {
                $this->oBeaut->incIndent();
                $this->oBeaut->addNewLineIndent();
                $this->oBeaut->decIndent();
            }
        }
    }

    public function t_string_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_integer_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_int_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_array_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_bool_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_double_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_object_cast($tag) {
        $this->_castAddSpace($tag);
    }

    public function t_unset_cast($tag) {
        $this->_castAddSpace($tag);
    }

    private function _castAddSpace($tag) {
        if ($this->oBeaut->getNextTokenConstant() !== T_WHITESPACE) {
            $this->oBeaut->add($tag . ' ');
        } else {
            $this->oBeaut->add($tag);
        }
    }

    public function t_string($sTag) {
        if ($sTag == "NULL" || $sTag == "TRUE" || $sTag == "FALSE") {
            $this->oBeaut->add(strtolower($sTag));
        } else {
            $this->oBeaut->add($sTag);
        }
    }
    /**
     * Keep and align multiline String Concatenation
     *
     */
    function t_dot($sTag) {
        //check if the previous token is withspace with newline in it
        $aToken = $this->oBeaut->getToken($this->oBeaut->iCount + 1);
        if ($aToken[0] == 'T_WHITESPACE' && preg_match_all("/(\r\n|\n|\r)/s", $aToken[1], $match)) {
            $this->oBeaut->incIndent();
            $this->oBeaut->addNewLineIndent();
            $this->oBeaut->decIndent();
        } else {
            $this->oBeaut->removeWhitespace();
            $this->oBeaut->add(' ');
        }
        $this->oBeaut->add($sTag . ' ');
    }
    /**
     * Add a space before and after a T_CONCAT_EQUAL
     */
    function t_concat_equal($sTag) {
        $this->oBeaut->removeWhitespace();
        $this->oBeaut->add(' ' . $sTag . ' ');
    }

    function t_foreach($sTag) {
        $this->oBeaut->add($sTag . ' ');
    }

    function t_catch($sTag) {
        $this->oBeaut->add($sTag . ' ');
    }
    function t_doc_comment($sTag) {
        $this->oBeaut->add($sTag);
        $this->oBeaut->addNewLineIndent();
    }
}
