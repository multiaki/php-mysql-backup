<?php namespace Dimsav\Backup;

use Dimsav\UnixZipper;

class DatabaseCompressor implements CompressorInterface {

    /** @var  \Dimsav\UnixZipper */
    private $zipper;

    public function __construct(Project $project, UnixZipper $zipper) {
        $this->zipper  = $zipper;
        $this->project = $project;
    }

    public function compress() {
        // https://github.com/dizda/CloudBackupBundle/blob/master/Databases/MySQL.php
    }

}