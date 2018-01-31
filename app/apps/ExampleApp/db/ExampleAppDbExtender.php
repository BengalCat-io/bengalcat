<?php

namespace Bc\App\Apps\ExampleApp\Db;

use Bc\App\Core\DbExtender;

class ExampleAppDbExtender extends DbExtender {

    protected $where = [];
    protected $limit;
    protected $orderby;

    public function formatDataKeys($data)
    {
        $keys     = array_keys($data);
        $bindKeys = array_map(function($key) {
            return ":{$key}";
        }, $keys);

        return array_combine($bindKeys, array_values($data));
    }

    public function setLimitFromData($data, $default = -1)
    {
        if (isset($data['limit'])) {
            $this->setLimit($data['limit']);
        }

        $this->setLimit($default);
    }

    public function setOrderbyFromData($data, $default = -1)
    {
        if (isset($data['orderby'])) {
            $this->setLimit($data['orderby']);
        }

        $this->setOrderby($default);
    }

    public function addWhere($where)
    {
        if (empty($where)) {
            return;
        }

        $this->where[] = $where;
    }

    /**
     * Use for checking if a column is equal or not equal to several values.
     *
     * Great use might be like:
     *
     * $q->addWhereColumnValues('perm_key', ['login', 'user-edit-own'], '=', 'AND');
     *
     * Which will help make a query where section like:
     * "WHERE (perm_key = 'login' and perm_key = '
     *
     * @param string $columnName Name of the column ie perm_key
     * @param array $values The array of values that the column equals or does not equal etc.
     * @param type $operator The operator to use for all of the values ie =, != like etc
     * @param type $delimeter The logic to join together the expressions by ie AND, OR
     * @param type $dataPrefix Name of prefix to append to unique data key value array ie 'has_perm_'
     * @return array $data The array of $data key pair values needed for binded param values.
     */
    public function addWhereColumnValues($columnName, $values = [], $operator = '=', $delimeter = 'AND', $dataPrefix = '')
    {
        if (empty($values)) {
            return '';
        }

        $data = []; // need key data pairs to populate query.

        foreach ($values as $value) {

        }
    }

    public function addWhereByKey($key, $data, $operator = '=', $bindNameOverride = '')
    {
        if (!isset($data[$key])) {
            return;
        }

        $bindName = !empty($bindNameOverride)
                ? ":{$bindNameOverride}"
                : ":{$key}";

        $this->where[] = "$key $operator $bindName";

    }

    public function getWhere()
    {
        return $this->where;
    }

    public function getWhereToSqlString($delimeter = 'AND', $addWhere = false)
    {
        if (empty($this->where)) {
            return '';
        }

        $where = $addWhere ? 'where ' : '';

        return $where . implode(" $delimeter ", $this->where);
    }

    public function setWhere($where = [])
    {
        $this->where = $where;

        return $this;
    }

    public function setLimit($limit = -1)
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit() {
        return $this->limit;
    }

    public function getLimitToSqlString()
    {
        return $this->limit > 0 ? "limit $this->limit" : '';
    }

    public function setOrderby($orderby = '')
    {
        $this->orderby = $orderby;

        return $this;
    }

    public function getOrderby() {
        return $this->orderby;
    }

    public function getOrderbyToSqlString()
    {
        return $this->orderby > 0 ? "orderby $this->orderby" : '';
    }
}

