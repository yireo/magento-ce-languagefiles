<?php 
/*
 * Script to automatically download Transifex translations for Magento
 * Sorry for the nineties-style of coding
 */

// Login credentials for Transifex
include_once 'private.php';

// Instantiate the Transifex class
include_once 'transifex.class.php';
$projectSlug = 'magento-ce-1x';
$transifex = new Transifex();
$transifex->setUsername($txUsername);
$transifex->setPassword($txPassword);
$transifex->setProjectSlug($projectSlug);

// Some other settings
$sleep = 0;
$completedPercentage = 10;
$lastUpdated = 60 * 60 * 24 * 30;

// Include the default language mapping
include_once 'mapping.php';

// Get the available resources
$resources = $transifex->getResources();

// Loop through the available languages in this project
$languages = $transifex->getLanguages();
foreach($languages as $language) {

    $languageCode = $language->language_code;

    // Check whether we will add new languages to the build list
    if(!in_array($languageCode, $languageCodes)) {

        // Flags
        $allowNew = false;
        $outOfDateCount = 0;

        // Do not allow this, if the language code does not match Magento locale-standards
        if(preg_match('/([a-z]{2})-([A-Z]{2})/', $languageCode) == false) {
            //continue;
        }
    
        // Check for the statistics for this new language
        foreach($resources as $resource) {

            $stats = $transifex->getResourceLanguageStats($resource, $languageCode);
            $completedPercentage = (int)$stats->completed;
            if($completedPercentage > 10) {
                $allowNew = true;
                break;
            }

            if($completedPercentage == 0 && strtotime($stats->last_update) < (time() + $lastUpdated)) {
                $outOfDateCount++;
            }
        }

        // Add this new language to the list
        if($allowNew) {
            $languageCodes[$languageCode] = $languageCode;
        }

        // Delete this language
        if($outOfDateCount > 64) {
            echo "Remove unused language $languageCode = $outOfDateCount\n";
            $transifex->deleteLanguage($languageCode);
        }
    } 
}

// Create the needed folders        
$base_dir = dirname(dirname(__FILE__));
@mkdir($base_dir.'/'.$projectSlug);
foreach($languageCodes as $languageLabel => $languageCode) {
    @mkdir($base_dir.'/'.$projectSlug.'/'.$languageLabel);
}

// Check for output
if(empty($resources)) {
    echo "No data received";
}

// Loop through the resources
foreach($resources as $resource) {

    // Loop through the languages
    foreach($languageCodes as $languageLabel => $languageCode) {

        // Start
        $resourceSlug = $resource->slug;
        echo "Processing resource '${resourceSlug}' / language '${languageCode}'\n";

        // Fetch the translation
        $txUrl = "/resource/${resourceSlug}/translation/${languageCode}/";
        $json = $transifex->call($txUrl);

        // Check for output
        if(empty($json)) {
            echo "ERROR: Empty feedback for resource '${resourceSlug}' / language '${languageCode}'\n";
        } elseif(empty($json->content)) {
            echo "ERROR: No content for resource '${resourceSlug}' / language '${languageCode}'\n";
        }
    
        // Parse the content
        $lines = explode("\n", $json->content);
        foreach($lines as $lineIndex => $line) {

            // Fix empty translations
            if(preg_match('/^\"(.*)\",\"\"$/', $line, $match)) {
                $line = '"'.$match[1].'","'.$match[1].'"';
            }
            $lines[$lineIndex] = $line;
        }
        $content = implode("\n", $lines);

        // Contruct the file
        $file = $base_dir.'/'.$projectSlug.'/'.$languageLabel.'/'.$resource->name.'.csv';
        file_put_contents($file, $content);

        if($sleep > 0) sleep((int)$sleep);
    }
}

