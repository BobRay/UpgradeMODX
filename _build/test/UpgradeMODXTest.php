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

    /** @var $modx modX - modx object */
    protected $modx = null;

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

    /** @var  $ugm upgradeMODX */
    protected $ugm;



    protected function setUp() {
        parent::setUp(); // TODO: Change the autogenerated stub
        include 'C:\xampp\htdocs\addons\assets\mycomponents\instantiatemodx\instantiatemodx.php';
        $this->modx =& $modx;
        include 'C:\xampp\htdocs\addons\assets\mycomponents\upgrademodx\core\components\upgrademodx\model\upgrademodx.class.php';
        $this->ugm = new UpgradeMODX($modx);
        $snippet = $this->modx->getObject('modSnippet', array('name' => 'UpgradeMODXWidget'));
        $props = $snippet->getProperties();
        $props['ugm.devMode'] = false;
        $this->ugm->init($props);
        $this->props = $props;
        $this->modx->lexicon->load('en:upgrademodx:default');
    }

    public function testInit() {

        /* Test with no versionlist file */
        /**  @var $InstallData array */
        /*$path = MODX_CORE_PATH . 'cache/upgrademodx/versionlist';
        @unlink( $path);
        $this->ugm->init($this->props);
        $this->assertTrue(is_string($this->ugm->latestVersion), implode("\n" , $this->ugm->getErrors()));
        $latest = $this->ugm->latestVersion;
        $this->assertNotEmpty($this->ugm->versionArray, implode("\n" , $this->ugm->getErrors()));
        $versionArray = $this->ugm->versionArray;*/

        /* Test with existing versionlist file */
        $this->ugm->init($this->props);
        /*$this->assertFileExists($path);
        $this->assertNotEmpty($this->ugm->latestVersion);
        $this->assertEquals($latest, $this->ugm->latestVersion);
        $this->assertNotEmpty($this->ugm->versionArray);

        $this->assertEquals($versionArray, $this->ugm->versionArray);


        require $path;
        $this->assertNotEmpty($InstallData);
        $this->assertEquals($versionArray, $InstallData);
        $latest = reset($InstallData);
        $this->assertEquals(substr($latest['name'], 16), $this->ugm->latestVersion);

        $this->assertEmpty($this->ugm->getErrors());*/

    }

    public function testgetJSONFromGitHub_fopen_6() {

        /* Avoid Timeout */
        $retVal = array();


        $retVal = $this->ugm->getJSONFromGitHub('fopen', 10, 3);


        fwrite(STDOUT, "\nReturn: " . $retVal);
        fwrite(STDOUT, "\nAttempts with fopen -- timeout = 6");

        $this->assertNotEmpty($retVal, implode("\n", $this->ugm->getErrors()));
    }

    public function testgetJSONFromGitHub_fopen_1() {

        // var_dump($retVal);

        /* Try to Force timeout */
        $retVal = $this->ugm->getJSONFromGitHub(1);
        $i = 1;
        while (!empty($retVal) && $i < 10) {
            $retVal = $this->ugm->getJSONFromGitHub(1, true);
            $i++;
        }
        fwrite(STDOUT, "\nAttempts with fopen -- timeout = 1: " . $i);
        /*if ($retVal === false) {
            echo "\nFailure -- ";
            $errors = @$this->ugm->getErrors();
            $this->assertNotEmpty($errors);
            fwrite(STDOUT, @implode(', ' , $errors));
        }*/
        if ($i < 10) {
            $this->assertEmpty($retVal, implode("\n", $this->ugm->getErrors()));
            $this->assertTrue(is_array($this->ugm->getErrors()));
            $this->assertNotEmpty($this->ugm->getErrors());
            fwrite(STDOUT, "\nErrors: " . implode("\n", $this->ugm->getErrors()));
        } else {
            $this->assertNotEmpty($retVal);
            $this->assertEmpty($this->ugm->getErrors());

        }
    }

    public function testGetJSONFromGitHub_curl_6() {
        /* Try with cURL */
        $retVal = array();

        $retVal = $this->ugm->getJSONFromGitHub('curl', 6, 3);
        fwrite(STDOUT, "\nReturn: " . $retVal);
        fwrite(STDOUT, "\nAttempts with cURL -- timeout = 6 \n");

        $this->assertNotEmpty($retVal, implode("\n", $this->ugm->getErrors()));
        $this->assertEmpty($this->ugm->getErrors());


        echo "\n" . $retVal;

        // var_dump($retVal);
    }

    public function testGetJSONFromGitHub_curl_1() {
        /* Try to Force timeout */
        $retVal = $this->ugm->getJSONFromGitHub(1);
        $i = 1;
        while (!empty($retVal) && $i < 10) {
            $retVal = $this->ugm->getJSONFromGitHub('curl', 1, 1);
            $i++;
        }
        fwrite(STDOUT,"\nAttempts with cURL -- timeout = 1: " . $i);
        if ($i == 10) {
            $this->assertNotEmpty($retVal);
            $this->assertEmpty($this->ugm->getErrors());

        } else {
            $this->assertEmpty($retVal, implode("\n", $this->ugm->getErrors()));
            $this->assertTrue(is_array($this->ugm->getErrors()));
            $this->assertNotEmpty($this->ugm->getErrors());
            fwrite(STDOUT, "\nErrors: " . implode("\n", $this->ugm->getErrors()));
            fwrite(STDOUT, "\nAttempts with cURL -- timeout = 1: " . $i);


        }

    }

    public function testfinalizeVersionArray() {

        $vl = $this->ugm->finalizeVersionArray($this->versionData, true, 5, '2.6.3-pl');
        $this->assertTrue(is_array($vl));
        $this->assertEquals(5, count($vl));

        foreach($vl as $version)  {
            $name = $version['name'];
            // echo "\n" . $name;
            $this->assertTrue(strpos($name, 'pl') !== false);
        }

        // var_dump($vl);
        /* Try again with $plOnly false */
        $vl = $this->ugm->finalizeVersionArray($this->versionData, false, 7, '2.6.3-pl');
        $this->assertTrue(is_array($vl));
        $this->assertEquals(7, count($vl));

        $found = false;
        foreach ($vl as $version) {
            $name = $version['name'];
            // echo "\n" . $name;

            if (strpos($name, 'pl') === false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);

        $vl = $this->ugm->finalizeVersionArray($this->versionData, true, 5, '2.2.11-pl');
        $this->assertEquals(18, count($vl));

        $vl = $this->ugm->finalizeVersionArray($this->versionData, true, 5, '2.2.12-pl');
        $this->assertEquals(16, count($vl));

        $vl = $this->ugm->finalizeVersionArray($this->versionData, true, 5, '2.2.0-pl');
        $this->assertEquals(29, count($vl));
        /* Try with actual data from GitHub - fopen */
        $vl = $this->ugm->getJSONFromGitHub('fopen', 6, 3);
        // var_dump($vl);
        $this->assertNotEmpty($vl, implode("\n", $this->ugm->getErrors()));
        $vl = $this->ugm->finalizeVersionArray($vl, true, 6, '2.6.3-pl');
        $this->assertEquals(6, count($vl));

        /* Try with actual data from GitHub  - cURL*/
        $vl = $this->ugm->getJSONFromGitHub('curl', 6, 3);
        // var_dump($vl);
        $this->assertNotEmpty($vl, implode("\n", $this->ugm->getErrors()));
        $vl = $this->ugm->finalizeVersionArray($vl, true, 7, '2.6.3-pl');
        $this->assertEquals(7, count($vl));

        /* Try with older version */
        $vl = $this->ugm->getJSONFromGitHub('curl', 6, 3);
        // var_dump($vl);
        $this->assertNotEmpty($vl, implode("\n", $this->ugm->getErrors()));
        $vl = $this->ugm->finalizeVersionArray($vl, true, 7, '2.4.4-pl'); //xxx
        $this->assertTrue(count($vl) >= 10);

        /* Try with older, non-existent version */
        $vl = $this->ugm->getJSONFromGitHub('curl', 6, 3);
        // var_dump($vl);
        $this->assertNotEmpty($vl, implode("\n", $this->ugm->getErrors()));
        $vl = $this->ugm->finalizeVersionArray($vl, true, 7, '2.5.5-pl'); //xxx
        $this->assertTrue(count($vl) >= 10);


    }

    public function testUpdateVersionlistFile() {
        /** @var $InstallData array  */
        $path = $this->ugm->versionListPath . 'versionlist';
        if (file_exists($path)) {
           unlink($path);
        }
        $data = $this->versionData;
        $versionArray = $this->ugm->finalizeVersionArray($data);
        $this->ugm->updateVersionlistFile($versionArray);
        require($path);
        $this->assertTrue(is_array($InstallData));
        $x = file_get_contents($path);
        $this->assertNotEmpty($x);
        $this->assertTrue(strpos($x, '$InstallData') !== false);
    }

    public function testWriteScriptFile() {
        $path = MODX_BASE_PATH . 'upgrade.php';
        $this->ugm->writeScriptFile();
        $this->assertFileExists($path);
        $c = file_get_contents($path);
        $this->assertNotEmpty($c);
        $this->assertTrue(strpos($c, '[[') === false);
        $this->assertTrue(strpos($c, 'MODX Revolution') !== false);

        unlink($path);
        $this->assertFileNotExists($path);
        $this->ugm->writeScriptFile();
        $this->assertFileExists($path);
        $c = file_get_contents($path);
        $this->assertNotEmpty($c);
        $this->assertTrue(strpos($c, '[[') === false);
        $this->assertTrue(strpos($c, 'MODX Revolution') !== false);

    }

    public function testDownloadableCurl() {
        /* Try with cURL */
        $version = '2.4.0-pl';
        echo "\n" . $version;
        $retVal = $this->ugm->downloadable($version, 'curl', 7, 6);
        $this->assertTrue($retVal, implode("\n", $this->ugm->getErrors()));
    }

    public function testDownloadableCurlFail() {
        /* Should fail */
        $version = '5.5.5-pl';
        $retVal = $this->ugm->downloadable($version, 'curl', 1, 1);
        $this->assertFalse($retVal, $retVal);
        $e = $this->ugm->getErrors();
        $this->assertNotEmpty($e);
        echo implode("\n", $e);
    }
    public function testDownloadableFopen() {
        /* Try with fopen */
        $version = '2.4.0-pl';
        echo "\n" . $version;
        $retVal = $this->ugm->downloadable($version, 'fopen', 6, 6);
        $this->assertTrue($retVal, implode("\n", $this->ugm->getErrors()));
    }

    public function testDownloadableFopenFail() {
        /* Should fail */
        $version = '5.5.5-pl';
        $retVal = $this->ugm->downloadable($version, 'fopen', 1, 1);
        $this->assertFalse($retVal);
        $e = $this->ugm->getErrors();
        $this->assertNotEmpty($e);
        echo implode("\n", $e);

    }

    public function testTimeToCheck() {
        $t = time();
        $values = array(
             array(
                 'time' => $t,
                 'interval' => '+1 week',
                 'expected' => false,
             ),
            array(
                'time' => $t - 62,
                'interval' => '+61 seconds',
                'expected' => true,
            ),
        );

         foreach ($values as $a) {
             $retVal = $this->ugm->timeToCheck(strftime('%Y-%m-%d %H:%M:%S', $a['time']), $a['interval']);
             $expected = $a['expected'];
             $this->assertEquals($expected, $retVal);
         }
    }

    public function testUpgradeAvailable() {

        $currentVersion = '2.4.0-pl';
        $retVal =  $this->ugm->upgradeAvailable($currentVersion, true, 5, 'curl');
        $this->assertTrue($retVal, implode("\n", $this->ugm->getErrors()));

        /* This has to be updated to the latest version of MODX before testing */
        $currentVersion = '2.6.4-pl';
        $retVal = $this->ugm->upgradeAvailable($currentVersion, true, 5, 'curl');
        $this->assertFalse($retVal, 'Set to latest version of MODX!');

        // $path = MODX_CORE_PATH . 'cache/upgrademodx/versionlist';
        $path = $this->ugm->versionListPath . 'versionlist';
        if (file_exists($path)) {
            unlink($path);
        }

        $currentVersion = '2.4.0-pl';
        $retVal = $this->ugm->upgradeAvailable($currentVersion, true, 5, 'curl');
        $this->assertTrue($retVal, 'Set to latest version of MODX!');

        if (file_exists($path)) {
            unlink($path);
        }


        /* This has to be updated to the latest version of MODX before testing */
        $currentVersion = '2.6.4-pl';
        $retVal = $this->ugm->upgradeAvailable($currentVersion, true, 5, 'curl');
        $this->assertFalse($retVal, implode("\n", $this->ugm->getErrors()));

        $this->assertTrue(file_exists($path), implode("\n", $this->ugm->getErrors()) );

        }


}
