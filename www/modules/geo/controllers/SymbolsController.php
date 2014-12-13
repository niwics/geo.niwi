<?php
namespace Gorazd\Geo;
use \Gorazd\System as Sys;

/**
 * @author niwi.cz
 * @date 11.11.2014
 */
class SymbolsController extends \Gorazd\Virtual\MainController
{
    /** Indicator for auto-adding Forms tails and spice */
    public $allowFormsPatterns = true;
    
    private $form;

    /**
     * Prepares data to display.
     */
    protected function prepareData()
    {
        $this->form = new CatSymbolForm();
        ;
        $this->customContent .= $this->form->apply();
    }
}

?>
