<?php

// модель данных курсов валют
class Rates extends Zend_Db_Table {

    // таблица в базе данных
    protected $_name = 'rates';

    // пишем данные в базу
    public function updateData($source, $date, $data) {
        $where = "source = '{$source}' AND date = '{$date}'";
        $this->delete($where); // сперва удаляем старые данные
        foreach ($data as $row) { // и затем пробегаемся для проверки
            if ($row['code'] && $row['nominal'] && $row['name'] && $row['value']) {
                $row['value'] = str_replace(",", ".", $row['value']); // float
                $this->insert($row); // пишем новые
            }
        }
    }

    // выборка по источнику и дате
    public function getData($source, $date) {
        $select = $this->select(); // выборка по источнику и дате
        $select->where('source = ?', $source)->where('date = ?', $date);
        return $this->fetchAll($select); // возвращаем искомые данные из БД
    }

}
