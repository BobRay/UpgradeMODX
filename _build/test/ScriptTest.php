<?php

/**
 * Created by PhpStorm.
 * User: Bob Ray
 * Date: 10/25/15
 * Time: 10:34 PM
 */
class UpgradeMODXTest extends PHPUnit_Framework_TestCase {

    /** @var $versionlist string - array of versions to display if upgrade is available as a string
     *  to inject into upgrade script */
    public $versionList = '';

    /** @var $versionArray string - array of versions to display if upgrade is available as a string
     *  to inject into upgrade script */

    public $versionArray = '';

    protected $corePath = null;
    /** @var $latestVersion string - latest version available; set only if an upgrade */
    protected $latestVersion = '';

    /** @var $errors array - array of error message (non-fatal errors only) */
    protected $errors = array();

    /** @var $forcePclZip boolean */
    protected $forcePclZip = false;

    /** @var $plOnly boolean */
    protected $plOnly = true;

    /** @var $versionData  */
    protected $versionData =  '[{
            "name":"v2.4.2-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.4.2-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.4.2-pl","commit":{
                "sha":"ed2adf4c3c4bba5102f5acba256f20845761ba56","url":"https://api.github.com/repos/modxcms/revolution/commits/ed2adf4c3c4bba5102f5acba256f20845761ba56"}},{
            "name":"v2.4.1-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.4.1-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.4.1-pl","commit":{
                "sha":"d63cc1735422a9c5c51b18a9f21d26bd1f6c390b","url":"https://api.github.com/repos/modxcms/revolution/commits/d63cc1735422a9c5c51b18a9f21d26bd1f6c390b"}},{
            "name":"v2.4.0-rc1","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.4.0-rc1","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.4.0-rc1","commit":{
                "sha":"e1042374fa8c46a853c8f583ec16061b9ad4ebb5","url":"https://api.github.com/repos/modxcms/revolution/commits/e1042374fa8c46a853c8f583ec16061b9ad4ebb5"}},{
            "name":"v2.4.0-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.4.0-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.4.0-pl","commit":{
                "sha":"429427aa5ba35c7e7b09601302442efdeaced534","url":"https://api.github.com/repos/modxcms/revolution/commits/429427aa5ba35c7e7b09601302442efdeaced534"}},{
            "name":"v2.3.6-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.6-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.6-pl","commit":{
                "sha":"1b473d07ee9fd4f9a670967b0d28a19c3a3209b4","url":"https://api.github.com/repos/modxcms/revolution/commits/1b473d07ee9fd4f9a670967b0d28a19c3a3209b4"}},{
            "name":"v2.3.5-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.5-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.5-pl","commit":{
                "sha":"b3f4f2d3aaf4f5671e8726b06e67724b234d9372","url":"https://api.github.com/repos/modxcms/revolution/commits/b3f4f2d3aaf4f5671e8726b06e67724b234d9372"}},{
            "name":"v2.3.4-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.4-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.4-pl","commit":{
                "sha":"4a307fcb60a24ec8afbb91edd0c145a606629dea","url":"https://api.github.com/repos/modxcms/revolution/commits/4a307fcb60a24ec8afbb91edd0c145a606629dea"}},{
            "name":"v2.3.3-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.3-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.3-pl","commit":{
                "sha":"b754e6e619837bd41c7c4e2ba6ecacb67e6732a4","url":"https://api.github.com/repos/modxcms/revolution/commits/b754e6e619837bd41c7c4e2ba6ecacb67e6732a4"}},{
            "name":"v2.3.2-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.2-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.2-pl","commit":{
                "sha":"35c0b0592b2048eda52a2bd21e47cc3f1042eb03","url":"https://api.github.com/repos/modxcms/revolution/commits/35c0b0592b2048eda52a2bd21e47cc3f1042eb03"}},{
            "name":"v2.3.1-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.1-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.1-pl","commit":{
                "sha":"ccd0f149cc0393a24ac0581c1824d1f49a3d74b0","url":"https://api.github.com/repos/modxcms/revolution/commits/ccd0f149cc0393a24ac0581c1824d1f49a3d74b0"}},{
            "name":"v2.3.0-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.3.0-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.3.0-pl","commit":{
                "sha":"9bdcb3f187b61bb670fb1ee33cc1503c267b4aac","url":"https://api.github.com/repos/modxcms/revolution/commits/9bdcb3f187b61bb670fb1ee33cc1503c267b4aac"}},{
            "name":"v2.2.16-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.16-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.16-pl","commit":{
                "sha":"c8bc292b4279c7beb6d0b5347c3c2fd621fd8c48","url":"https://api.github.com/repos/modxcms/revolution/commits/c8bc292b4279c7beb6d0b5347c3c2fd621fd8c48"}},{
            "name":"v2.2.15-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.15-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.15-pl","commit":{
                "sha":"abc44483b06d591d13767ed7d6d96d0e44b3f8eb","url":"https://api.github.com/repos/modxcms/revolution/commits/abc44483b06d591d13767ed7d6d96d0e44b3f8eb"}},{
            "name":"v2.2.14-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.14-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.14-pl","commit":{
                "sha":"a0e6938780fadd86161deae118b2a0371c82c65f","url":"https://api.github.com/repos/modxcms/revolution/commits/a0e6938780fadd86161deae118b2a0371c82c65f"}},{
            "name":"v2.2.13-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.13-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.13-pl","commit":{
                "sha":"91f917764a5e2c10cb4079b8513dc90168cfdd25","url":"https://api.github.com/repos/modxcms/revolution/commits/91f917764a5e2c10cb4079b8513dc90168cfdd25"}},{
            "name":"v2.2.12-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.12-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.12-pl","commit":{
                "sha":"9b5bf30f27a4445fe048144e114e6c4d8ccc0074","url":"https://api.github.com/repos/modxcms/revolution/commits/9b5bf30f27a4445fe048144e114e6c4d8ccc0074"}},{
            "name":"v2.2.11-pl2","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.11-pl2","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.11-pl2","commit":{
                "sha":"46c66a5d602c315f9de16eea69f02dce199ef998","url":"https://api.github.com/repos/modxcms/revolution/commits/46c66a5d602c315f9de16eea69f02dce199ef998"}},{
            "name":"v2.2.11-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.11-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.11-pl","commit":{
                "sha":"7f7b48075eb74f58d4604b05c028c5efaa35c738","url":"https://api.github.com/repos/modxcms/revolution/commits/7f7b48075eb74f58d4604b05c028c5efaa35c738"}},{
            "name":"v2.2.10-pl2","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.10-pl2","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.10-pl2","commit":{
                "sha":"70306d97716346ff397cf0a32819e02392d287c8","url":"https://api.github.com/repos/modxcms/revolution/commits/70306d97716346ff397cf0a32819e02392d287c8"}},{
            "name":"v2.2.10-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.10-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.10-pl","commit":{
                "sha":"223fc91c784a1ebbcf5502e8a6088a8069fac915","url":"https://api.github.com/repos/modxcms/revolution/commits/223fc91c784a1ebbcf5502e8a6088a8069fac915"}},{
            "name":"v2.2.9-pl2","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.9-pl2","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.9-pl2","commit":{
                "sha":"528d3a8521f4067eb045d08f5822230e732608cb","url":"https://api.github.com/repos/modxcms/revolution/commits/528d3a8521f4067eb045d08f5822230e732608cb"}},{
            "name":"v2.2.9-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.9-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.9-pl","commit":{
                "sha":"e2dd98878fc0eba193a6f42d4de715cc8a41d805","url":"https://api.github.com/repos/modxcms/revolution/commits/e2dd98878fc0eba193a6f42d4de715cc8a41d805"}},{
            "name":"v2.2.8-pl2","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.8-pl2","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.8-pl2","commit":{
                "sha":"b3021d3b83732e474a8d9e4ff4a4c6699816b224","url":"https://api.github.com/repos/modxcms/revolution/commits/b3021d3b83732e474a8d9e4ff4a4c6699816b224"}},{
            "name":"v2.2.8-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.8-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.8-pl","commit":{
                "sha":"038670d38f327262827897770a354ca489b3a8f1","url":"https://api.github.com/repos/modxcms/revolution/commits/038670d38f327262827897770a354ca489b3a8f1"}},{
            "name":"v2.2.7-pl2","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.7-pl2","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.7-pl2","commit":{
                "sha":"ced3eea73991be3f9206a708c242ef7c087169ad","url":"https://api.github.com/repos/modxcms/revolution/commits/ced3eea73991be3f9206a708c242ef7c087169ad"}},{
            "name":"v2.2.7-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.7-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.7-pl","commit":{
                "sha":"7fd91c4ce08b4e45aff85acfe3af692e552164fb","url":"https://api.github.com/repos/modxcms/revolution/commits/7fd91c4ce08b4e45aff85acfe3af692e552164fb"}},{
            "name":"v2.2.6-pl2","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.6-pl2","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.6-pl2","commit":{
                "sha":"57785f06bf2b40032eecb695d5b168409264f3fe","url":"https://api.github.com/repos/modxcms/revolution/commits/57785f06bf2b40032eecb695d5b168409264f3fe"}},{
            "name":"v2.2.6-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.6-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.6-pl","commit":{
                "sha":"3da428742cbb108212c54edcdd0dbab0067a9389","url":"https://api.github.com/repos/modxcms/revolution/commits/3da428742cbb108212c54edcdd0dbab0067a9389"}},{
            "name":"v2.2.5-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.5-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.5-pl","commit":{
                "sha":"8608c05ddb6e65c0ecdf585896bc53c0997638cf","url":"https://api.github.com/repos/modxcms/revolution/commits/8608c05ddb6e65c0ecdf585896bc53c0997638cf"}},{
            "name":"v2.2.4-pl","zipball_url":"https://api.github.com/repos/modxcms/revolution/zipball/v2.2.4-pl","tarball_url":"https://api.github.com/repos/modxcms/revolution/tarball/v2.2.4-pl","commit":{
                "sha":"d91dd6a4675e66fab97a1c5487123d014be332fc","url":"https://api.github.com/repos/modxcms/revolution/commits/d91dd6a4675e66fab97a1c5487123d014be332fc"}}]';

    /** @var $versionsToShow int */
    protected $versionsToShow = false;


   /* protected $props = array(
        'versionsToShow' => 5,
        'hideWhenNoUpgrade' => false,
        'lastCheck' => '',
        'interval' => '+1 seconds',
        'plOnly' => true,
        'language' => 'en',
        'forcePclZip' => false,
    );*/

    /** @var  $ugm MODXInstaller */
    protected $ugm;



    protected function setUp() {
        parent::setUp(); // TODO: Change the autogenerated stub

        ob_start();
        include 'C:\xampp\htdocs\addons\assets\mycomponents\upgrademodx\core\components\upgrademodx\elements\chunks\upgrademodxsnippetscriptsource.chunk.php';
        ob_end_clean();
        require dirname(dirname(dirname(dirname(dirname(dirname(__FILE__)))))) . '/config.core.php';
        require MODX_CORE_PATH . 'config/config.inc.php';

        // include 'C:/xampp/htdocs/addons/core/config/config.inc.php';
        // $this->modx =& $modx;
        // include 'C:\xampp\htdocs\addons\assets\mycomponents\upgrademodx\core\components\upgrademodx\model\upgrademodx.class.php';
        $this->ugm = new MODXInstaller();
    }

    public static function tearDownAfterClass() {

        // do sth after the last test
    }

    public function testDownloadFileCurl() {
        /** @var $ugm MODXInstaller */

        $path = dirname(__FILE__) . '/modx.zip';
        unlink($path);
        // $url = 'https://modx.com/download/direct/modx-2.4.1-pl.zip';
        $url = 'https://modx.s3.amazonaws.com/releases/' . '2.4.1' . '/modx-' . '2.4.1-pl' . '.zip';
        $method = 'curl';
        $certPath = MODX_CORE_PATH . 'components/upgrademodx/cacert.pem';
        MODXInstaller::downloadFile($url, $path, $method, $certPath);
        $this->assertFileExists($path);
        $this->assertNotEmpty(filesize($path));
        // fwrite(STDOUT, "\n File Size: " . $this->formatSizeUnits(filesize($path)));
    }

    public function testDownloadFileFopen() {
        $path = dirname(__FILE__) . '/modx.zip';
        unlink($path);
        // $url = 'https://modx.com/download/direct/modx-2.4.1-pl.zip';
        $url = 'https://modx.s3.amazonaws.com/releases/' . '2.4.1' . '/modx-' . '2.4.1-pl' . '.zip';
        $method = 'fopen';
        MODXInstaller::downloadFile($url, $path, $method);
        $this->assertFileExists($path);
        $this->assertNotEmpty(filesize($path));
        fwrite(STDOUT, "\n File Size: " . $this->formatSizeUnits(filesize($path)));
    }

    public function testunZipZipArchive() {
        $source = dirname(__FILE__) . "/modx.zip";
        $forcePclZip = false;
        $tempDir = realPath(dirname(__FILE__)) . '/temp';
        MODXInstaller::removeFolder($tempDir, true);
        $this->assertFalse(is_dir($tempDir));
        MODXInstaller::mmkdir($tempDir);
        clearstatcache();
        $destination = $tempDir;
        $success = MODXInstaller::unZip(MODX_CORE_PATH, $source, $destination, $forcePclZip);
        $this->assertTrue($success);
        $this->assertTrue(is_dir($tempDir));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'connectors'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'connectors/security'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'core'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'core/error'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'manager'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'manager/controllers'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'setup'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'setup/controllers'));
    }

    public function testunZipPclZip() {
        $tempDir = realPath(dirname(__FILE__)) . '/temp';
        MODXInstaller::removeFolder($tempDir, true);
        $corePath = MODX_CORE_PATH;
        fwrite(STDOUT, "\n Core Path: " . $corePath);
        $source = dirname(__FILE__) . "/modx.zip";
        $forcePclZip = true;
        clearstatcache();
        $destination = $tempDir;
        $success = MODXInstaller::unZip($corePath, $source, $destination, $forcePclZip);
        $this->assertTrue($success);
        $this->assertTrue(is_dir($tempDir));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'connectors'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'connectors/security'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'core'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'core/error'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'manager'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'manager/controllers'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'setup'));
        $this->assertTrue(is_dir($tempDir . '/modx-2.4.1-pl/' . 'setup/controllers'));

    }

    public function testGetDirectories() {

        /* Try with real MODX directories */
        $dirs = MODXInstaller::getDirectories();
        $base = MODX_BASE_PATH;

        $this->assertEquals($dirs['setup'], $base . 'setup');
        $this->assertEquals($dirs['core'], $base . 'core');
        $this->assertEquals($dirs['manager'], $base . 'manager');
        $this->assertEquals($dirs['connectors'], $base . 'connectors');

        $dest = dirname(__FILE__) . '/' . 'temproot';
        /* Normalize $dest */
        $dest = MODXInstaller::normalize($dest);

        /* Try with our nonstandard directories */
        $directories = $this->getDirectories($dest);

        $dirs = MODXInstaller::getDirectories($directories);

        $this->assertEquals($dirs['setup'], $dest . '/' . 'setup' );
        $this->assertEquals($dirs['core'], $dest . '/' . 'core');
        $this->assertEquals($dirs['manager'], $dest . '/' . 'manager');
        $this->assertEquals($dirs['connectors'], $dest . '/' . 'connectors');
    }

    public function getDirectories($dest) {
        return array(
            'setup' => $dest . '/setup',
            'core' => $dest . '/core',
            'manager' => $dest . '/manager',
            'connectors' => $dest . '/connectors',
            'core/model/modx/processors' => $dest . '/myprocessors',
        );

    }
    public function testCopyFiles() {
        $dest = dirname(__FILE__) . '/' . 'temproot';
        $dest = MODXInstaller::normalize($dest);
        MODXInstaller::removeFolder($dest, true);
        MODXInstaller::mmkdir($dest);

        $directories = $this->getDirectories($dest);
        $directories = MODXInstaller::normalize($directories);
        $tempDir = realPath(dirname(__FILE__)) . '/temp';
        $sourceDir = $tempDir . '/' . MODXInstaller::getModxDir($tempDir);
        $sourceDir = MODXInstaller::normalize($sourceDir);

        MODXInstaller::copyFiles($sourceDir, $directories);

        $this->assertTrue(is_dir($dest . '/setup'));
        $this->assertTrue(is_dir($dest . '/setup/controllers'));
        $this->assertTrue(is_dir($dest . '/core'));
        $this->assertTrue(is_dir($dest . '/core/docs'));
        $this->assertTrue(is_dir($dest . '/manager'));
        $this->assertTrue(is_dir($dest . '/manager/controllers'));
        $this->assertTrue(is_dir($dest . '/connectors'));
        $this->assertTrue(is_dir($dest . '/connectors/security'));
        $this->assertTrue(is_dir($dest . '/myprocessors'));

    }

    public function testRemoveFolder() {
        $folder = 'C:\xampp\htdocs\addons\assets\mycomponents\upgrademodx\_build\test\temproot';
        $this->assertTrue(is_dir($folder));

        MODXInstaller::removeFolder($folder, true);
        $this->assertFalse(is_dir($folder));

        $folder = 'C:\xampp\htdocs\addons\assets\mycomponents\upgrademodx\_build\test\temp';
        $this->assertTrue(is_dir($folder));
        MODXInstaller::removeFolder($folder, false);
        $this->assertTrue(is_dir($folder));
        MODXInstaller::removeFolder($folder);
        $this->assertFalse(is_dir($folder));

    }


    public function formatSizeUnits($bytes) {
        if ($bytes >= 1073741824)
        {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }

        return $bytes;
    }

}
