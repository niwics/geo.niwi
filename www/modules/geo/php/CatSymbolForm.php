<?php
namespace Gorazd\Geo;

use \Gorazd\System as Sys;

/**
 * @author mira - niwi (miradrda@volny.cz)
 * @date 11.11.2014
 */
class CatSymbolForm extends \Gorazd\Forms\Form
{
    /**
     * Permission level for editing places.
     */
    const ADMIN_PERMISSION = 3;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('geo_symbol');

        Sys\Env::getController()->addScript('geo');
        Sys\Env::getController()->addStyle('geo');
        /*
        // overwrite
        $this->titleField = 'clientSurname';
        $this->defaultSpiceAction = EDIT;
        $this->setOrderBy('date DESC');
        // fields aggregates
        foreach (self::$options as $option => $_dummy)
            $this->fieldAggregates[$option] = $option . 'Select';

        // add help texts to the JS
        $arrayForJs = array();
        foreach (array_values(self::$options) as $oneFieldOptions)
            $arrayForJs[] = array_values($oneFieldOptions);
        Sys\Env::getController()->appendJs("var spc_options = " . json_encode($arrayForJs) . ";\n");
        */
        $this->prepareFields();
    }


    /**
     *
     */
    protected function prepareFields()
    {
        $this->addIdField();
        $this->addField(array(
            'name' => 'name',
            'title' => 'Název',
            'type' => 'string',
            'maxLength' => 32,
            'required' => true
        ));
        $this->addField(array(
            'name' => 'categoryId',
            'title' => 'Kategorie',
            'type' => 'int',
            'required' => true,
            /* References */
            'refJoinColumn' => 'geo_category.id',
            'refDisplayColumn' => 'name'
        ));
        $this->addField(array(
            'name' => 'number',
            'title' => 'Číslo znaku',
            'type' => 'string',
            'maxLength' => 4,
            'required' => true
        ));
        $this->addField(array(
            'name' => 'standard',
            'title' => 'Norma',
            'type' => 'string',
            'default' => 4,
            'possibilities' => array('csn' => 'ČSN 01 3411', 'rwe' => 'RWE', 'szdc' => 'SŽDC'),
            'required' => true,
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'description',
            'title' => 'Popis',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'measure',
            'title' => 'Zaměření v terénu',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'draw',
            'title' => 'Zakreslení do mapy',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'mapPosition',
            'title' => 'Umístění na mapě',
            'type' => 'string',
            'maxLength' => 6,
            'possibilities' => array('point' => 'do bodu', 'line' => 'do linie', 'center' => 'do středu plochy'),
            'required' => true,
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'mapOrientation',
            'title' => 'Orientace na mapě',
            'type' => 'string',
            'maxLength' => 13,
            'possibilities' => array('perpendicular' => 'kolmo k linii', 'nord' => 'na sever', 'along' => 'podél prvku', 'topography' => 'podle polohopisu', 'frame' => 's mapovým rámem'),
            'required' => true,
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'exampleImage',
            'title' => 'Ukázka (obrázek)',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/example'
        ));
        $this->addField(array(
            'name' => 'symbolImage',
            'title' => 'Znak (obrázek)',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/symbol'
        ));
        $this->addField(array(
            'name' => 'measureImage',
            'title' => 'Zaměření (obrázek)',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/measure',
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'drawImage',
            'title' => 'Zakreslení (obrázek)',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/draw',
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'cadastreCzText',
            'title' => 'Řešení v katastru CZ',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'cadastreSkText',
            'title' => 'Řešení v katastru SK',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'cadastreCzImage',
            'title' => 'Řešení v katastru CZ (obrázek)',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/cadastre-cz',
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'cadastreSkImage',
            'title' => 'Řešení v katastru SK (obrázek)',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/cadastre-sk',
            /* Visibility */
            'listable' => false
        ));
    }


    /**
     * Overwritten for generating.
     */
//    protected function displayDetail()
//    {
//        // prepare all data for the template
//        $filename = $this->rawVal("clientSurname") . " " . $this->rawVal("clientName") . " - vyš. " . $this->val("date");
//        $fullName = $this->rawVal("clientName") . " " . $this->rawVal("clientSurname");
//        $date = $this->val("date");
//
//        // Include classes
//        require_once(ROOT_PATH.'/modules/niwi/libs/tbs/tbs_class.php'); // Load the TinyButStrong template engine
//        require_once(ROOT_PATH.'/modules/niwi/libs/tbs/tbs_plugin_opentbs.php'); // Load the OpenTBS plugin
//
//        // Initialize the TBS instance
//        $TBS = new \clsTinyButStrong; // new instance of TBS
//        $TBS->Plugin(TBS_INSTALL, OPENTBS_PLUGIN); // load the OpenTBS plugin
//
//        $template = ROOT_PATH . '/modules/niwi/data/spc-template.docx';
//        $TBS->LoadTemplate($template, OPENTBS_ALREADY_UTF8); // Also merge some [onload] automatic fields (depends of the type of document).
//
//        // Output the result as a downloadable file (only streaming, no data saved in the server)
//        $TBS->Show(OPENTBS_DOWNLOAD, "result.docx"); // Also merges all [onshow] automatic fields.
//        // Be sure that no more output is done, otherwise the download file is corrupted with extra data.
//        exit();
//    }


    /**
     *
     */
//    protected function displayTableHeadSpecialCols()
//    {
//        return parent::displayTableHeadSpecialCols() . '<th class="actionButton"></th>';
//    }

    /**
     *
     */
//    protected function displayRowSpecialCols()
//    {
//        $rowId = $this->dbData[$this->linkField];
//        $out = parent::displayRowSpecialCols();
//        $out .= "<td><a href=\"". Sys\Env::urlAppend(array($rowId, "generovat")) ."\" class=\"generate\"></a></td>";
//        return $out;
//    }


    /**
     * Displays search action.
     * Overwritten - do not display anything.
     */
    protected function displaySearch()
    {
        return "";
    }


    /**
     * This functio is called automatically from handle().
     * It can be used for the last modification of allowed actions.
     * $this->loadRecord() can be called here if needed.
     */
    protected function checkAllowedActions()
    {
        if (Sys\Env::$user->getPermission() < self::ADMIN_PERMISSION)
            $this->allowedActions = array_diff($this->allowedActions, array(INSERT, EDIT, DELETE));
        return true;
    }
}