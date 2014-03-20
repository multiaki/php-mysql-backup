<?php namespace Dimsav\Backup;

class Project {

//    private $config;

    // User this in the compressor
    private $generatedFiles = array();


    // Use this in the compressor
    public function getBackupFile($extension = '')
    {
        $timestamp = date($this->config->get('app.timestamp_prefix', "Y.m.d.H.i."));

        $file = $this->config->get('app.backups_dir');
        $file.= "/{$this->name}/{$timestamp}{$this->name}";
        $file.= $extension ? ".{$extension}" : '';

        return $file;
    }

    // User this in the compressor
    public function addToGeneratedFiles(array $files)
    {
        $this->generatedFiles = array_merge($this->generatedFiles, $files);
    }

    // Use this in the parser
    private function getConfig($parameter)
    {
        return $this->config->get(
            "projects.projects.$this->name.$parameter",
            $this->config->get("projects.default.$parameter")
        );
    }

}