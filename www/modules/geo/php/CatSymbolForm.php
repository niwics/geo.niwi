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
    const PRINT_URL_TAIL = 'tiskova-verze';

    private $printStyle = false;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        parent::__construct('geo_symbol');

        if (Sys\Env::isActualTail(self::PRINT_URL_TAIL))
        {
            $this->printStyle = true;
            Sys\Env::getController()->addStyle('print', 'system', 2);
            Sys\Env::getController()->addStyle('print', null, 2);
        }


        if (Sys\Env::isActualTail("znaky") or Sys\Env::isActualTail("objekty"))
        {
            $this->paginationLimit = false;
            $this->orderBy = "categoryId, G.name";
        }
        else
        {
            $this->orderBy = "G.name";
        }
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
            'thumbnails' => array('list' => array(65, 65), 'detail' => array(310, 500), 'grid' => array(140, 180, true)),
            'maxLength' => 255,
            'targetWeb' => ROOT_URL,
            'targetDir' => '/modules/geo/images/symbol',
            'class' => 'main-center'
        ));
        $this->addField(array(
            'name' => 'exampleImage',
            'title' => 'Ukázka',
            'type' => 'image',
            'thumbnails' => array('list' => array(50, 65, true), 'detail' => array(310, 500), 'grid' => array(140, 180, true), 'index' => array(60, 90, true)),
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
            'thumbnails' => array('detail' => array(250, 250)),
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
            'thumbnails' => array('detail' => array(250, 250)),
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
            $titleDiv = ine($title, '<div class="name">', '</div>');
            $out .= <<<EOT
            <a href="{$detailLink}" title="{$title}">
                <img src="{$imgSrc}" alt="{$title}">
                {$titleDiv}
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

        // set the visit
        $res = Sys\Db::query(
            "INSERT INTO geo_symbolVisit (`date`, symbolId, visits)
             VALUES('". date('Y-m-d') ."', ". $this->rawVal("id") .", 1)
             ON DUPLICATE KEY UPDATE visits = visits+1"
        );
        if (!$res)
            return "";

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
        $standardString = "<span class=\"gray\">dle</span> <strong>{$this->val("standard")}</strong>";
        $categoryString = "<strong>{$this->val('categoryId')}</strong>";
        $exampleString = <<<EOT
    <div class="example subbox">
        <h2>Ukázka</h2>
        {$imgStrings['exampleImage']}
        {$moreExamples}
    </div>
EOT;
        $descriptionString = <<<EOT
    <div class="description subbox">
        <h2>Popis</h2>
        {$this->val("description")}
    </div>
EOT;
        $symbolBoxString = <<<EOT
        <div class="symbol subbox">
            <h2>Kartografický znak</h2>
            {$imgStrings['symbolImage']}
            <p class="note">Kartografický znak je zobrazen v obecném měřítku. Kóty udávají jeho rozměry na mapě v milimetrech.</p>
        </div>
EOT;
        $mapPositionString = "<h2>Umístění znaku na mapě</h2>\n&ndash; {$this->val("mapPosition")}";
        $mapOrientationString = "<h2>Orientace znaku na mapě</h2>\n&ndash; {$this->val("mapOrientation")}";
        $measureString = <<<EOT
    <div class="measure subbox">
        {$imgStrings['measureImage']}
        <h2>Zaměření v terénu</h2>
        <p>{$this->rawVal('measure')}</p>
        <br class="clear">
    </div>
EOT;
        $drawString = <<<EOT
    <div class="draw subbox">
        <h2>Zakreslení do mapy</h2>
        {$imgStrings['drawImage']}
        <p>{$this->rawVal("draw")}</p>
        <br class="clear">
    </div>
EOT;

        if (!$this->printStyle)
        {
            $discussionForm = new SymbolDiscussion($this->rawVal('id'), '', 'geo_symbolComment');
            $discussionForm->formFirst = false;
            $discussionForm->paginationLimit = false;
            $discussionForm->authorLinks = false;
            $discussionForm->handleActions();
            $discussion = '<h2 id="discussion">Diskuze k tomuto znaku</h2>' . $discussionForm;

            $out .= <<<EOT
<p class="top-info">
    {$standardString}<br>
    <span class="gray">Kategorie:</span> {$categoryString}
</p>

<div class="first-box detail-box">
    {$exampleString}
    {$descriptionString}
</div>

<div class="second-box detail-box">
    <div class="number subbox">
        <h2>Číslo znaku: <strong>{$this->val("number")}</strong></h2>
    </div>
    {$symbolBoxString}
    <div class="subbox">
        {$mapPositionString}
    </div>
    <div class="subbox">
        {$mapOrientationString}
    </div>
</div>

<div class="third-box detail-box">
    {$measureString}
    {$drawString}
    <div class="subbox">
        <h2><a href="/katastr-nemovitosti/{$cadastreUrl}">Řešení v KN ČR a SR</a></h2>
    </div>
</div>
{$discussion}
EOT;
        }
        else
        {
            $cadastreCzText = $this->rawVal('cadastreCzText');
            if (!$cadastreCzText)
                $cadastreCzText = "Popis nezadán.";
            $cadastreSkText = $this->rawVal('cadastreSkText');
            if (!$cadastreSkText)
                $cadastreSkText = "Popis nezadán.";

            # print style
            $out .= <<<EOT

<div class="first-box detail-box">
    <div class="subbox">
        <span class="gray">Číslo znaku:</span> <strong>{$this->val("number")}</strong><br>
        {$standardString}
    </div>
    {$symbolBoxString}
    <div class="subbox">
        {$mapPositionString}
        {$mapOrientationString}
    </div>
    {$measureString}
</div>

<div class="second-box detail-box">
    <div class="subbox">
        <span class="gray">Kategorie:</span><br>{$categoryString}
    </div>
    {$exampleString}
    {$descriptionString}
    {$drawString}
    <div class="subbox">
        <h2>Řešení v katastru nemovitostí ČR</h2>
        <p>{$cadastreCzText}</p>
        <h2>Řešení v katastri nehnuteľností SR</h2>
        <p>{$cadastreSkText}</p>
    </div>
</div>
EOT;

        }
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
            # printable/normal links
            if (Sys\Env::isActualTail(self::PRINT_URL_TAIL))
                $actionStrings[] = '<a href="'. Sys\Env::urlAppend(Sys\Env::$spice) .'" title="Normální verze stránky znaku" id="gsf-action-normal">Normální verze</a>';
            else
                $actionStrings[] = '<a href="'. Sys\Env::urlAppend(array(Sys\Env::$spice, self::PRINT_URL_TAIL)) .'" title="Tisková verze stránky znaku" id="gsf-action-print">Tisková verze</a>';
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


class SymbolDiscussion extends \Gorazd\Discussion\Discussion
{
    /**
     *
     */
    protected function sendMails()
    {
        $url = Sys\Env::$url;
        $author = '<strong>' . (Sys\Env::$user->isLogged() ? Sys\Env::$user->username : $_POST['gsf-author']) . '</strong>';
        $title = ine($_POST['gsf-title'], '<h2>', '</h2>');
        $content = ine($_POST['gsf-content'], '<p>', '</p>');
        $msg = <<<EOT
<p style="font-size: 0.8em">V diskuzi na <a href="{$url}" title="Diskuze">{$url}</a> přibyl nový komentář.</p>

Autor: {$author}<br />
{$title}
{$content}
EOT;

        return Sys\Utils::sendMail('PavlaAndelova@seznam.cz', 'Nový příspěvek v diskuzi', $msg, Sys\Env::$systemMail, true, null, false);
    }
}