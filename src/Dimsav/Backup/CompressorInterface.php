<?php namespace Dimsav\Backup;

use Dimsav\UnixZipper;

interface CompressorInterface {
    public function __construct(Project $project, UnixZipper $zipper);
    public function compress();
} 