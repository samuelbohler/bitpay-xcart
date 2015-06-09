<?php

require_once('vendor/autoload.php');

class RoboFile extends \Robo\Tasks
{

    use \Robo\Task\Development\loadTasks;
    use \Robo\Common\TaskIO;
    protected $config;

    public function setuptest()
    {
        $this->taskDeleteDir('tests/phpcs')->run();
        $this->taskFileSystemStack()
             ->mirror('vendor/opencart/opencart/tests/phpcs', 'tests/phpcs')->run();
    }

    public function test()
    {
        $this->taskExec('./bin/phpcs')->arg('src')->arg('--standard=./vendor/drupal/coder/coder_sniffer/Drupal')->run();
    }

    public function stylefix()
    {
        $this->taskExec('./bin/phpcbf')->arg('src')->arg('--standard=./vendor/drupal/coder/coder_sniffer/Drupal')->run();
    }

    public function watch()
    {
        $this->dev();
        $this->taskWatch()
             ->monitor('composer.json', function () {
                $this->taskComposerUpdate()->run();
                $this->dev();
           })->monitor('src/', function () {
                $this->dev();
           })->run();
    }

    public function dev()
    {
        $this->taskDeleteDir('www')->run();
        $this->taskFileSystemStack()->mirror('src', 'www')->run();
        $this->taskFileSystemStack()->mirror('vendor/bitpay/php-client/src/Bitpay', 'www/classes/XLite/Module/BitPay/BitPay/lib/Bitpay')->run();
        $this->taskReplaceInFile('www/classes/XLite/Module/BitPay/BitPay/lib/xcart-wrapper.php')
             ->from('{{bitpay_lib_version}}')
             ->to($this->depver('bitpay/php-client'))
             ->run();

        // DELETE ME!!!!!! This is for Sam's dev environment
        $this->taskDeleteDir('/Applications/MAMP/htdocs/xcart/classes/XLite/Module/BitPay/BitPay')->run();
        $this->taskDeleteDir('/Applications/MAMP/htdocs/xcart/skins/admin/en/modules/BitPay/BitPay')->run();
        $this->taskFileSystemStack()->mirror('www', '/Applications/MAMP/htdocs/xcart')->run();
    }

    private function depver($dep)
    {
        if ($composerLock = file_get_contents(__DIR__.DIRECTORY_SEPARATOR.'composer.lock')) {
            $json = json_decode($composerLock);
            foreach ($json->packages as $package) {
                if ($package->name === $dep) {
                    return $package->version;
                }
            }
            return "Not Found";
        }
        return "Not Installed";
    }

    public function build()
    {
        // $this->taskDeleteDir('build')->run();
        // $this->taskFileSystemStack()->mirror('src', 'build/commerce-bitpay')->run();
        // $this->taskFileSystemStack()->mirror('vendor/bitpay/php-client/src/Bitpay', 'build/commerce-bitpay/libraries/bitpay')->run();
        // $this->taskReplaceInFile('build/commerce-bitpay/includes/commerce_bitpay.library.inc')
        //      ->from('{{bitpay_lib_version}}')
        //      ->to($this->depver('bitpay/php-client'))
        //      ->run();
        // $this->taskExec('zip')->dir('build/commerce-bitpay')->arg('-r')->arg('../commerce-bitpay.zip')->arg('./')->run();
    }
}
