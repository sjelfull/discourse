<?php
/**
 * Discourse plugin for Craft CMS
 *
 * Discourse Controller
 *
 * @author    Superbig
 * @copyright Copyright (c) 2017 Superbig
 * @link      https://superbig.co
 * @package   Discourse
 * @since     1.0.0
 */

namespace Craft;

use Cviebrock\DiscoursePHP\SSOHelper;

class DiscourseController extends BaseController
{

    /**
     * @var    bool|array Allows anonymous access to this controller's actions.
     * @access protected
     */
    protected $allowAnonymous = [ ];

    /**
     */
    public function actionIndex ()
    {
        craft()->userSession->requireLogin();

        $plugin   = craft()->plugins->getPlugin('discourse');
        $settings = $plugin->getSettings();
        $user     = craft()->userSession->getUser();

        $sso = new SSOHelper();

        // this should be the same in your code and in your Discourse settings:
        $secret = $settings->ssoSecret;
        $sso->setSecret($secret);

        // load the payload passed in by Discourse
        $payload   = craft()->request->getParam('sso');
        $signature = craft()->request->getParam('sig');

        // validate the payload
        if ( !($sso->validatePayload($payload, $signature)) ) {
            // invaild, deny
            header("HTTP/1.1 403 Forbidden");
            echo("Bad SSO request");
            craft()->end();
        }

        $nonce = $sso->getNonce($payload);

        // Required and must be unique to your application
        $userId = $user->id;

        // Required and must be consistent with your application
        $userEmail = $user->email;

        // Optional - if you don't set these, Discourse will generate suggestions
        // based on the email address

        $extraParameters = array(
            'username' => $user->username,
            'name'     => $user->getName(),
        );

        // build query string and redirect back to the Discourse site
        $query = $sso->getSignInString($nonce, $userId, $userEmail, $extraParameters);
        $this->redirect($settings->ssoEndpoint . '?' . $query);

    }
}