<?php

class S4_Sniffs_ControlStructures_ControlSignatureSniff
    extends Squiz_Sniffs_ControlStructures_ControlSignatureSniff
{

    protected function getPatterns() {
        return array(
            'try {EOL...}',
            'catch (...) {EOL',
            'do {EOL...}',
            'while (...);EOL',
            'while (...) {EOL',
            'for (...) {EOL',
            'foreach (...) {EOL',
            'if (...) {EOL',
            'else if (...) {EOL',
            'else {EOL',
        );
    }

}

