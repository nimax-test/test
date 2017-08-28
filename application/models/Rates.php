<?php

// модель данных курсов валют
class Rates extends Zend_Db_Table {

    // таблица в базе данных
    protected $_name = 'rates';

    // идентификатор для кэшаs
    private function cacheId($source, $date) {
        return $id = $source . ((int) $date);
    }

    // обновление кэша
    private function cacheUpdate($source, $date) {
        $caсhe = Zend_Registry::get('cache');
        $id = $this->cacheId($source, date);
        $caсhe->remove($id);
    }

    // пишем данные в базу
    public function updateData($source, $date, $data) {
        $where = "source = '{$source}' AND date = '{$date}'";
        $this->delete($where); // удаляем старые данные
        foreach ($data as $row) { // и после проверки
            if ($row['code'] && $row['nominal'] && $row['name'] && $row['value']) {
                $this->insert($row); // пишем новые
            }
        }
        $this->cacheUpdate($source, date); // обновляем кэш
    }

    // выборка по источнику и дате
    public function getData($source, $date) {
        $id = $this->cacheId($source, date); // id 
        $caсhe = Zend_Registry::get('cache'); // кэш
        if (!$data = $caсhe->load($id)) { // проверяем кэш
            $select = $this->select(); // выборка по источнику и дате
            $select->where('source = ?', $source)->where('date = ?', $date);
            $data = $this->fetchAll($select); // выборка из базы
            $caсhe->save($data, $id); // в кэш
        }
        return $data;
    }

}
