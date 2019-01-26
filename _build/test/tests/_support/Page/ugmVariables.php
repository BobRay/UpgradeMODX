<?php
namespace Page;

class ugmVariables
{
    /*  include url of initial page */
    public static $managerUrl = 'manager/';

    /* Orphans Page URL */
    public static $orphansPage = 'manager/?a=index&namespace=orphans';

    /* Temporary category for change category test */
    public static $category = 'abOrphans';

    /* Name Prefix for objects */
    public static $namePrefix = 'aaOrphansTest';

    /* Save button for change category */
    public static $changeCategorySaveButton = "//button[contains(@class, 'x-btn-text') and text() = 'Save']";

    /* Confirm delete button */
    public static $deleteYesButton = "//button[contains(@class, 'x-btn-text') and text() = 'Yes']";

    /* Name of chunk containing ignore list */
    public static $ignoreChunk = 'OrphansIgnoreList';

    /* Content of empty ignore list Chunk */
    public static $ignoreChunkText = "OrphansIgnoreList\n";

    /* Add to ignore list context menu prompt */
    public static $addToIgnoreListContextOption = "//span[text() = 'Add to Ignore List']";


    public static function testTab($tab) {

    }

    /**
     * Basic route example for your current URL
     * You can append any additional parameter to URL
     * and use it in tests like: Page\Edit::route('/123-post');
     */
    public static function route($param)
    {
        return static::$managerUrl.$param;
    }


}
