<?php
use PHPUnit\Framework\TestCase;
/**
 * Created by PhpStorm.
 * User: BobRay
 * Date: 11/10/2018
 * Time: 2:47 PM
 */


/* IMPORTANT: Tests will not run unless ugm.devMode System Setting exists
   and is set to true */

if (! function_exists('rrmdir')) {
    function rrmdir($dir) {
        $dir = rtrim($dir, '/\\');
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir") {
                        $prefix = substr($object, 0, 4);
                        rrmdir($dir . "/" . $object);
                    } else {
                        @unlink($dir . "/" . $object);
                    }
                }
            }
            reset($objects);
            $success = @rmdir($dir);
        }
    }
}

// class TestUGM extends PHPUnit_Framework_TestCase {
class TestUGM extends TestCase {
    /** @var $modx modX - modx object */
    public $modx = null;
    /** @var $ugm UpgradeMODX */
    public $ugm = null;
    public $tempDir = 'c:/dummy/ugmtemp/';
    public $basePath = 'c:/dummy/ugmtemp/test/';
    public $logFilePath = 'C:/dummy/upgrademodx.log';
    public $version = 'modx-2.6.5-pl.zip';
    public $corePath = '';

    protected function setUp() {
        parent::setUp();
        include 'C:\xampp\htdocs\addons\assets\mycomponents\instantiatemodx\instantiatemodx.php';

        $this->modx =& $modx;
        $this::assertTrue($modx instanceof modX);
        include 'C:\xampp\htdocs\addons\assets\mycomponents\upgrademodx\core\components\upgrademodx\model\upgrademodx\upgrademodx.class.php';
        $this->ugm = new UpgradeMODX($modx);
        $this::assertTrue($this->ugm instanceof UpgradeMODX);
        $snippet = $this->modx->getObject('modSnippet', array('name' => 'UpgradeMODXWidget'));
        $props = $snippet->getProperties();

        $devMode = (bool) $this->modx->getOption('ugm.devMode', null, true, true);
        $this::assertTrue($devMode);
        $this->ugm->init($props);
        $this->props = $props;
        $this->modx->lexicon->load('en:upgrademodx:default');
    }

    public function testSetup() {
        $modxDefined = !empty(MODX_CORE_PATH);
        $this::assertTrue($modxDefined);
        $this::assertInstanceOf('modX', $this->modx);
        $this::assertInstanceOf('UpgradeMODX', $this->ugm);
    }

    public function testInit() {
        $eLogPath = $this->modx->getCachePath() . 'logs/';
        $this->ugm->init();
        $this::assertTrue(is_dir($eLogPath));
        $this::assertTrue(file_exists($eLogPath . 'error.log' ));
        $this::assertInstanceOf('\GuzzleHttp\Client', $this->ugm->client);
        $this::assertNotEmpty($this->modx->lexicon('ugm_no_version_list'));
        $this::assertEquals(array(), $this->ugm->getErrors());
        $this::assertTrue(is_dir(MODX_ASSETS_PATH . 'components/upgrademodx'));
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testGetRawVersions() {
        /* Note: Some tests here will fail if rate limit has been exceeded */

        /* Normal return */
        $versions = $this->ugm->getRawVersions('//api.github.com/repos/modxcms/revolution/tags', 6, true, '', '');
      // $versions = $this->ugm->getRawVersions('https://latest-modx.ofco.cloud', 6, true, '', '');
        $this::assertNotEmpty($versions);
        $errors = $this->ugm->getErrors();
        $this::assertEmpty($errors);
        $this::assertNotEmpty($versions);
        $vArray = json_decode($versions);
        $this::assertNotEmpty($vArray);
        $this::assertNotEmpty($vArray['0']->name);
        $this::assertNotEmpty($vArray['0']->zipball_url);
        $version = $vArray['0']->name;
        $pattern = '/v\d{1,2}\.\d{1,2}.\d{1,2}\-[A-Za-z]+/';
        preg_match($pattern, $version, $matches);
        $this::assertEquals($version, $matches[0]);

        /* Rate limit test -- This wrecks the other tests for an hour */
       /*
       $this->ugm->clearErrors();
       for ($i = 1; $i <=70; $i++) {
            $versions = $this->ugm->getVersions('//api.github.com/repos/modxcms/revolution/tags', 6, true, '', '');
            $errors = $this->ugm->getErrors();
            if (!empty ($errors)) {
                break;
            }
        }
        $errors = $this->ugm->getErrors();
        $this::assertNotEmpty($errors());
       $this::assertContains('rate limit', $errors[0]);

        // echo "\nI = " . $i;
        // echo print_r($errors, true);

        return; */

        /* Bad URL */
        $this->ugm->clearErrors();
        $versions = $this->ugm->getRawVersions('//xapi.github.com/repos/modxcms/revolution/tags', 6, true);
        $this::assertFalse($versions);
        $errors = $this->ugm->getErrors();
        $this::assertNotEmpty($errors);
        $this::assertContains('Not Found', $errors[0]);
        $this::assertContains('404', $errors[0]);
        // echo print_r($errors, true);
        // echo print_r($vArray, true);
        // for ($i = 1; $i <= 40; $i++ ) {

        /* Bad Credentials */
        $this->ugm->clearErrors();
        $versions = $this->ugm->getRawVersions('//api.github.com/repos/modxcms/revolution/tags', 6, true, 'BR', 'TK');
 //       $this::assertFalse($versions);
//        $errors = $this->ugm->getErrors();
//        $this::assertNotEmpty($errors);
//        $this::assertContains('401', $errors[0]);
//        $this::assertContains('Bad credentials', $errors[0]);

        /* Bad Credentials verbose*/
        $this->ugm->clearErrors();
        $versions = $this->ugm->getRawVersions('//api.github.com/repos/modxcms/revolution/tags', 6, true, 'BR', 'TK', '', true);
//        $this::assertFalse($versions);
        $errors = $this->ugm->getErrors();
//        $this::assertNotEmpty($errors);
//       $this::assertContains('401', $errors[0]);
//        $this::assertContains('Bad credentials', $errors[0]);
//        $this::assertContains('Client error', $errors[0]);


        /* Invalid URL */
        $this->ugm->clearErrors();
        $versions = $this->ugm->getRawVersions('//api.gixhub/repos/modxcms/revolution/tags', 6, true);
        $this::assertFalse($versions);
        $errors = $this->ugm->getErrors();
        $this::assertNotEmpty($errors);
        $this::assertContains('Could not get version list', $errors[0]);
        $this::assertContains('Connection error', $errors[0]);

    }

    public function testSetLatestVersion() {
        $s = '[{"name": "v2.7.0-rc",}, 
              "name": "v2.6.5-pl",

  },';
        $this->ugm->setLatestVersion($s);
        $v = $this->ugm->getLatestVersion();
        $this::assertNotEmpty($v);
        $this::assertEquals('2.6.5-pl', $v);
        // echo "\nVersion: " . $v;

        $this->ugm->setLatestVersion($s, false);
        $v = $this->ugm->getLatestVersion();
        $this::assertNotEmpty($v);
        $this::assertEquals('2.7.0-rc', $v);
        // echo "\nVersion: " . $v;
        $errors = $this->ugm->getErrors();
        $this::assertTrue(is_array($errors));
        $this::assertEmpty($errors);
    }
    public function testGetVersionListPath() {
        $path = $this->ugm->getVersionListPath('', true);
        $this::assertEquals('c:/dummy/ugmtemp/', $path);
        $path = $this->ugm->getVersionListPath('', false);
        $this::assertEquals('c:/xampp/htdocs/addons/core/cache/upgrademodx/', $path);
        $path = $this->ugm->getVersionListPath('No Path');
        $this::assertEquals('No Path', $path);
        $path = $this->ugm->getVersionListPath('No Path', true);
        $this::assertEquals('c:/dummy/ugmtemp/', $path);
        $path = $this->ugm->getVersionListPath('core/cache/upgrademodx/', false);
        $this::assertEquals('c:/xampp/htdocs/addons/core/cache/upgrademodx/', $path);
        // echo $path;
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testFinalizeVersionArray() {
        $rawVersions = $this->ugm->getRawVersions('//api.github.com/repos/modxcms/revolution/tags', 6, true, '', '');
        $this::assertNotEmpty($rawVersions);
        $finalizedVersionArray=$this->ugm->finalizeVersionArray($rawVersions, true, 40);
        $this::assertNotEmpty($finalizedVersionArray);
        $count = count($finalizedVersionArray);
        $this::assertGreaterThanOrEqual(20, $count);
    }


    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testCreateVersionForm() {
        $path = $this->ugm->getVersionListPath('', true);
        $versionListFile = $path . 'versionlist';
        // echo $versionListFile;
        if (file_exists($versionListFile)) {
            unlink($versionListFile);
        }
        // $this->assertTrue(false);
        $form = $this->ugm->createVersionForm($this->modx);
        $this::assertEmpty($form);
        $errors = $this->ugm->getErrors();
        $this::assertNotEmpty($errors);
        $this::assertContains("Could not get version list", $errors[0]);
        $this::assertContains("@ c:/dummy/ugmtemp/versionlist" , $errors[0]);
        // echo print_r($form, true);

        $this->ugm->upgradeAvailable('2.6.0-pl');
        $this::assertFileExists($versionListFile);
        $this->ugm->clearErrors();
        $form = $this->ugm->createVersionForm($this->modx);
        $this::assertNotEmpty($form);
        $errors = $this->ugm->getErrors();
        $this::assertEmpty($errors);
        $this::assertContains("upgrade_form", $form);
        $this::assertContains("recommended that you install", $form);
        $this::assertContains("ugm_version_header", $form);
        $this::assertContains("checked", $form);
        $this::assertContains("ugm_submit_button", $form);
        $this::assertContains("progress-button", $form);

        // echo $form;
       //  echo print_r($errors, true);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpgradeAvailable() {
       $file = $this->ugm->versionListPath . 'versionlist';
       @unlink($file);

        /* Rigged test, simulates being NOT up-to-date by pretending
           current version is an older one */
       $currentVersion='2.6.0-pl';
       $result = $this->ugm->upgradeAvailable($currentVersion);
       $this::assertTrue($result);

       $currentVersion='3.9.9-pl';

        $result = $this->ugm->upgradeAvailable($currentVersion);
        $this::assertFalse($result);

       /* Rigged test, simulates being up-to-date by pretending
          current version is the latest one */
        $currentVersion = $this->modx->getOption('ugm_latest_version');
        $result = $this->ugm->upgradeAvailable($currentVersion);
        $this::assertFalse($result);
        $this::assertEmpty($this->ugm->getErrors());

        $this::assertTrue($this->ugm->versionListExists());

        $this::assertTrue(file_exists($file));
        $content = file_get_contents($file);
        $versionsToShow = $this->modx->getOption('ugm_versions_to_show');
        $this::assertNotEmpty($versionsToShow);
        /* Make sure we're showing at least as many versions as $versionsToShow */
        $versionCount = substr_count($content, 'zipball_url');
        $this::assertTrue((int) $versionCount >= (int) 26);
    }

    /**
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function testUpdateVersionListFile() {
        $time = time();
        $path = $this->ugm->getVersionListPath('', true);
        $path .= 'versionlist';
        $rawVersions = file_get_contents($path);
        $this->ugm->setLatestVersion($rawVersions);
        $latestVersion = $this->ugm->getLatestVersion();
        // $this->ugm->upgradeAvailable($latestVersion);
        $this->ugm->updateVersionListFile('test', $latestVersion, $latestVersion);
        $content = file_get_contents($path);
        $this::assertEquals($rawVersions, $content);
        $this->ugm->updateVersionListFile('test', '2.6.0', $latestVersion);
        $content = file_get_contents($path);
        $this::assertEquals('test', $content);
        $this->ugm->updateVersionListFile($rawVersions, '2.6.0', $latestVersion );
        $content = file_get_contents($path);
        $this::assertEquals($rawVersions, $content);
       //  echo file_get_contents($path);
    }

    public function testDownloadFiles() {
        $devMode = $this->modx->getOption('ugm.devMode');
        $this::assertTrue((bool) $devMode);
        $tempDir = $this->tempDir;
        $logFilePath = $this->logFilePath;
        $version = $this->version;
        $_SESSION['ugm_version'] = $version;
        @unlink($tempDir . $version);
        $this::assertFalse(is_dir($tempDir . $version));
        $options = array( //xxx
            'processors_path' => $this->ugm->corePath . 'processors/',
        );
        $config = array(
            'version' => $version,
        );
        $response = $this->modx->runProcessor('downloadFiles', $config, $options);
        $result = $response->response;
        // echo print_r($result, true);
        $this::assertEquals(1, $result['success']);
        $this::assertEmpty($result['errors']);
        $this::assertEquals($this->modx->lexicon('ugm_unzipping_files'), $result['message']);
        $this::assertTrue(file_exists($logFilePath));
        $this::assertTrue(file_exists($tempDir . $version));
        $this::assertGreaterThan(11000000, filesize($tempDir . $version));
    }

    public function testUnzipFiles() {
        $devMode = $this->modx->getOption('ugm.devMode');
        $this::assertTrue((bool)$devMode);
        $tempDir = $this->tempDir;
        rrmdir($tempDir . 'unzipped');

        $logFilePath = $this->logFilePath;
        $version = $this->version;
        $file = str_replace('.zip', '', $version);
        $time = time();
        // rrmdir($tempDir . 'unzipped');
        $this::assertTrue(file_exists($tempDir . $version));
        $options = array(
            'processors_path' => $this->ugm->corePath . 'processors/',
        );
        $config = array(
            'version' => $version,
        );
        $response = $this->modx->runProcessor('unzipfiles', $config, $options);
        $result = $response->response;
        $this::assertEquals($this->modx->lexicon('ugm_copying_files'), $result['message']);
        $this::assertTrue(file_exists($logFilePath));
        $this::assertEquals(1, $result['success']);
        $this::assertEmpty($result['errors']);
        $this::assertTrue(is_dir($tempDir . 'unzipped/' . $file));
        /* Make sure files in unzipped directory are new */
        clearstatcache();
        $mTime = filemtime($tempDir . 'unzipped/' . $file);
        $this::assertGreaterThan($time, $mTime);
        // echo print_r($result, true);
    }

    public function testCopyFiles() {
        $devMode = $this->modx->getOption('ugm.devMode');
        $this::assertTrue((bool)$devMode);
        $tempDir = $this->tempDir;
        $basePath = $this->basePath;
        $corePath = $tempDir . 'test/core/';
        rrmdir($basePath);
        $this::assertFalse(is_dir($basePath));

        $logFilePath = $this->logFilePath;
        $version = $this->version;
        $time = time();
        $this::assertTrue(file_exists($tempDir . $version));
        $options = array(
            'processors_path' => $this->ugm->corePath . 'processors/',
        );
        $config = array(
           'version' => $version,
        );
        $this::assertTrue(is_dir($tempDir . 'unzipped'));
        $response = $this->modx->runProcessor('copyfiles', $config, $options);
        $this::assertTrue(is_dir($tempDir . 'test'));
        $result = $response->response;
        $this::assertEquals($this->modx->lexicon('ugm_preparing_setup'), $result['message']);
        $this::assertTrue(file_exists($logFilePath));
        $this::assertEquals(1, $result['success']);
        $this::assertEmpty($result['errors']);

        clearstatcache();
        $directories = array(
            'setup' => $basePath . 'setup/',
            'core' => $corePath,
            'manager' => $basePath . 'manager/',
            'connectors' => $basePath . '/connectors',
            'processors' => $corePath . 'model/modx/processors/',
        );

        foreach($directories as $k => $v) {
            $this::assertTrue(is_dir($v));
            $mTime = filemtime($v);
            $this::assertGreaterThanOrEqual($time, $mTime, 'Directory: ' . $v);
        }
        // echo print_r($result, true);
    }
    public function testPrepareSetup() {
        $devMode = $this->modx->getOption('ugm.devMode');
        $this::assertTrue((bool)$devMode);
        $logFilePath = $this->logFilePath;
        $tempDir = $this->tempDir;
        $setupDir = $tempDir . 'test/setup/';
        $this::assertTrue(is_dir($setupDir));
        $configFile = $setupDir . 'includes/config.core.php';
        $this::assertTrue(file_exists($configFile));

        $version = $this->version;
        $options = array(
            'processors_path' => $this->ugm->corePath . 'processors/',
        );
        $config = array(
            'version' => $version,
        );
        $time = time();
        $response = $this->modx->runProcessor('preparesetup', $config, $options);
        $result = $response->response;

        // echo print_r($result, true);
        $this::assertEquals($this->modx->lexicon('ugm_deleting_temp_files'), $result['message']);
        $this::assertTrue(file_exists($logFilePath));
        $this::assertEquals(1, $result['success']);
        $this::assertEmpty($result['errors']);
        $content = file_get_contents($configFile);
        $this::assertNotEmpty(strpos($content, '@traditional@'));
    }

    public function testCleanup() {
        $version = $this->version;
        $tempDir = $this->tempDir;
        $logFilePath = $this->logFilePath;
        $this::assertTrue(file_exists($logFilePath));
        $options = array(
            'processors_path' => $this->ugm->corePath . 'processors/',
        );
        $config = array(
            'version' => $version,
        );
        $response = $this->modx->runProcessor('cleanup', $config, $options);
        $result = $response->response;

        // echo print_r($result, true);
        $this::assertEquals($this->modx->lexicon('ugm_launching_setup'), $result['message']);
        $this::assertEquals(1, $result['success']);
        $this::assertEmpty($result['errors']);
        $this::assertFalse(is_dir($tempDir . 'unzipped'));

    }


}
