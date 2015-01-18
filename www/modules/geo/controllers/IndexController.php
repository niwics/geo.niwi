<?php
namespace Gorazd\Geo;
use \Gorazd\System as Sys;

/**
 * @author niwi.cz
 * @date 25.11.2014
 */
class IndexController extends \Gorazd\Virtual\MainController
{
    /**
     * Indicator for auto-adding Forms tails and spice
     */
    public $allowFormsPatterns = true;
    /**
     * @var array
     */
    private $categories = array();


    /**
     * Prepares data to display.
     */
    protected function prepareData()
    {
        $mainCategoriesForm = $this->makeCategoriesForm('vypis');
        $signsCategoriesForm = $this->makeCategoriesForm('vypis/znaky');
        $examplesCategoriesForm = $this->makeCategoriesForm('vypis/objekty');

        $searchForm = new \Gorazd\FormsBasic\FormBuilder("search-form");
        $searchForm->tableLayout = false;
        $searchForm->fieldPrefix = "";
        $searchForm->checkDoubleSubmit = false;
        $searchForm->separateSubmitButton = true;
        $searchForm->method = "GET";
        $searchForm->targetAction = "/vypis";
        $searchForm->addHidden("andOr", "OR");
        $searchForm->addInput("search-string", "Zadej slovo nebo frázi:");
        $searchForm->addHtml("Hledej v:");
        $searchForm->addCheckbox("name", "Název", true, false);
        $searchForm->addCheckbox("number", "Číslo", true, false);
        $searchForm->addCheckbox("measure", "Zaměření v terénu", true, false);
        $searchForm->addCheckbox("draw", "Zákres do mapy", true, false);
        $searchForm->addCheckbox("description", "Popis", true, false);
        $searchForm->addSubmit("Zobraz vybrané");

        $mostVisitedString = "";
        foreach ($this->findMostVisitedSymbols() as $symbolData)
        {
            $mostVisitedString .= <<<EOT
            <a href="/vypis/{$symbolData['id']}" title="{$symbolData['name']} - {$symbolData['visits']}&times;"">
                <img src="/images/geo/example/index/{$symbolData['exampleImage']}" alt="{$symbolData['name']}">
            </a>
EOT;
        }

        $this->customContent .= <<<EOT
        <div class="cell">
            <h2>Vyhledávání podle kategorií</h2>
            {$mainCategoriesForm}
        </div>
        <div class="cell">
            <h2>Vyhledávání podle klíčových slov</h2>
            {$searchForm}
            <h2 id="most-visited-heading">Nejčastěji hledané znaky v poslední době:</h2>
            <div id="most-visited">
                {$mostVisitedString}
            </div>
        </div>
        <hr>
        <div class="cell bottom">
            <h2>Přehled kartografických znaků</h2>
            {$signsCategoriesForm}
        </div>
        <div class="cell bottom">
            <h2>Přehled ukázek objektů</h2>
            {$examplesCategoriesForm}
        </div>
EOT;
    }


    /**
     * Prepares data to display.
     */
    protected function makeCategoriesForm($link)
    {
        $form = new \Gorazd\FormsBasic\FormBuilder();
        $form->fieldPrefix = "";
        $form->checkDoubleSubmit = false;
        $form->separateSubmitButton = true;
        $form->method = "GET";
        $form->targetAction = '/'.$link;

        $filterItems = $signItems = $exampleItems = "";
        foreach ($this->getCategories() as $row)
        {
            $filterItems .= <<<EOT
        <li>
            <input type="checkbox" class="gsfv-categoryId" name="gsfv-categoryId[]" value="{$row['id']}">
            <a href="/{$link}?andOr=AND&gsfo-categoryId[]=EQ&gsfv-categoryId[]={$row['id']}">{$row['name']}</a>
EOT;
        }
        $form->addHidden("andOr", "OR");
        $form->addHtml("<ul id=\"category-filter\">" . $filterItems . "</ul>");
        $form->addSubmit("Zobraz vybrané");
        return $form;
    }


    /**
     * Prepares data to display.
     */
    protected function getCategories()
    {
        if ($this->categories)
            return $this->categories;

        # load from DB
        $res = Sys\Db::select("c.id", "c.name", array("COUNT(s.id) AS itemsCount"))
            ->from("geo_category", "c")
            ->leftJoin("geo_symbol", "s", "c.id = s.categoryId")
            ->groupBy("c.id")
            ->orderBy("c.id")
            ->query();
        if (!$res)
            return err('Chyba při SQL dotazu', 3);

        while ($row = $res->fetch_assoc())
            $this->categories[] = $row;

        return $this->categories;
    }


    /**
     * Prepares data to display.
     */
    protected function findMostVisitedSymbols()
    {
        # load from DB
        $res = Sys\Db::select('tailAndSpice', 'g.id', 'g.name', 'g.exampleImage', array('COUNT(*) AS visits'))
            ->from("visitPage", 'v')
            ->join('geo_symbol', 'g', 'v.tailAndSpice = g.id')
            ->where("menuItemId = (SELECT id FROM menuItem WHERE websiteId = ". Sys\Env::$websiteId ." AND url = 'vypis') AND `timestamp` >= DATE_SUB(NOW() , INTERVAL 3 MONTH )")
            ->groupBy("tailAndSpice")
            ->orderBy("COUNT(*) DESC")
            ->query();
        if (!$res)
            return err('Chyba při SQL dotazu', 3);

        $symbolsData = array();
        while ($row = $res->fetch_assoc())
        {
            if (ctype_digit($row['tailAndSpice']))   # count the detail only - throw all URL tails
            {
                $symbolsData[] = $row;
                # limit to 8 last symbols only
                if (count($symbolsData) == 8)
                    break;
            }
        }
        return $symbolsData;
    }
}

?>
