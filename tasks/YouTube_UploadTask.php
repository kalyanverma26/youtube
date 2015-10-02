<?php

namespace Craft;

/**
 * YouTube Upload Task.
 *
 * Upload video assets to YouTube.
 *
 * @author    Bob Olde Hampsink <b.oldehampsink@itmundi.nl>
 * @copyright Copyright (c) 2015, Itmundi
 * @license   MIT
 *
 * @link      http://github.com/boboldehampsink
 */
class YouTube_UploadTask extends BaseTask
{
    /**
     * Define settings.
     *
     * @return array
     */
    protected function defineSettings()
    {
        return array(
            'element'   => AttributeType::Mixed,
            'model'     => AttributeType::Mixed,
            'assets'    => AttributeType::Mixed,
        );
    }

    /**
     * Return description.
     *
     * @return string
     */
    public function getDescription()
    {
        return Craft::t('YouTube Upload');
    }

    /**
     * Return total steps.
     *
     * @return int
     */
    public function getTotalSteps()
    {
        // Get settings
        $settings = $this->getSettings();

        // Take a step for every asset
        return count($settings->assets);
    }

    /**
     * Run step.
     *
     * @param int $step
     *
     * @return string|bool
     */
    public function runStep($step)
    {
        // Set a max timeout eventually
        if ($timeout = craft()->config->get('taskTimeout')) {
            @set_time_limit($timeout);
        }

        // Get settings
        $settings = $this->getSettings();

        // Get asset
        $asset = craft()->assets->getFileById($settings->assets[$step]);

        // Verify if element still exists
        $element = craft()->elements->getElementById($settings->element->id);

        // Return process status
        return craft()->youTube->process($element, $asset, $settings->model->handle);
    }
}
