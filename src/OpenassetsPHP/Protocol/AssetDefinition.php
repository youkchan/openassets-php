<?php
namespace youkchan\OpenassetsPHP\Protocol;

class AssetDefinition 
{
    public $asset_definition_url;
    public $asset_ids;
    public $name_short;
    public $name;
    public $contract_url;
    public $issuer;
    public $description;
    public $description_mime;
    public $type;
    public $divisibility;
    public $link_to_website;
    public $icon_url;
    public $image_url;
    public $version;
    public $proof_of_authenticity;
    public function __construct()
    {
        $this->asset_definition_url = null;
        $this->asset_ids = [];
        $this->name_short = null;
        $this->name = null;
        $this->contract_url = null;
        $this->issuer = null;
        $this->description = null;
        $this->description_mime = null;
        $this->type = null;
        $this->divisibility = 0;
        $this->link_to_website = null;
        $this->icon_url = null;
        $this->image_url = null;
        $this->version = '1.0';
        $this->proof_of_authenticity = false;
    }
    public static function parse_json($json)
    {
        $data = json_decode($json);
        $asset_definition = new AssetDefinition();
        $asset_definition->asset_ids = $data->asset_ids;
        $asset_definition->name_short = $data->name_short;
        $asset_definition->name = $data->name;
        $asset_definition->contract_url = isset($data->contract_url) ? $data->contract_url : null ;
        $asset_definition->issuer = isset($data->issuer) ? $data->issuer : null;
        $asset_definition->description = isset($data->description) ? $data->description: null;
        $asset_definition->description_mime = isset($data->description_mime) ? $data->description_mime : null;
        $asset_definition->type = isset($data->type) ? $data->type : null;
        $asset_definition->divisibility = isset($data->divisibility) ? $data->divisibility : null ;
        $asset_definition->link_to_website = isset($data->link_to_website) ? $data->link_to_website  : null;
        $asset_definition->icon_url = isset($data->icon_url) ? $data->icon_url : null;
        $asset_definition->image_url = isset($data->image_url) ? $data->image_url : null;
        $asset_definition->version = isset($data->version) ? $data->version : null;
        return $asset_definition;
    }
    
    public function has_asset_id($asset_id)
    {
        if ($this->asset_ids == null || empty($this->asset_ids)) {
            return false;
        }
        return in_array($asset_id, $this->asset_ids);    
    }
}
