<?php
/**
 * Pwny plugin for Craft CMS 5.x
 *
 * The plugin employs the k-Anonymity method to validate passwords against the Pwned Passwords API without compromising user privacy by revealing passwords to an external service.
 *
 * @link      https://simplygoodwork.com
 * @copyright Copyright (c) 2024 Good Work
 */

namespace simplygoodwork\pwny;

use Craft;
use craft\base\Model;
use craft\base\Plugin;
use craft\elements\User;
use simplygoodwork\pwny\models\Settings;
use simplygoodwork\pwny\services\Validate;
use yii\base\Event;
use yii\base\ModelEvent;

class Pwny extends Plugin
{
	// Static Properties
	// =========================================================================
	//
    /**
     * @var Pwny
     */
    public static Pwny $plugin;

	public static function config(): array
	{
		return [
			'components' => [
				'validate' => ['class' => Validate::class],
			],
		];
	}

	// Public Properties
	// =========================================================================
    /**
     * @var string
     */
    public string $schemaVersion = '1.0.0';

    /**
     * @var bool
     */
    public bool $hasCpSettings = true;
    /**
     * @var bool
     */
    public bool $hasCpSection = false;

	// Public Methods
	// =========================================================================
    public function init(): void
	{
        parent::init();
        self::$plugin = $this;

        Event::on(
            User::class,
            User::EVENT_BEFORE_VALIDATE,
            function(ModelEvent $event) {
                $user = $event->sender;

                // User has set a new password
                if ($user->newPassword) {
                    // Test against Have I Been Pwnd API
                    $error = $this->validate->apiCheck($user->newPassword);
                    $event->isValid = $error == false;

                    if (!$event->isValid) {
                        $user->addError('newPassword', $error);
                    }
                }
            }
        );
    }

    // Protected Methods
    // =========================================================================

    /**
     * Creates and returns the model used to store the plugin’s settings.
     *
     * @return Model|null
     */
    protected function createSettingsModel(): ?Model
    {
        return new Settings();
    }

    /**
     * Returns the rendered settings HTML, which will be inserted into the content
     * block on the settings page.
     *
     * @return string The rendered settings HTML
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'pwny/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
