<?php
class Aw_Tool_CodeProvider extends Zend_Tool_Framework_Provider_Abstract implements Zend_Tool_Framework_Provider_Interface {
    public function shorttagAction($file) {
        if (!file_exists($file)) {
            $this->_registry->getResponse()->setContent(sprintf('Invalid! File %s not exists.', $file));
            exit(0);
        }

        $path = realpath($file);

        if (is_file($path)) {
            $this->_shortTag($path);
        } else {

            foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path)) as $file) {
                if (stripos($file->getBasename(), '.php') !== false || stripos($file->getBasename(), '.phtml') !== false) {
                    $this->_shortTag($file->getPathName());
                }
            }
        }
    }
    
    public function dos2unixAction($path) {
    	$cmd = sprintf('dos2unix %s', $path);
    	passthru($cmd);
    }
    
    public function tab2spaceAction($path) {
    	file_put_contents($path, str_replace("\t", "    ", file_get_contents($path)));
    }
    
    public function scssIndentAction($path) {
    	
    	$tempFile = sys_get_temp_dir() . '/' . md5(rand(0,99999));
    	
    	copy($path, $tempFile);
    	
    	$file = fopen($tempFile, 'r');
    	
    	while ($row = fgetcsv($file, 0, "\n")) {
    		$line = $row[0];
    		if (preg_match_all('/ }/', $line, $match)) {
    			preg_match('/^\s*/', $line, $empty);
    			$indent = strlen($empty[0]);
    			$count = count($match[0]);
    	
    			$line = str_replace(str_repeat(" }", $count), '', $line);
    			echo $line;
    	
    			for ($i = 1; $i <= $count; $i++) {
    				$dent = ($indent - ($i * 2));
    				echo sprintf("%s%s}%s", "\n", str_repeat(" ", $indent - ($i * 2)), "\n");
    			}
    		} else {
    			echo $line . "\n";
    		}
    	}
    	
    	fclose($file);
    	unlink($tempFile);
    }
    
    public function spyAction() {
    	
    	$application = Zend_Registry::get('application');
    	$db = $application->getBootstrap()->getResource('db');
    	$dbConf = $db->getConfig();
    	$cmd = sprintf(
    	'java -jar library/Aw/Contrib/SchemaSpy/schemaSpy_5.0.0.jar -u %s -p %s -t mysql -o docs/db/schema -dp library/Aw/Contrib/SchemaSpy/mysql-connector-java-5.1.17-bin.jar -host %s -db %s -noviews'
    	, $dbConf['username'], $dbConf['password'], $dbConf['host'], $dbConf['dbname']);
    	$this->_registry->getResponse()->setContent($cmd);
    }

    public function beautyAction($file) {
        if (!file_exists($file)) {
            $this->_registry->getResponse()->setContent(sprintf('Invalid! File %s not exists.', $file));
            exit(0);
        }

        defined('APPLICATION_ROOT') || define('APPLICATION_ROOT', realpath(dirname(__FILE__) . '/../'));

        require_once ('PHP/Beautifier.php');
        require_once ('PHP/Beautifier/Batch.php');
        require_once (__DIR__ . '/Library/Beauty/Aw.filter.php');
        require_once (__DIR__ . '/Library/Beauty/Fluent.filter.php');
        require_once (__DIR__ . '/Library/Beauty/ArrayFilter.filter.php');

        try {

            $codeBeautifier = new PHP_Beautifier();
            //$codeBeautifier->addFilter('PEAR');
            $codeBeautifier->addFilter('Aw');
            $codeBeautifier->addFilter('DocBlock');
            $codeBeautifier->addFilter('NewLines', array());
            $codeBeautifier->addFilter('ArrayNested');
            $codeBeautifier->addFilter('Lowercase');

            $codeBeautifier->setNewLine(PHP_EOL);
            if (is_dir($file)) {
                $it = new RecursiveDirectoryIterator(realpath($file));
                foreach (new RecursiveIteratorIterator($it) as $file) {
                    if ($file->isFile()) {
                        $this->_beauty($codeBeautifier, $file->getPathName());
                    }
                }
            } else {
                $this->_beauty($codeBeautifier, $file);
            }
        }
        catch (Exception $e) {
            echo ($e);
        }
    }

    private function _shortTag($file) {
        echo "Replacing short open tags in \"$file\"...\n";
        if (is_dir($file))return false;
        $content = file_get_contents($file);
        $tokens = token_get_all($content);
        $previousCode = $output = '';

        foreach ($tokens as $index => $token) {
            if (is_array($token)) {
                list($index, $code, $line) = $token;
                switch ($index) {
                    case T_OPEN_TAG_WITH_ECHO:
                        if (stripos($code, '<?=') !== false) {
                            if (isset($tokens[$index + 1]) && $tokens[$index + 1][0] == T_WHITESPACE) {
                                $output.= '<?php echo';
                            } else {
                                $output.= '<?php echo ';
                            }
                        } else {
                            $output.= $code;
                        }
                    break;
                    case T_OPEN_TAG:
                        if (stripos($code, 'php') === false) {
                            if (isset($tokens[$index + 1]) && $tokens[$index + 1][0] == T_WHITESPACE) {
                                $output.= '<?php';
                            } else {
                                $output.= '<?php ';
                            }
                        } else {
                            $output.= $code;
                        }
                    break;
                    default:
                        $output.= $code;
                    break;
                }
            } else {
                $output.= $token;
            }
        }
        $in = array(
            '<?php echo  ',
            ') ?>',
            ';?>'
        );
        $out = array(
            '<?php echo ',
            '); ?>',
            '; ?>'
        );
        $output = str_replace($in, $out, $output);
        $output = preg_replace('/([a-z0-9]) \?>/', '$1; ?>', $output);
        $output = preg_replace('/([a-z0-9\]])\?>/', '$1; ?>', $output);
        file_put_contents($file, $output);
        //echo $output;

    }

    private function _beauty($codeBeautifier, $file) {
        shell_exec('dos2unix ' . $file);
        $input = file_get_contents($file);
        $codeBeautifier->setInputString($input);
        $codeBeautifier->process();

        $processed = $codeBeautifier->get();

        $lines = array();
        foreach (explode(PHP_EOL, $processed) as $line) {
            $lines[] = rtrim($line);
        }
        $processed = implode(PHP_EOL, $lines);
        file_put_contents($file, $processed);
        shell_exec('dos2unix ' . $file);
    }

    public function cpdAction() {
    }
}
