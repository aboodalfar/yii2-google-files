<?php



namespace frontend\components;

/**
 * Description of InterfaceMyFiles
 *
 * @author abdullah
 */
interface InterfaceMyFiles {
    public function getClient();
    public function initDriveService();
    public function listFilesFolders();
}
