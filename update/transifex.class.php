<?php
class Transifex
{
    protected $username = null;

    protected $password = null;

    protected $projectSlug = null;

    public function getResources()
    {
        return $this->call('/resources/');
    }    

    public function getLanguages()
    {
        return $this->call('/languages/');
    }

    public function getResourceLanguageStats($resource, $languageCode)
    {
        $url = '/resource/'.$resource->slug.'/stats/'.$languageCode.'/';
        return $this->call($url);
    }

    public function deleteLanguage($languageCode)
    {
        return $this->call('/language/'.$languageCode.'/', 'DELETE');
    }

    // Generic function to get data from the Transifex API
    public function call($url, $request = 'GET')
    {
        // Construct the URL
        $url = 'https://www.transifex.com/api/2/project/'.$this->projectSlug.$url;

        // Construct the command
        $cmd = 'curl -s -L -X '.$request.' --user '.$this->username.':'.$this->password.' '.$url;
        //echo $cmd."\n";

        // Make the call
        $rs = exec($cmd, $output);
        $output = implode("\n", $output);
        $json = json_decode($output);

        return $json;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }
    
    public function setProjectSlug($projectSlug)
    {
        $this->projectSlug = $projectSlug;
    }
}
