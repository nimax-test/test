<?php

// модель данных курсов валют
class Rates extends Zend_Db_Table {

    // таблица в базе данных
    protected $_name = 'rates';

    // пишем XML в базу
    public function addXml($xml) {
        // удаляем устаревшие данные
        $date = $xml->attributes()->Date;
        $this->delete("date = '{$date}'");
        // формируем новые данные
        foreach ($xml as $rate) {
            $data = [
                'date' => $date,
                'name' => $rate->Name,
                'value' => $rate->Value,
                'numcode' => $rate->NumCode,
                'nominal' => $rate->Nominal,
                'charcode' => $rate->CharCode,
                'valuteid' => $rate->attributes()->ID,
            ];
            $this->insert($data); // пишем новые данные в базу
        }
    }

}
