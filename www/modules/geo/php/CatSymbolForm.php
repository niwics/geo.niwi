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


        if (Sys\Env::isActualTail("znaky") or Sys\Env::isActualTail("objekty"))
        {
            $this->paginationLimit = false;
            $this->orderBy = "categoryId, G.name";
        }
        else
        {
            $this->orderBy = "G.name";
        }
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
        $this->addIdField(array("listable" => false));
        $this->addField(array(
            'name' => 'name',
            'title' => 'Název',
            'type' => 'string',
            'maxLength' => 48,
            'required' => true,
            'linkUrl' => true
        ));
        $this->addField(array(
            'name' => 'categoryId',
            'title' => 'Kategorie',
            'type' => 'int',
            'required' => true,
            'class' => 'category',
            /* References */
            'refJoinColumn' => 'geo_category.id',
            'refDisplayColumn' => 'name'
        ));
        $this->addField(array(
            'name' => 'number',
            'title' => 'Číslo',
            'type' => 'string',
            'maxLength' => 10,
            'required' => true,
            'class' => 'main-center'
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
            'name' => 'symbolImage',
            'title' => 'Znak',
            'type' => 'image',
            'thumbnails' => array('list' => array(55, 45), 'detail' => array(310, 500), 'grid' => array(140, 140, true)),
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/symbol',
            'class' => 'main-center'
        ));
        $this->addField(array(
            'name' => 'exampleImage',
            'title' => 'Ukázka',
            'type' => 'image',
            'thumbnails' => array('list' => array(55, 45), 'detail' => array(310, 500), 'grid' => array(140, 140, true)),
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/example',
            'class' => 'main-center'
        ));
        $this->addField(array(
            'name' => 'measureImage',
            'title' => 'Zaměření',
            'type' => 'image',
            'thumbnails' => array('detail' => array(180, 220)),
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/measure',
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'drawImage',
            'title' => 'Zakreslení',
            'type' => 'image',
            'thumbnails' => array('detail' => array(180, 220)),
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/draw',
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'cadastreCzText',
            'title' => 'Řešení v katastru CZ - text',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'cadastreSkText',
            'title' => 'Řešení v katastru SK - text',
            'type' => 'text'
        ));
        $this->addField(array(
            'name' => 'cadastreCzImage',
            'title' => 'Řešení v katastru CZ',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/cadastre-cz',
            /* Visibility */
            'listable' => false
        ));
        $this->addField(array(
            'name' => 'cadastreSkImage',
            'title' => 'Řešení v katastru SK',
            'type' => 'image',
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/cadastre-sk',
            /* Visibility */
            'listable' => false
        ));
    }


    /**
     * Hanldes list action - displays table of items.
     */
    protected function displayList()
    {
        if (Sys\Env::isActualTail("znaky"))
        {
            Sys\Env::getController()->setTitle('Přehled znaků', null);
            return $this->displayExamplesList(true);
        }
        elseif (Sys\Env::isActualTail("objekty"))
        {
            Sys\Env::getController()->setTitle('Přehled objektů', null);
            return $this->displayExamplesList(false);
        }
        else
            return parent::displayList();
    }


    /**
     * Hanldes list action - displays table of items.
     */
    protected function displayExamplesList($areSymbols)
    {
        $fieldName = $areSymbols ? "symbolImage" : "exampleImage";
        $out = "";

        // cycle for rows
        $previousCategory = null;
        while ($this->fetchNextRow())
        {
            if ($previousCategory != $this->dbData["categoryId"])
            {
                $out .= '<h2>'.$this->dbData["categoryId_REFVAL"].'</h2>';
                $previousCategory = $this->dbData["categoryId"];
            }
            $detailLink = $this->getDetailUrl($this->dbData);
            $imgSrc = $this->getField($fieldName)->imgVal(false, "grid");
            $title = $this->rawVal("name");
            $out .= <<<EOT
            <a href="{$detailLink}" title="{$title}">
                <img src="{$imgSrc}" alt="' . $this->title . '"><br>
                {$title}
            </a>
EOT;
        }
        return ine($out, '<div id="examples">', '</div>');
    }


    /**
     * Overwritten for generating.
     */
    protected function displayDetail()
    {
        if (!$this->_result or $this->_result->num_rows == 0)
            return "";

        Sys\Env::getController()->setTitle($this->rawVal("name"), null);
        $out = "";

        # prepare all images string for the output
        $images = array(
            "exampleImage" => "Ukázka znaku v terénu",
            "symbolImage" => "Obrázek kartografického znaku",
            "measureImage" => "Obrázek zakreslení znaku do mapy",
            "drawImage" => "Obrázek zakreslení znaku do mapy"
        );
        foreach ($images as $imageName => $altText)
        {
            $imgStrings[$imageName] = "";
            if ($this->getField($imageName)->imgVal())  # uncommented link breaks the responsivity of image
                $imgStrings[$imageName] = <<<EOT
            <!--<a href= "{$this->getField($imageName)->imgVal()}" class="gsf_thumb" title="{$altText}">-->
                <img src="{$this->getField($imageName)->imgVal()}" alt="{$altText}">
            <!--</a>-->
EOT;
        }

        #$moreExamples = "<a href title=\"Zobrazit další ukázky\">Další ukázky</a>";#TODO


        $cadastreUrl = Sys\Utils::urlize($this->rawVal("name"), $this->rawVal("id"));

        $out .= <<<EOT
        <p class="top-info">
            <span class="gray">dle</span> {$this->val("standard")}<br>
            <span class="gray">Kategorie:</span> <strong>{$this->val("categoryId")}</strong>
        </p>

        <div class="first-box detail-box">
            <div class="example subbox">
                <h3>Ukázka</h3>
                {$imgStrings['exampleImage']}
                {$moreExamples}
            </div>
            <div class="description subbox">
                <h3>Popis</h3>
                {$this->val("description")}
            </div>
        </div>

        <div class="second-box detail-box">
            <div class="number subbox">
                <h3>Číslo znaku: <strong>{$this->val("number")}</strong></h3>
            </div>
            <div class="symbol subbox">
                <h3>Kartografický znak</h3>
                {$imgStrings['symbolImage']}
                <p>Kartografický znak je zobrazen v obecném měřítku. Kóty udávají jeho rozměry na mapě v milimetrech.</p>
            </div>
            <div class="subbox">
                <h3>Umístění znaku na mapě</h3>
                &ndash; {$this->val("mapPosition")}
            </div>
            <div class="subbox">
                <h3>Orientace znaku na mapě</h3>
                &ndash; {$this->val("mapOrientation")}
            </div>
        </div>

        <div class="third-box detail-box">
            <div class="measure subbox">
                <h3>Zaměření v terénu</h3>
                {$imgStrings['measureImage']}
                <p>{$this->rawVal("measure")}</p>
                <br class="clear">
            </div>
            <div class="draw subbox">
                <h3>Zakreslení do mapy</h3>
                {$imgStrings['drawImage']}
                <p>{$this->rawVal("draw")}</p>
                <br class="clear">
            </div>
            <div class="subbox">
                <h3><a href="/katastr-nemovitosti/{$cadastreUrl}">Řešení v KN ČR a SR</a></h3>
            </div>
        </div>
EOT;
        return $out;
    }


    /**
     * Do not display action links in the detail.
     */
    public function renderInfoBar()
    {
        if ($this->getDisplayAction() == DETAIL)
        {
            $actionStrings = array();
            $editActionString = $this->renderLinkForAction(EDIT);
            if ($editActionString)
                $actionStrings[] = $editActionString;
            $deleteActionString = $this->renderLinkForAction(DELETE);
            if ($deleteActionString)
                $actionStrings[] = $deleteActionString;
            if (count($actionStrings))
                return '<div id="gsf-info-bar" class="detail">' . implode(" | ", $actionStrings) . '</div>';
            return '';
        }
        else    # default
            return parent::renderInfoBar();
    }


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
