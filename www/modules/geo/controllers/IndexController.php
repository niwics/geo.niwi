<?php
namespace Gorazd\Geo;
use \Gorazd\System as Sys;

/**
 * @author niwi.cz
 * @date 25.11.2014
 */
class IndexController extends \Gorazd\Virtual\MainController
{
    /** Indicator for auto-adding Forms tails and spice */
    public $allowFormsPatterns = true;
    
    private $form;

    /**
     * Prepares data to display.
     */
    protected function prepareData()
    {
        $categoriesForm = new \Gorazd\FormsBasic\FormBuilder();
        $categoriesForm->fieldPrefix = "";
        $categoriesForm->checkDoubleSubmit = false;
        $categoriesForm->method = "GET";
        $categoriesForm->targetAction = "/vypis";

        $res = Sys\Db::select("c.id", "c.name", array("COUNT(s.id) AS itemsCount"))
            ->from("geo_category", "c")
            ->leftJoin("geo_symbol", "s", "c.id = s.categoryId")
            ->groupBy("c.id")
            ->orderBy("c.id")
            ->query();
        if (!$res)
            return err('Chyba při SQL dotazu', 3);

        $items = "";
        while ($row = $res->fetch_assoc())
        {
            $countString = Sys\Utils::wordForm($row['itemsCount'], array("značka", "značky", "značek"), false);
            $items .= <<<EOT
        <li>
            <input type="checkbox" class="gsfv-categoryId" name="gsfv-categoryId[]" value="{$row['id']}">
            <a href="/vypis?andOr=AND&gsfo-categoryId[]=EQ&gsfv-categoryId[]={$row['id']}">{$row['name']}</a>
            <span class="items-count">(<b>{$row['itemsCount']}</b> {$countString})</span>
EOT;
        }
        $categoriesForm->addHidden("andOr", "OR");
        $categoriesForm->addHtml("<ul id=\"category-filter\">" . $items . "</ul>");
        $categoriesForm->addSubmit("Zobraz vybrané");

        $this->customContent .= <<<EOT
        <div class="cell">
            <h2>Vyhledávání podle kategorií</h2>
            {$categoriesForm}
        </div>
        <div class="cell">
            <h2>Vyhledávání podle klíčových slov</h2>
            {$searchForm}
        </div>
EOT;
    }
}

?>
