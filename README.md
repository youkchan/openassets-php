# openassetsphp
The implementation of the [Open Assets Protocol](https://github.com/OpenAssets/open-assets-protocol) for PHP.

## Install

```php
composer require youkchan/openassetsphp
```

## Sample

sampleディレクトリにあるsample.phpを参照ください。  
sampleディレクトリから出してvendorディレクトリと同じ階層に置いて実行してください。  
現在はlitecoinとmonacoinのtestnetで使用できるようにしています。  
デフォルトではmonacoin testnetに接続します。  

フルノードのオプションでtxindex=1にする必要があります。
もしtxindex=1でサービスが起動しなくなった場合は、-reindexをつけて起動してください。

## Acknowledgment
下記プロジェクトを大いに参考にしました。参考にしました（大切な事なのでry）  
https://github.com/haw-itn/openassets-ruby  
https://github.com/OKohei/openassets-php  
