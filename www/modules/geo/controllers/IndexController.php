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
        $searchForm->fieldPrefix = "";
        $searchForm->checkDoubleSubmit = false;
        $searchForm->separateSubmitButton = true;
        $searchForm->method = "GET";
        $searchForm->targetAction = "/vypis";
        $searchForm->addHidden("andOr", "OR");
        $searchForm->addInput("search-string", "Zadej slovo nebo frázi");
        $searchForm->addHtml("Hledej v:");
        $searchForm->addCheckbox("name", "Název", true, false);
        $searchForm->addCheckbox("number", "Číslo znaku", true, false);
        $searchForm->addCheckbox("measure", "Zaměření v terénu", true, false);
        $searchForm->addCheckbox("draw", "Zakreslení do mapy", true, false);
        $searchForm->addCheckbox("description", "Popis", true, false);
        $searchForm->addSubmit("Zobraz vybrané");

        $this->customContent .= <<<EOT
        <div class="cell">
            <h2>Vyhledávání podle kategorií</h2>
            {$mainCategoriesForm}
        </div>
        <div class="cell">
            <h2>Vyhledávání podle klíčových slov</h2>
            {$searchForm}
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
            $countString = Sys\Utils::wordForm($row['itemsCount'], array("značka", "značky", "značek"), false);
            $filterItems .= <<<EOT
        <li>
            <input type="checkbox" class="gsfv-categoryId" name="gsfv-categoryId[]" value="{$row['id']}">
            <a href="/{$link}?andOr=AND&gsfo-categoryId[]=EQ&gsfv-categoryId[]={$row['id']}">{$row['name']}</a>
EOT;
            if ($link == 'vypis')
                $filterItems .= ' <span class="items-count">(<b>' . $row['itemsCount'] . '</b> '. $countString . ')</span>';
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
}

?>
