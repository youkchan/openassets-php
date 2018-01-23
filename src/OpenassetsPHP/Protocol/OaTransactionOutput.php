<?php
namespace youkchan\OpenassetsPHP\Protocol;
use BitWasp\Bitcoin\Script\ScriptFactory;
use youkchan\OpenassetsPHP\Protocol\OutputType;
use youkchan\OpenassetsPHP\Protocol\MarkerOutput;
use youkchan\OpenassetsPHP\Protocol\AssetDefinition;
use youkchan\OpenassetsPHP\Protocol\AssetDefinitionLoader;
use BitWasp\Bitcoin\Script\Script;
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
    
    public function __construct($value, Script $script, $asset_id = null, $asset_quantity = 0, $output_type = OutputType::UNCOLORED, $metadata = null)
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
}
