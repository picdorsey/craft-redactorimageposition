<?php
/**
 * Redactor Image Position plugin for Craft CMS
 *
 * Adds position field to Redactor image edit modal.
 *
 * --snip--
 * Craft plugins are very much like little applications in and of themselves. We’ve made it as simple as we can,
 * but the training wheels are off. A little prior knowledge is going to be required to write a plugin.
 *
 * For the purposes of the plugin docs, we’re going to assume that you know PHP and SQL, as well as some semi-
 * advanced concepts like object-oriented programming and PHP namespaces.
 *
 * https://craftcms.com/docs/plugins/introduction
 * --snip--
 *
 * @author    Nicholas O'Donnell
 * @copyright Copyright (c) 2016 Nicholas O'Donnell
 * @link      http://nicholasodo.com
 * @package   RedactorImagePosition
 * @since     1.0.0
 */

namespace Craft;

class RedactorImagePositionPlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init()
    {
        parent::init();
        if (craft()->request->isCpRequest()) {
            $this->_renderCSS();
            $this->_renderJS();
        }
    }

    /**
     * Returns the user-facing name.
     *
     * @return mixed
     */
    public function getName()
    {
         return Craft::t('Redactor Image Position');
    }

    /**
     * Plugins can have descriptions of themselves displayed on the Plugins page by adding a getDescription() method
     * on the primary plugin class:
     *
     * @return mixed
     */
    public function getDescription()
    {
        return Craft::t('Adds position field to Redactor image edit modal.');
    }

    /**
     * Plugins can have links to their documentation on the Plugins page by adding a getDocumentationUrl() method on
     * the primary plugin class:
     *
     * @return string
     */
    public function getDocumentationUrl()
    {
        return 'https://github.com/nicholasodo/craft-redactorimageposition/blob/master/README.md';
    }

    /**
     * Plugins can now take part in Craft’s update notifications, and display release notes on the Updates page, by
     * providing a JSON feed that describes new releases, and adding a getReleaseFeedUrl() method on the primary
     * plugin class.
     *
     * @return string
     */
    public function getReleaseFeedUrl()
    {
        return 'https://raw.githubusercontent.com/nicholasodo/craft-redactorimageposition/master/releases.json';
    }

    /**
     * Returns the version number.
     *
     * @return string
     */
    public function getVersion()
    {
        return '1.0.0';
    }

    /**
     * As of Craft 2.5, Craft no longer takes the whole site down every time a plugin’s version number changes, in
     * case there are any new migrations that need to be run. Instead plugins must explicitly tell Craft that they
     * have new migrations by returning a new (higher) schema version number with a getSchemaVersion() method on
     * their primary plugin class:
     *
     * @return string
     */
    public function getSchemaVersion()
    {
        return '1.0.0';
    }

    /**
     * Returns the developer’s name.
     *
     * @return string
     */
    public function getDeveloper()
    {
        return 'Nicholas O\'Donnell';
    }

    /**
     * Returns the developer’s website URL.
     *
     * @return string
     */
    public function getDeveloperUrl()
    {
        return 'http://nicholasodo.com';
    }

    /**
     * Returns whether the plugin should get its own tab in the CP header.
     *
     * @return bool
     */
    public function hasCpSection()
    {
        return false;
    }

    /**
     * Called right before your plugin’s row gets stored in the plugins database table, and tables have been created
     * for it based on its records.
     */
    public function onBeforeInstall()
    {
    }

    /**
     * Called right after your plugin’s row has been stored in the plugins database table, and tables have been
     * created for it based on its records.
     */
    public function onAfterInstall()
    {
        craft()->redactorImagePosition->addRedactorPlugin();
    }

    /**
     * Called right before your plugin’s record-based tables have been deleted, and its row in the plugins table
     * has been deleted.
     */
    public function onBeforeUninstall()
    {
    }

    /**
     * Called right after your plugin’s record-based tables have been deleted, and its row in the plugins table
     * has been deleted.
     */
    public function onAfterUninstall()
    {
    }

    /**
     * Defines the attributes that model your plugin’s available settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return [
            'figureConfig' => [
                AttributeType::Mixed,
                'label' => 'Figure config settings',
                'default' => '[["figureWrap","<figure class=\"c-figure c-figure--inline\"><\/figure>"],["figureLeft","c-figure--left"],["figureRight","c-figure--right"],["figureFull","c-figure--full"],["imageClass","c-figure__image"],["captionClass","c-figure__caption"]]'
            ]
        ];
    }

    /**
     * Returns the HTML that displays your plugin’s settings.
     *
     * @return mixed
     */
    public function getSettingsHtml()
    {
       return craft()->templates->render('redactorimageposition/RedactorImagePosition_Settings', array(
           'settings' => $this->getSettings()
       ));
    }

    /**
     * If you need to do any processing on your settings’ post data before they’re saved to the database, you can
     * do it with the prepSettings() method:
     *
     * @param mixed $settings  The Widget's settings
     *
     * @return mixed
     */
    public function prepSettings($settings)
    {
        // Modify $settings here...

        return $settings;
    }

    /**
     * Renders CP css
     */
    private function _renderCSS()
    {
        craft()->templates->includeCssResource('redactorimageposition/css/style.css');
    }

    /**
     * Renders CP JS
     */
    private function _renderJS()
    {
        $figureConfigFromSettings = $this->getSettings()->figureConfig;

        craft()->templates->includeJs('
            var figureConfigFromSettings = ' . json_encode($figureConfigFromSettings) . ';
            var figureConfig = [];
            figureConfigFromSettings.forEach(function (element, index, array) {
                figureConfig[element[0]] = element[1];
            });
        ');
        craft()->templates->includeJsResource('redactorimageposition/js/script.js');
    }

}