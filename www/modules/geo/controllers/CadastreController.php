<?php
namespace Gorazd\Geo;
use \Gorazd\System as Sys;

/**
 * @author niwi.cz
 * @date 11.12.2014
 */
class CadastreController extends \Gorazd\Virtual\MainController
{
    public $allowSpice = true;

    /**
     * Prepares data to display.
     */
    protected function prepareData()
    {
        $id = Sys\Utils::extractId(Sys\Env::$spice);
        $res = Sys\Db::select()
            ->from('geo_symbol')
            ->where("id = " . intval($id))
            ->query();
        if (!$res)
            return err('Chyba při SQL dotazu', 3);
        if ($res->num_rows == 0)
            return err('Požadovaný symbol nebyl nalezen', 3, "Symbol s ID '{$id}' nebyl nalezen.");
        $data = $res->fetch_assoc();

        Sys\Env::getController()->setTitle($data["name"] . " v katastru nemovitostí");

        if (!$data['cadastreCzText'])
            $data['cadastreCzText'] = "Popis nezadán.";
        if (!$data['cadastreSkText'])
            $data['cadastreSkText'] = "Popis nezadán.";

        $czImage = "";
        if ($data['cadastreCzImage'])
            $czImage = "<img src=\"" . Sys\Utils::url(ROOT_URL, '/images/geo/cadastre-cz', $data['cadastreCzImage']) . "\" alt=\"Zakreslení v katastru nemovitostí ČR\">";
        $skImage = "";
        if ($data['cadastreSkImage'])
            $skImage = "<img src=\"" . Sys\Utils::url(ROOT_URL, '/images/geo/cadastre-sk', $data['cadastreSkImage']) . "\" alt=\"Zakreslení v katastru nemovitostí SR\">";

        $out = <<<EOT
            <h2>Řešení v katastru nemovitostí České republiky</h2>
            <p>{$data['cadastreCzText']}</p>
            {$czImage}

            <h2>Řešení v katastri nehnuteľností Slovenské republiky</h2>
            <p>{$data['cadastreSkText']}</p>
            {$skImage}
EOT;


        $this->customContent .= $out;
    }
}

?>
