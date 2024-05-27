<?php
namespace Gorazd\Geo;
use \Gorazd\System as Sys;

/**
 * Parent class for the all page templates (views).
 * @author niwi.cz
 * @date 11.11.2014
 */
class MainView extends Sys\View
{
    /**
     * Display page top part
     */
    protected function displayTop()
    {
        $root = ROOT_URL;
        $webTitle = ine($this->controller->websiteTitle, ' ');
        echo <<<EOT
    <div id="top">
        <a id="logo-link" href="{$root}" title="Hlavní strana webu{$webTitle}">
            <img src="/images/geo/logo.png">
        </a>
        <div id="top-right">
            <a id="top-link" href="{$root}" title="Hlavní strana webu{$webTitle}">
                {$this->controller->websiteTitle}
            </a>
            {$this->controller->cachedMenu}
        </div>
    </div>
EOT;
    }


    /**
     *
     */
    protected function displayBeforeContent($permitted)
    {
        echo '<div id="content">
      <div id="content-inner">' . "\n";
    }


    /**
     *
     */
    protected function displayAfterContent($permitted)
    {
        echo '</div>'; # EOF div#content-inner
        echo '</div>'; # EOF div#content
        if ($this->controller->displayLeftCol)
            $this->displayLeftColumn($permitted);
    }


    /**
     * Dispalys the page footer.
     */
    protected function displayFooter()
    {
        ?>
        <div id="footer">
            <div class="copyright">
                Databáze je určena jako výuková pomůcka. Databáze byla podpořena projektem Specifického vysokoškolského výzkumu na VUT v Brně č. 24930. <br>
                &copy; <a href="https://www.niwi.cz">niwi</a> <?php echo conf('copyrightYear'); ?> |
                <a href="https://gorazd.niwi.cz" title="Redakční systém Gorazd">RS Gorazd</a> |
                <a class="toggle-login" rev="Skrýt přihlašovací box" href="">Přihlášení</a>
            </div>
            <?php
            echo $this->controller->loginBoxString;
            $this->displayFooterAdmin();
            ?>
            <br class="clear invisible"/>
        </div>
    <?php
    }
}

?>
