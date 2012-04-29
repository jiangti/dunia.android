<?php

class S4_Sniffs_Functions_FunctionCallArgumentSpacingSniff
    extends Generic_Sniffs_Functions_FunctionCallArgumentSpacingSniff {

    public $spacesAfterCommaLimit = 1;

    public function process(PHP_CodeSniffer_File $phpcsFile, $stackPtr)
    {
        $tokens = $phpcsFile->getTokens();

        // Skip tokens that are the names of functions or classes
        // within their definitions. For example:
        // function myFunction...
        // "myFunction" is T_STRING but we should skip because it is not a
        // function or method *call*.
        $functionName    = $stackPtr;
        $functionKeyword = $phpcsFile->findPrevious(PHP_CodeSniffer_Tokens::$emptyTokens, ($stackPtr - 1), null, true);
        if ($tokens[$functionKeyword]['code'] === T_FUNCTION || $tokens[$functionKeyword]['code'] === T_CLASS) {
            return;
        }

        // If the next non-whitespace token after the function or method call
        // is not an opening parenthesis then it cant really be a *call*.
        $openBracket = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($functionName + 1), null, true);
        if ($tokens[$openBracket]['code'] !== T_OPEN_PARENTHESIS) {
            return;
        }

        $closeBracket = $tokens[$openBracket]['parenthesis_closer'];

        $nextSeperator = $openBracket;
        while (($nextSeperator = $phpcsFile->findNext(array(T_COMMA, T_VARIABLE), ($nextSeperator + 1), $closeBracket)) !== false) {
            // Make sure the comma or variable belongs directly to this function call,
            // and is not inside a nested function call or array.
            $brackets    = $tokens[$nextSeperator]['nested_parenthesis'];
            $lastBracket = array_pop($brackets);
            if ($lastBracket !== $closeBracket) {
                continue;
            }

            if ($tokens[$nextSeperator]['code'] === T_COMMA) {
                if ($tokens[($nextSeperator - 1)]['code'] === T_WHITESPACE) {
                    $error = 'Space found before comma in function call';
                    $phpcsFile->addError($error, $stackPtr, 'SpaceBeforeComma');
                }

                if ($tokens[($nextSeperator + 1)]['code'] !== T_WHITESPACE) {
                    $error = 'No space found after comma in function call';
                    $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterComma');
                } else {
                    // If there is a newline in the space, then the must be formatting
                    // each argument on a newline, which is valid, so ignore it.
                    if (strpos($tokens[($nextSeperator + 1)]['content'], $phpcsFile->eolChar) === false) {
                        $space = strlen($tokens[($nextSeperator + 1)]['content']);
                        if ($this->spacesAfterCommaLimit && $space > $this->spacesAfterCommaLimit) {
                            $error  = sprintf('Expected %d space after comma in function call; %d found', $this->spacesAfterCommaLimit, $space);
                            $phpcsFile->addError($error, $stackPtr, 'TooMuchSpaceAfterComma');
                        }
                    }
                }
            } else {
                // Token is a variable.
                $nextToken = $phpcsFile->findNext(PHP_CodeSniffer_Tokens::$emptyTokens, ($nextSeperator + 1), $closeBracket, true);
                if ($nextToken !== false) {
                    if ($tokens[$nextToken]['code'] === T_EQUAL) {
                        if (($tokens[($nextToken - 1)]['code']) !== T_WHITESPACE) {
                            $error = 'Expected 1 space before = sign of default value';
                            $phpcsFile->addError($error, $stackPtr, 'NoSpaceBeforeEquals');
                        }

                        if ($tokens[($nextToken + 1)]['code'] !== T_WHITESPACE) {
                            $error = 'Expected 1 space after = sign of default value';
                            $phpcsFile->addError($error, $stackPtr, 'NoSpaceAfterEquals');
                        }
                    }
                }
            }//end if
        }//end while

    }//end process()


}//end class

?>
