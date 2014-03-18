<?php

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Dimsav\Backup\Application;

require_once __DIR__.'/../../../vendor/autoload.php';
require_once __DIR__.'/../../../vendor/phpunit/phpunit/PHPUnit/Framework/Assert/Functions.php';

/**
 * Features context.
 */
class FeatureContext extends BehatContext
{

    private $configHelper;
    private $app;
    /**
     * @var mysqli
     */
    private $db;

    public function __construct(array $parameters) {
        define('BEHAT_ERROR_REPORTING', E_ALL);
        $this->configHelper = new \Dimsav\Backups\TestConfigHelper();
        $this->app = new Application($this->configHelper->getConfig());
    }

    /**
     * @BeforeSuite
     */
    public static function prepare() {
        $tempDir = __DIR__.'/../../temp';
        if (is_dir($tempDir)) exec('rm -rf '.realpath($tempDir));
    }

    /**
     * @Given /^I run the app using the project "([^"]*)"$/
     */
    public function iRunTheAppUsingTheProject($projectName) {
        $this->configHelper->excludeProjectsNotMatching($projectName);
        $this->app->run();
    }

    /**
     * @Then /^I should see a folder named "([^"]*)" in the backups directory$/
     */
    public function iShouldSeeAFolderNamedInTheBackupsDirectory($folderName) {
        assertTrue(is_dir($this->configHelper->getBackupDir($folderName)));
    }

    /**
     * @Given /^The backup folder of project "([^"]*)" should contain (\d+) file of type "([^"]*)"$/
     */
    public function theBackupFolderOfProjectShouldContainFileOfType($projectName, $count, $fileType) {
        $files = scandir($this->configHelper->getBackupDir($projectName));
        $found = 0;
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) == $fileType)
                $found ++;
        }

        assertEquals($count, $found);
    }

    /**
     * @Given /^The log file exists$/
     */
    public function theLogFileExists() {
        assertFileExists(realpath($this->configHelper->get('app.log')));
    }

    /**
     * @Given /^I run the sql file "([^"]*)" to the empty database "([^"]*)"$/
     */
    public function iRunTheSqlFileToTheEmptyDatabase($fileName, $databaseName) {
        $this->emptyDatabase($databaseName);
        $file = $this->getSqlFile($fileName);
        $this->loadSql($file);
    }

    /**
     * @Then /^There is a row with "([^"]*)" "([^"]*)" in the "([^"]*)" table$/
     */
    public function thereIsARowWithInTheTable($field, $value, $table) {
        $count = $this->db->query("SELECT 1 AS count FROM `{$table}` WHERE `{$field}` = '{$value}'")->num_rows;
        assertEquals(1, $count);
    }

    private function dbConnectTo($database = '') {
        $host = $this->configHelper->get('projects.default.database.host');
        $user = $this->configHelper->get('projects.default.database.username');
        $pass = $this->configHelper->get('projects.default.database.password');
        $port = $this->configHelper->get('projects.default.database.port');
        $this->db = new mysqli($host, $user, $pass, $database, $port);
    }

    private function getSqlFile($fileName) {
        $file = realpath(__DIR__.'/../../sql/'.$fileName);
        assertFileExists($file);
        return $file;
    }


    private function emptyDatabase($databaseName) {
        $this->dbConnectTo($databaseName);
        $this->db->query('DROP DATABASE '. $databaseName);
        $this->db->query('CREATE DATABASE '. $databaseName);
        $this->db->select_db($databaseName);
        assertEquals(0, $this->db->query('show tables')->num_rows);
    }

    private function loadSql($file) {
        $query = '';
        $lines = file($file);
        foreach ($lines as $line)
        {
            // Skip it if it's a comment
            if (substr($line, 0, 2) == '--' || $line == '')
                continue;

            $query .= $line;
            // If it has a semicolon at the end, it's the end of the query
            if (substr(trim($line), -1, 1) == ';') {
                $this->db->query($query)
                    or print('Error performing query \'<strong>' . $query . '\': ' . $this->db->error . '<br /><br />');
                $query = '';
            }
        }
    }

}
