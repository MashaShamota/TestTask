<?php

class SQLQueries
{
    /**
     * @param $count amount of top countries
     * @return string sql query for selecting top countries
     */
    public static function getSelectTopCountriesQuery($count)
    {
        return '
      SELECT
        country,
        sum(hints) as total
      FROM `actions`
      GROUP BY country
      ORDER BY total DESC
      LIMIT ' . $count;
    }

    /**
     * @param array $countries list of top countries
     * @return string sql query for selecting statistics
     */
    public static function getSelectStatistics(array $countries)
    {
        array_walk($countries, function (&$val) {
            $val = "'" . $val . "'";
        });
        return '
      SELECT
          country,
          action,
          sum(hints) as total
        FROM `actions`
        WHERE country IN (' . implode(',', $countries) . ')
        AND date >= curdate() - INTERVAL DAYOFWEEK(curdate())+6 DAY
        GROUP BY country,action';
    }

    /**
     * @return string  sql query for updating event counter
     */
    public static function getUpdateCounterQuery()
    {
        return '
      UPDATE actions
      SET hints = hints + 1
      WHERE action = :event
      AND country = :country
      AND DATE(`date`) = :date';
    }

    /**
     * @return string sql query for inserting new record
     */
    public static function getInsertEventQuery()
    {
        return '
      INSERT INTO actions
      (action, country, date, hints)
      VALUES
      (:event, :country, :date, :hints)';
    }
}
