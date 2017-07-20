<?php
use \D4rkm3z\BashColorCLI;

class Console
{
    protected function setAction(){

    }

    public function main(){
        echo BashColorCLI::transformString("Are you sure you want to do this?  Type 'yes' to continue: ", 'blue');
        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);
        if(trim($line) != 'yes'){
            echo "ABORTING!\n";
            exit;
        }
        fclose($handle);
        echo "\n";
        echo "Thank you, continuing...\n";
    }
}



