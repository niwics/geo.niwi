<?php
namespace Gorazd\Geo;
use \Gorazd\System as Sys;

/**
 * @author niwi.cz
 * @date 11.11.2014
 */
class SymbolsController extends \Gorazd\Virtual\MainController
{
    private $form;


    /**
     * May be overwritten in subclasses.
     * The order of added patterns is important - in the same order will be tested
     *  to match.
     */
    public function prepareUrlPatterns()
    {
        $this->addUrlPattern("znaky");
        $this->addUrlPattern("objekty");
        $this->prepareFormsPatterns();
    }

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
