<?php
/**
 * Redactor Image Position plugin for Craft CMS
 *
 * RedactorImagePosition Service
 *
 * @author    Piccirilli Dorsey, Inc. (Nicholas O'Donnell)
 * @copyright Copyright (c) 2016 Piccirilli Dorsey, Inc. (Nicholas O'Donnell)
 * @link      http://picdorsey.com
 * @package   RedactorImagePosition
 * @since     1.0.3
 */

namespace Craft;

class RedactorImagePositionService extends BaseApplicationComponent
{
    /**
     * Adds redactor plugin to config json file
     */
    public function addRedactorPlugin()
    {
        $redactorConfigPath = (new PathService())->configPath . 'redactor/';

        foreach (IOHelper::getFiles($redactorConfigPath) as $file) {
            $contents = IOHelper::getFileContents($file);

            if (! $contents) {
                continue;
            }

            $json = json_decode($contents, true);

            if ($json == null) {
                continue;
            }

            if (array_key_exists('plugins', $json) && in_array('imagePosition', $json['plugins'])) {
                continue;
            }

            $json['plugins'][] = 'imagePosition';

            $encoded = json_encode($json, JSON_PRETTY_PRINT);

            IOHelper::writeToFile($file, $encoded);
        }
    }

}
