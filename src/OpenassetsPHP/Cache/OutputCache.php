<?php
namespace youkchan\OpenassetsPHP\Cache;
use youkchan\OpenassetsPHP\Cache\DBAccess;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use BitWasp\Bitcoin\Script\ScriptFactory;
use Exception;

class OutputCache extends DBAccess
{

    public function setup() {
        $sql = "CREATE TABLE IF NOT EXISTS Output(TransactionHash BLOB,OutputIndex INT,Value BigInt,Script BLOB,AssetId BLOB,AssetQuantity INT,OutputType INT,Metadata BLOB,PRIMARY KEY (TransactionHash, OutputIndex))";
        $result = $this->db->exec($sql);
    }

    public function get($transaction_id, $index) {
        $sql = "SELECT Value,Script,AssetId,AssetQuantity,OutputType,Metadata FROM Output WHERE TransactionHash = :transaction_id AND OutputIndex = :index";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":transaction_id", $transaction_id);
        $stmt->bindValue(":index", $index);
        $result = $stmt->execute()->fetchArray();
        if (!$result) {
            return null;
        }

        $output = new OaTransactionOutput($result["Value"], ScriptFactory::fromHex($result["Script"]), $result["AssetId"], $result["AssetQuantity"] ,$result["OutputType"],$result["Metadata"]);
        return $output;
    }
   
    public function set($transaction_id, $index, $value, $script,$asset_id, $asset_quantity, $output_type, $metadata) {
        $sql = "INSERT INTO Output (TransactionHash, OutputIndex, Value,Script,AssetId,AssetQuantity,OutputType,Metadata) VALUES (:transaction_id, :index, :value, :script, :asset_id, :asset_quantity, :output_type, :metadata)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":transaction_id", $transaction_id);
        $stmt->bindValue(":index", $index);
        $stmt->bindValue(":value", $value);
        $stmt->bindValue(":script", $script);
        $stmt->bindValue(":asset_id", $asset_id);
        $stmt->bindValue(":asset_quantity", $asset_quantity);
        $stmt->bindValue(":output_type", $output_type);
        $stmt->bindValue(":metadata", $metadata);
        $stmt->execute();
    }
}
