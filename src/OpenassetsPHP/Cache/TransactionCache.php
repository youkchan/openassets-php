<?php
namespace youkchan\OpenassetsPHP\Cache;
use youkchan\OpenassetsPHP\Cache\DBAccess;
use youkchan\OpenassetsPHP\Protocol\OaTransactionOutput;
use BitWasp\Bitcoin\Script\ScriptFactory;
use Exception;

class TransactionCache extends DBAccess
{

    public function setup() {
        $sql = "CREATE TABLE IF NOT EXISTS Tx(TransactionHash BLOB,SerializedTx BLOB,PRIMARY KEY (TransactionHash))";
        $result = $this->db->exec($sql);
    }

    public function get($transaction_id) {
        $sql = "SELECT SerializedTx FROM Tx WHERE TransactionHash = :transaction_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":transaction_id", $transaction_id);
        $result = $stmt->execute()->fetchArray();
        if (!$result) {
            return null;
        }

        return $result["SerializedTx"];
    }
   
    public function set($transaction_id, $serialized_transaction ){
        $sql = "INSERT INTO Tx (TransactionHash, SerializedTx) VALUES (:transaction_id, :serialized_transaction)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":transaction_id", $transaction_id);
        $stmt->bindValue(":serialized_transaction", $serialized_transaction);
        $stmt->execute();
    }
}
