<?php
namespace Gorazd\Virtual;
use \Gorazd\System as Sys;

/**
 * Parent class for the all page controllers. Provides extended functionallity for website controllers.
 * @author niwi.cz
 * @date 11.11.2014
 */
class MainController extends Sys\Controller
{
    // name of the view class
    protected $viewClass = '\Gorazd\Geo\MainView';
    public $displayLeftCol = false;
    public $displayRightCol = false;

    /**
     * Prepares data to display.
     */
    protected final function prepareMainData()
    {
        $this->addStyle('main', 'geo', 1);
        $this->addScript('main', 'geo');
        $this->customContent .= '';
    }
}

?>
