<?php
namespace youkchan\OpenassetsPHP\Protocol;
use BitWasp\Bitcoin\Bitcoin;
use BitWasp\Bitcoin\Network\NetworkFactory;
use BitWasp\Buffertools\Buffer;
use youkchan\OpenassetsPHP\Util;
use Exception;


class MarkerOutput
{
    const OAP_MARKER = "4f41";
    const VERSION = "0100";

    protected $asset_quantities;
    protected $metadata;

    public function __construct($asset_quantities, $metadata)
    {
        $this->asset_quantities = $asset_quantities;
        $this->metadata = $metadata;
    }

    public static function deserialize_payload($payload)
    {
        if (self::is_valid_payload($payload) !== true) {
            return null;
        }

        $payload = substr($payload, strlen(self::OAP_MARKER.self::VERSION));
        $parsed_asset_quantity = self::parse_asset_quantity($payload);
        $asset_quantity = $parsed_asset_quantity[0];
        $payload = $parsed_asset_quantity[1];
        $base = null;
        foreach(str_split($payload, 2) as $byte) {
            $base .= Buffer::hex($byte)->getInt() >= 128 ? $byte : $byte.'|';
        }

        $base = substr($base, 0, -1);
        $data = explode('|', $base);
        $list = implode(array_slice($data, 0, $asset_quantity));
        $asset_quantities = Util::decode_leb128($list);
        $metaHex = Buffer::hex($payload)->slice(Buffer::hex($list)->getSize() + 1);
        $metadata = empty($metaHex) ? NULL : $metaHex->getBinary();
        return new MarkerOutput($assetQuantities, $metadata);
    }

    private static function parse_asset_quantity($payload)
    {
        $buffer = Buffer::hex($payload);
        switch ($buffer->slice(0,1)->getHex()) {
        case "fd":
            return [$buffer->slice(1,2)->getInt(), $buffer->slice(3)->getHex()];
        case 'fe':
            return [$buffer->slice(1,4)->getInt(), $buffer->slice(5)->getHex()];
        default:
            return [$buffer->slice(0,1)->getInt(), $buffer->slice(1)->getHex()];
        }
    }

    public static function is_valid_payload($payload) {

        if (is_null($payload)) {
            return false;
        }
        if (substr($payload,  0, 8) !== self::OAP_MARKER.self::VERSION) {
            return false;
        }
        //ToDo:: readLeb128
        //ToDo:: readVarInteger
        return true;

    }
}
