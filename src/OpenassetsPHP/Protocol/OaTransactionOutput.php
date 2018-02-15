<?php
namespace youkchan\OpenassetsPHP\Protocol;
use BitWasp\Bitcoin\Script\ScriptFactory;
use youkchan\OpenassetsPHP\Util;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;
use youkchan\OpenassetsPHP\Protocol\AssetDefinition;
use youkchan\OpenassetsPHP\Protocol\AssetDefinitionLoader;
use BitWasp\Bitcoin\Script\Classifier\OutputClassifier;
use BitWasp\Bitcoin\Script\Script;
use BitWasp\Bitcoin\Amount;
use Exception;


class OaTransactionOutput
{
    public $value;         
    public $script;        
    public $asset_id;       
    public $asset_quantity;
    public $output_type;
    public $account;
    public $metadata;
    public $asset_definition_url;
    public $asset_definition;
    public $network;
    
    public function __construct($value, Script $script, $asset_id = null, $asset_quantity = 0, $output_type = OutputType::UNCOLORED, $metadata = null, $network = null)
    {
        if (!OutputType::isLabel($output_type)) {
            throw new Exception ('invalid output type');
        }
        
        if ($asset_quantity < 0 && $asset_quantity >= MarkerOutput::MAX_ASSET_QUANTITY) {
            throw new Exception ('invalid output type');
        }
        $this->value = $value;
        $this->script = $script;
        $this->asset_id = $asset_id;
        $this->asset_quantity = $asset_quantity;
        $this->output_type = $output_type;
        $this->metadata = $metadata;
        $this->get_load_asset_definition_url();
        $this->network = $network;
    }

    public function get_asset_amount() {
        $divisibility = $this->get_divisibility();
        if ($divisibility > 0 ){
            return $this->asset_quantity / (10 ** $divisibility);
        } else {
            return $this->asset_quantity;
        }
    }

    public function get_divisibility() {
        if (!$this->valid_asset_definition() || is_null(!$this->assetdefinition->divisibility)) {
            return 0;
        }
        return $this->asset_definition->divibility;
    }

    public function get_proof_of_authenticity() {
        if ($this->valid_asset_definition()) {
            return $this->asset_definition->proof_of_authenticity;
        } else {
            return false;
        }
    }

    public function get_asset_id()
    {
        return $this->asset_id;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function get_asset_quantity()
    {
        return $this->asset_quantity;
    }
    public function get_metadata_url()
    {
        if ($this->metadata) {
            return substr($this->metadata, 2);
        }
        return null;
    }

    public function get_load_asset_definition_url()
    {
        $this->asset_definition_url = null;
        if (!$this->metadata || strlen($this->metadata) == 0) {
            return null;
        }
        $prefix = 'u=';
        if (substr($this->metadata,  0, strlen($prefix)) === $prefix) {
            $metadata_url = $this->get_metadata_url();
            $this->asset_definition = $this->load_asset_definition($metadata_url);
            if ($this->valid_asset_definition()) {
                $this->asset_definition_url = $metadata_url;
            } else {
                $this->asset_definition_url = "The asset definition is invalid. $metadata_url";
            }
        } else {
            $this->asset_definition_url = 'Invalid metadata format.';
        }
        return $this->asset_definition_url;
    }

    public function valid_asset_definition()
    {
        if (is_null($this->asset_definition)){
            return false;
        } 
        return $this->asset_definition->has_asset_id($this->asset_id);
    }

    public function load_asset_definition($url)
    {
        $loader = new AssetDefinitionLoader($this->get_metadata_url());
        return $loader->load_definition();
    }


    public function get_script()
    {
        return $this->script;
    }

    public function to_hash()
    {
        $amount = new Amount();
        return [
            "address" => $this->get_address(),
            "oa_address" => $this->get_oa_address(),
            "script" => $this->script->getBuffer()->getHex(),
            "amount" => $amount->toBtc($this->value),
            "asset_id" => $this->asset_id,
            "asset_quantity" => $this->asset_quantity,
            "asset_amount" => $this->get_asset_amount(),
            "asset_definition_url" => $this->asset_definition_url,
            "proof_of_authenticity" => $this->get_proof_of_authenticity(),
            "output_type" => OutputType::output_type_label($this->output_type)
        ];
    }

    public function get_address() {
        $classifier = new OutputClassifier();
        if ($classifier->isMultisig($this->script)) {
            $handler = new Multisig($this->script);
            foreach ($handler->getKeys() as $address) {
                if($address == null) {
                    return null;
                }
            }
        }

        return Util::script_to_address($this->script, $this->network->get_bclib_network());
    }

    public function get_oa_address() {
        $address = $this->get_address();
        if (is_null($address)) {
            return null;
        }

        if (is_array($address)) {
            $result = [];
            foreach ($address as $item) {
                $result[] = Util::convert_address_to_oa_address($item);
            }
            return $result;
        }
        return Util::convert_address_to_oa_address($address);
    }
}
