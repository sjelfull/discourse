<?php
/**
 * Discourse plugin for Craft CMS
 *
 * Single Sign-On for Discourse
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   Discourse
 * @since     1.0.0
 */

namespace Craft;

class DiscoursePlugin extends BasePlugin
{
    /**
     * @return mixed
     */
    public function init ()
    {
        parent::init();

        require_once __DIR__ . '/vendor/autoload.php';
    }

    /**
     * @return mixed
     */
    public function getName ()
    {
        return Craft::t('Discourse');
    }

    /**
     * @return mixed
     */
    public function getDescription ()
    {
        return Craft::t('Single Sign-On for Discourse');
    }

    /**
     * @return string
     */
    public function getDocumentationUrl ()
    {
        return 'https://github.com/sjelfull/discourse/blob/master/README.md';
    }

    /**
     * @return string
     */
    public function getReleaseFeedUrl ()
    {
        return 'https://raw.githubusercontent.com/sjelfull/discourse/master/releases.json';
    }

    /**
     * @return string
     */
    public function getVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getSchemaVersion ()
    {
        return '1.0.0';
    }

    /**
     * @return string
     */
    public function getDeveloper ()
    {
        return 'Superbig';
    }

    /**
     * @return string
     */
    public function getDeveloperUrl ()
    {
        return 'https://superbig.co';
    }

    /**
     * @return bool
     */
    public function hasCpSection ()
    {
        return false;
    }

    /**
     * @return array
     */
    protected function defineSettings ()
    {
        return array(
            'ssoEndpoint' => array( AttributeType::String, 'label' => 'Discourse SSO endpoint', 'default' => '' ),
            'ssoSecret'   => array( AttributeType::String, 'label' => 'Discourse SSO secret', 'default' => '' ),
        );
    }

    /**
     * @return mixed
     */
    public function getSettingsHtml ()
    {
        $path     = 'discourse';
        $path     = craft()->config->get('actionTrigger') . '/' . trim($path, '/');
        $loginUrl = UrlHelper::getSiteUrl($path);

        return craft()->templates->render('discourse/Discourse_Settings', array(
            'settings' => $this->getSettings(),
            'loginUrl' => $loginUrl,
        ));
    }
}