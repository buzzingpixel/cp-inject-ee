<?php // @codingStandardsIgnoreStart

// @codingStandardsIgnoreEnd

/**
 * @author TJ Draper <tj@buzzingpixel.com>
 * @copyright 2017 BuzzingPixel, LLC
 */

// Get add on json path
$addOnPath = realpath(__DIR__);
$addOnJsonPath = "{$addOnPath}/addon.json";

// Get the add on json file
$addOnJson = json_decode(file_get_contents($addOnJsonPath));

// Define constants
defined('CP_INJECT_VER') || define('CP_INJECT_VER', $addOnJson->version);

// Return info about the addon for ExpressionEngine
return array(
    'author' => $addOnJson->author,
    'author_url' => $addOnJson->authorUrl,
    'description' => $addOnJson->description,
    'docs_url' => $addOnJson->docsUrl,
    'name' => $addOnJson->label,
    'namespace' => $addOnJson->namespace,
    'settings_exist' => $addOnJson->settingsExist,
    'version' => $addOnJson->version
);
