<?php



namespace frontend\components;

/**
 * Description of MyFilesFactory
 *
 * @author abdullah
 */
class MyFilesFactory {
    public static function create($type)
    {
        if($type == 'google'){
            return new GoogleClient();
        }
    }
}
