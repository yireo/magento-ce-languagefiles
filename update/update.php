<?php 
/*
 * Script to automatically download Transifex translations for Magento
 * Sorry for the nineties-style of coding
 */

// Login credentials for Transifex
include_once 'private.php';

// Project definitions
$project_slug = 'magento-ce-17'; // https://www.transifex.com/projects/p/magento-ce-17/
$language_codes = array(
    'de_DE' => 'de_DE',
    'tr_TR' => 'tr_TR',
    'nl_NL' => 'nl',
    'zh_TW' => 'zh_TW',
    'fr_FR' => 'fr',
    'he_IL' => 'he_IL',
    'ta_IN' => 'ta_IN',
    'ro_RO' => 'ro_RO',
);

// Create the needed folders        
$base_dir = dirname(dirname(__FILE__));
@mkdir($base_dir.'/'.$project_slug);
foreach($language_codes as $language_label => $language_code) {
    @mkdir($base_dir.'/'.$project_slug.'/'.$language_label);
}

// Make the call
$resources = getTransifexData("/resources/");

// Check for output
if(empty($resources)) {
    echo "No data received";
}

// Loop through the resources
foreach($resources as $resource) {

    // Loop through the languages
    foreach($language_codes as $language_label => $language_code) {

        // Start
        $resource_slug = $resource->slug;
        echo "Processing resource '${resource_slug}' / language '${language_code}'\n";

        // Fetch the translation
        $tx_url = "/resource/${resource_slug}/translation/${language_code}/";
        $json = getTransifexData($tx_url);

        // Check for output
        if(empty($json)) {
            echo "ERROR: Empty feedback for resource '${resource_slug}' / language '${language_code}'\n";
        } elseif(empty($json->content)) {
            echo "ERROR: No content for resource '${resource_slug}' / language '${language_code}'\n";
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
        $file = $base_dir.'/'.$project_slug.'/'.$language_label.'/'.$resource->name.'.csv';
        file_put_contents($file, $content);
        sleep(1);
    }
}

// Generic function to get data from the Transifex API
function getTransifexData($tx_url) {

    // Fetch global parameters
    global $tx_username;
    global $tx_password;
    global $project_slug;

    // Construct the command
    $tx_url = "https://www.transifex.com/api/2/project/${project_slug}".$tx_url;
    $cmd = "curl -s -L --user ${tx_username}:${tx_password} ${tx_url}";
    //echo $cmd."\n";

    // Make the call
    $rs = exec($cmd, $output);
    $output = implode("\n", $output);
    $json = json_decode($output);

    return $json;
}
