<?php

namespace app\models;

class Elastic extends \yii\elasticsearch\ActiveRecord
{

    public static function getIndex()
    {
        return self::getCommand()->getIndices();
    }

    public static function getCommand()
    {
        return self::getDb()->createCommand();
    }

    public static function getField($index)
    {
        return self::getCommand()->getMapping($index);
    }

    public static function luceneSearch($index, $query)
    {
        return self::getCommand()->luceneSearch($index, urldecode($query));
    }


    public static function elasticSearch($index, $query)
    {
        $opt = [];

        //json查询
        $config = [
            "index" => $index,
            "queryParts" => json_encode($query),
        ];
        $command = self::getDb()->createCommand($config);
        return $command->search($opt);
    }

}