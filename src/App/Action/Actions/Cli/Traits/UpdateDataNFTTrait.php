<?php

namespace App\Action\Actions\Cli\Traits;

trait UpdateDataNFTTrait
{

    protected function getTableNFTs(string $prefix, mixed $affix = null)
    {
        if ($affix) {
            return self::CHAIN . '_' . $prefix . '_' . $affix . '_nfts';
        }

        return self::CHAIN . '_' . $prefix . '_nfts';
    }
}
