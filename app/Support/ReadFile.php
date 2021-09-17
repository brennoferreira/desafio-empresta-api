<?php

namespace App\Support;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class ReadFile
{
    private $fileData;

    public function __construct(string $file, string $driver = 'local')
    {
        $exists = Storage::disk($driver)->exists($file);

        if(!$exists) {
            throw new \Exception('Arquivo não encontrado: ' . $file, 400);
        }

        $contents = Storage::disk($driver)->get($file);

        if(empty($contents)) {
            throw new \Exception('A informação não conseguiu ser coletada.', 400);
        }

        $this->fileData = json_decode($contents, true);
    }

    /**
     * Returns the contents retrieved from the file, as a collection
     *
     * @return Collection
     */
    public function getDataFileCollection(): Collection
    {
        return Collection::make($this->fileData);
    }

    /**
     * Returns a collection of the contents of the file, based on the key passed in the argument.
     *
     * @param string $key
     * @return Collection
     */
    public function getDataFileCollectionByKey(string $key)
    {
        $collection = [];
        foreach ($this->fileData as $data) {
            $collection[] = $data[$key];
        }

        return Collection::make($collection);
    }
}
